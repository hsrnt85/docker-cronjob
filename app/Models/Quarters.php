<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Quarters extends Model
{
    use HasFactory;

    protected $table    = 'quarters';
    public $timestamps  = false;

    public function category()
    {
        return $this->belongsTo(QuartersCategory::class, 'quarters_cat_id');
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'quarters_inventory', 'q_id', 'i_id')->withPivot('id','quantity', 'm_inventory_id', 'data_status', 'action_by', 'action_on', 'delete_by', 'delete_on');
    }

    public function application_quarters_categories()
    {
        return $this->hasMany(ApplicationQuartersCategory::class, 'quarters_id');
    }

    public function quarters_condition()
    {
        return $this->belongsTo(QuartersCondition::class, 'quarters_condition_id');
    }

    public function latest_tenant()
    {
        return $this->hasOne(Tenant::class, 'quarters_id')->where('data_status', 1)->latest('id');
    }

    public function current_active_tenant()
    {
        return $this->latest_tenant()
        ->where(function ($subQ) {
            $subQ->whereRaw('((leave_status_id is null) or (leave_status_id < 3 and leave_date is null) or (leave_status_id = 3 and leave_date > "'.currentDateDb().'"))');// belum keluar

        })
        ->where('data_status', 1);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'quarters_id');
    }

    public static function getAllAddress($category_id)
    {
        $data = Quarters::select('address_1')
                    ->where('data_status', 1)
                    ->where('quarters_cat_id', $category_id )
                    ->whereNotNull('unit_no')
                    ->groupBy('address_1')
                    ->get();
        return $data;
    }

    public static function getAvailableAddressByCategory($category_id)
    {
        $data = Quarters::select('address_1')
                    ->whereDoesntHave('application_quarters_categories')
                    ->whereNotNull('unit_no')
                    ->where('quarters_cat_id', $category_id )
                    ->where('data_status', 1)
                    ->groupBy('address_1')
                    ->get();
        return $data;
    }

    public static function getAvailableUnitByAddr($category_id, $address)
    {
        $data = Quarters::whereDoesntHave('current_active_tenant')
                    ->whereNotNull('unit_no')
                    ->where('quarters_cat_id', $category_id)
                    ->where('address_1', $address)
                    ->where('quarters_condition_id', 1)
                    ->where('data_status', 1)
                    ->get();
        return $data;
    }

    public static function getAvailableUnitAll($district_id = null)
    {
        $queryEmptyQuarters = Quarters::whereDoesntHave('application_quarters_categories')
                    ->whereNotNull('unit_no')
                    ->where('quarters_condition_id', 1)
                    ->where('data_status', 1);

        $queryEmptyQuarters = $queryEmptyQuarters->whereHas('category', function($subQ) use ($district_id){
            $subQ->where('data_status', 1);
            if($district_id>0){
                $subQ->where('district_id', $district_id);
            }
        });

        $countEmptyQuarters = $queryEmptyQuarters->count();

        $queryTenantLeftQuarters = Quarters::whereHas('latest_tenant', function($subQ){
                        $subQ->where('data_status', 1)
                        ->where('leave_status_id', 1)
                        ->where('leave_date', '<', Carbon::now());
                    })
                    ->whereNotNull('unit_no')
                    ->where('quarters_condition_id', 1)
                    ->where('data_status', 1);

        $queryTenantLeftQuarters = $queryTenantLeftQuarters->whereHas('category', function($subQ) use ($district_id){
            $subQ->where('data_status', 1);
            if($district_id>0){
                $subQ->where('district_id', $district_id);
            }
        });

        // dd($queryEmptyQuarters);
        $countTenantLeftQuarters = $queryTenantLeftQuarters->count();

        return $countEmptyQuarters + $countTenantLeftQuarters;
    }

    public static function getAllQuartersTotal($condition_id, $district_id = null, $condition_id_2 = null)
    {
        $query = Quarters::where('data_status' , 1);

        $query = $query->where(function ($query) {
            $query->where('quarters_condition_id','=',1)
                ->whereNotNull('unit_no')
                ->orWhere(function ($query) {
                    $query->where('quarters_condition_id','=',2)
                        ->orWhere('quarters_condition_id','=',3)
                        ->orWhereNull('quarters_condition_id');
                });
        });

        $query = $query->whereHas('category', function($subQ) use ($district_id){
            $subQ->where('data_status', 1);
            if($district_id>0){
                $subQ->where('district_id', $district_id);
            }
        });

        $data = $query->count();

        return $data;
    }

    public static function getEmptyUnitMaintenanceFeeAll($categoryId)
    {
        $countEmptyQuarters = Quarters::whereDoesntHave('application_quarters_categories')
                            ->whereNotNull('unit_no')
                            ->where('data_status', 1)
                            ->where('quarters_cat_id', $categoryId)
                            ->whereNotNull('maintenance_fee')
                            ->count();

        return $countEmptyQuarters;
    }

    public static function getAvailableUnitMaintenanceFeeAll($categoryId)
    {
        $countAvailableQuarters = Quarters::where('data_status', 1)
                            ->where('quarters_cat_id', $categoryId)
                            ->whereNotNull('maintenance_fee')
                            ->count();

        return $countAvailableQuarters;
    }

    public static function getStatisticByQuartersCategory($id, $status = null, $vacancy = null)
    {
        $query = Quarters::with('quarters_condition')
                ->with('current_active_tenant')
                ->where('quarters_cat_id', $id)
                ->where('data_status', 1);

        if ($status)
        {
            $query = $query->whereHas('quarters_condition', function($subQ) use($status){
                $subQ->where('id', $status);
            });
        }

        if($vacancy == 1)
        {
            $query = $query->whereDoesntHave('current_active_tenant')
            ->whereHas('quarters_condition', function($subQ){
                $subQ->where('id', 1);
            });
        }

        if($vacancy == 2)
        {
            $query = $query->whereHas('current_active_tenant')
            ->whereHas('quarters_condition', function($subQ){
                $subQ->where('id', 1);
            });
        }

        return $query->get();
    }

    public static function getStatisticByQuartersCategory2($quarters_categories, $eligibility, $condition)
    {
        $ids = $quarters_categories->pluck('id')->toArray();

        $query = Quarters::with('quarters_condition')
                ->with('current_active_tenant')
                ->whereIn('quarters_cat_id', $ids)
                ->where('data_status', 1);

        if ($eligibility == 'Boleh Ditawarkan')
        {
            $query = $query->where(function($innerQ){
                $innerQ->where('quarters_condition_id', 1)->doesntHave('current_active_tenant');
            });
        }else if ($eligibility == 'Tidak Boleh Ditawarkan')
        {
            $query = $query->where(function($innerQ){
                $innerQ->has('current_active_tenant')->orWhere('quarters_condition_id', '!=',1);
            });
        }

        if($condition == "BAIK") $condition_id = 1;
        else if($condition == "SEDANG DISELENGGARA") $condition_id = 2;
        else if($condition == "ROSAK") $condition_id = 3;

        if($condition) $query = $query->where('quarters_condition_id', '=',$condition_id);

        $query = $query->orderBy('quarters_cat_id','ASC')->orderBy('unit_no', 'ASC')->get();

        // dd($query->toSql());

        return $query;
    }

    public static function getUserProfileKuartersInfo($quarters_id)
    {
        $data = self::whereNotNull('unit_no')
                    ->where('id', $quarters_id)
                    ->where('data_status', 1)
                    ->first();
        return $data;
    }

    //------------------------------------------------------------------------------------------------------------------------
    // DASHBOARD
    //------------------------------------------------------------------------------------------------------------------------

    public static function getDashboardQuartersNoByCondition($district_id, $flag)
    {
        $query = Quarters::where('data_status' , 1);

        if($flag==2){
            $query = $query->where(function ($query) {
                $query->whereIn('quarters_condition_id' , [2,3])->orWhereNull('quarters_condition_id')->orWhereNull('unit_no');
            });
        }
        else{
            $query = $query->where('quarters_condition_id' , 1);
            $query = $query->whereNotNull('unit_no');
        }

        $query = $query->whereHas('category', function($subQ) use ($district_id){
            $subQ->where('data_status', 1);
            if($district_id>0) $subQ->where('district_id', $district_id);
        });

        $data = $query->count();

        return $data;
    }

    public static function getDashboardQuartersByCondition($district_id, $condition_id=null)
    {
        $query = Quarters::where('data_status' , 1);
        //$query = $query->whereNotNull('unit_no');
        $query = $query->whereHas('category', function($subQ) use ($district_id){
            $subQ->where('data_status', 1);
            if($district_id>0) $subQ->where('district_id', $district_id);
        });
        $query = $query->whereIn('quarters_condition_id' , [2,3]);
        //$query = $query->count();
        $query = $query->select('quarters_condition_id', DB::raw('COUNT(*) as count'))->groupBy('quarters_condition_id')->get();

        foreach ($query as $data) {
            $quarters_condition_id = $data->quarters_condition_id;
            $dataArr["condition_id"] = $quarters_condition_id;
            $dataArr["counter"] = $data->count;
        }
        return $dataArr;
    }

}
