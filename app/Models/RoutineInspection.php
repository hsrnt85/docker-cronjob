<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoutineInspection extends Model
{
    use HasFactory;

    protected $table = 'routine_inspection';
    public $timestamps = false;
    protected $dates = ['inspection_date', 'action_on', 'deleted_on'];

    public function quarters_category()
    {
        return $this->belongsTo(QuartersCategory::class, 'quarters_category_id');
    }

    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'quarters_id');
    }

    public function status()
    {
        return $this->belongsTo(ApprovalStatus::class, 'approval_status_id');
    }

    public function monitoring_officer()
    {
        return $this->belongsTo(Officer::class, 'monitoring_officer_id');
    }

    public function approval_officer()
    {
        return $this->belongsTo(Officer::class, 'approval_officer_id');
    }

    public function inspection_transaction()
    {
        // return $this->hasMany(RoutineInspectionTransaction::class, 'routine_inspection_id');
        return $this->hasOne(RoutineInspectionTransaction::class, 'routine_inspection_id');
    }

    public static function getAllInspectionByCategoryId($category)
    {
        $data = self::with('quarters_category')
                ->with('quarters')
                ->with('status')
                ->where('data_status', 1)
                ->where('quarters_category_id', $category->id)
                ->whereDoesntHave('inspection_transaction')
                ->get();

        return $data;
    }

    // public static function getAllArchivedInspectionByCategoryId($category)
    // {
    //     $data = self::with('quarters_category')
    //             ->with('quarters')
    //             ->with('status')
    //             ->where('data_status', 1)
    //             ->where('quarters_category_id', $category->id)
    //             ->whereHas('inspection_transaction')
    //             ->get();

    //     return $data;
    // }

    public static function getAllArchivedInspectionByCategoryId_Done($category)
    {
        $data = self::with('quarters_category')
                ->with('quarters')
                ->with('status')
                ->where('data_status', 1)
                ->where('quarters_category_id', $category->id)
                ->whereHas('inspection_transaction', function ($query) {
                    $query->where('inspection_status_id', 1);
                })
                ->get();

        return $data;
    }

    public static function getAllArchivedInspectionByCategoryId_notDone($category)
    {
        $data = self::with('quarters_category')
                ->with('quarters')
                ->with('status')
                ->where('data_status', 1)
                ->where('quarters_category_id', $category->id)
                ->whereHas('inspection_transaction', function ($query) {
                    $query->where('inspection_status_id', 2);
                })
                ->get();

        return $data;
    }


    public static function getAllInspectionByApprovalOfficer($officerId)
    {
        $data = self::with('quarters_category')
                ->with('quarters')
                ->with('status')
                ->where('data_status', 1)
                ->where('approval_officer_id', $officerId)
                ->where('approval_status_id', null)
                ->get();

        return $data;
    }

    
    public static function getAllInspectionByApprovalOfficerWithStatus($officerId)
    {
        $data = self::with('quarters_category')
                ->with('quarters')
                ->with('status')
                ->where('data_status', 1)
                ->where('approval_officer_id', $officerId)
                ->whereNotNull('approval_status_id')
                ->get();

        return $data;
    }

    public static function getAllInspectionByDistrict($seeAllDistrict)
    {
        $data = self::with('inspection_transaction.inspectionStatus')
                ->where([
                    'data_status' => 1,
                ]);

        if(!$seeAllDistrict)
        {
            $data = $data->whereHas('quarters_category.district', function ($subQ) {
                $subQ->where('id', districtId());
            });
        }
        
        $data = $data->whereDoesntHave('inspection_transaction')
                ->orWhereHas('inspection_transaction', function($subQ){
                    $subQ->where('inspection_status_id', 2) //BELUM SELESAI
                    ->where('data_status', 1); 
                });
        
        // dd($data->toSql());
        $data = $data->get();

        return $data;
    }

    public static function getAllInspectionByMonth($month)
    {
        $data = self::with('quarters_category:id,name')
                ->with('monitoring_officer:id,users_id')
                ->with('monitoring_officer.user:id,name')
                ->where('data_status', 1)
                ->whereMonth('inspection_date', $month)
                ->select(DB::raw('DATE_FORMAT(inspection_date, "%Y-%m-%d") as inspection_date'), 'ref_no', 'quarters_category_id', 'monitoring_officer_id', 'address', 'remarks')
                ->get();
        
        return $data;
    }

    public static function checkAddressByDate($address, $date)
    {
        $count = self::where('address', $address)
                ->whereDate('inspection_date', $date)
                ->where('data_status', 1)
                ->count();
        
        return $count;
    }
}
