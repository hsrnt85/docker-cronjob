<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tenant extends Model
{
    use HasFactory;
    protected $table    = 'tenants';
    public $timestamps  = false;

    protected $dates = [
        'quarters_offer_date',
        'quarters_acceptance_date',
        'leave_date',
        'blacklist_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function quarters_category()
    {
        return $this->belongsTo(QuartersCategory::class, 'quarters_category_id');
    }

    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'quarters_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'other_district_id');
    }

    public function district_tenant()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function residence_type()
    {
        return $this->belongsTo(ResidenceType::class, 'residence_type_id');
    }

    public function services_type()
    {
        return $this->belongsTo(ServicesType::class, 'services_type_id');
    }

    public function reason()
    {
        return $this->belongsTo(BlacklistReason::class, 'blacklist_reason_id');
    }

    public function penalties()
    {
        return $this->hasMany(BlacklistPenalty::class, 'tenants_id');
    }

    public function initialPenalty()
    {
        return $this->hasOne(BlacklistPenalty::class, 'tenants_id')->oldestOfMany();
    }

    public function monitor_leave()
    {
        return $this->hasOne(MonitoringTenantLeave::class, 'tenants_id')->where('data_status', 1);
    }

    // Denda kerosakan
    public function tenant_penalties()
    {
        return $this->hasMany(TenantsPenalty::class, 'tenants_id');
    }

    public function scopeBlacklisted($query)
    {
        return $query->whereNotNull('blacklist_date')
            ->where(function ($q) {
                $q->whereNotNull('blacklist_reason_id')
                    ->orWhereNotNull('blacklist_reason_others');
            });
    }

    public function scopeNotBlacklisted($query)
    {
        return $query->whereNull('blacklist_date')
        ->where(function($q){
            $q->whereNull('blacklist_reason_id')
            ->orWhereNull('blacklist_reason_others');
        });
    }

    public static function getAllCurrentTenantsByCategory($categoryId)
    {
        return self::where('data_status', 1)
            ->notBlacklisted()
            ->where(function ($query) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            })
            ->where('quarters_category_id', $categoryId)
            ->get();
    }

    public static function getCurrentTenantsByCategoryandIC($newIc ,$categoryId = null)
    {
         $data = self::where('data_status', 1)
            ->notBlacklisted()
            ->where(function ($query) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            })
            ->where('new_ic', $newIc)
            ->orderBy('id', 'desc');

            if($categoryId) { $data = $data->where('quarters_category_id', $categoryId); }

            $data = $data->first();

            return $data;
    }


    public static function getAllCurrentTenantsCount($district_id = null)
    {
        $query = self::where('data_status', 1)
            ->where(function ($query) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            });

        $query->whereHas('quarters_category', function ($subQ) use ($district_id) {
            $subQ->where('data_status', 1);
            if ($district_id>0) {
                $subQ->where('district_id', $district_id);
            }
        });

        $data = $query
            // ->groupBy('quarters_id')
            ->count();

        return $data;
    }

    public static function getSingleTenant($categoryId, $tenantId)
    {
        return self::where('data_status', 1)
            ->where('quarters_category_id', $categoryId)
            ->where('id', $tenantId)
            ->orderBy('id', 'desc')
            ->first();
    }

    public static function getAllLeftTenants($categoryId)
    {
        return self::where('data_status', 1)
            ->whereNotNull('leave_status_id')
            ->where('leave_status_id', '>', 1) //2: TA telah membuat pemantauan 3:admin telah membuat pengesahan
            ->where('quarters_category_id', $categoryId)
            ->get();
    }

    public static function getDistinctQuartersCategory()
    {
        $data = self::with('application.category')
            ->where('data_status', 1)
            ->whereHas('application.category', function ($q) {
                $q->distinct('id');
            })->get()
            ->pluck('application.category')
            ->unique();

        return $data;
    }

    public static function getEligibleForReplacement($category)
    {
        $data = self::whereNull('leave_date')
            ->where('leave_status_id', '!=', 1)
            ->where('quarters_category_id', $category->id)
            ->get();
        return $data;
    }

    public static function getAllCurrentTenantsMaintenanceFeeCountByCategory($categoryId)
    {
        $countTenantsQuarters = self::where('data_status', 1)
            ->where(function ($countTenantsQuarters) {
                $countTenantsQuarters->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            })->whereHas('quarters', function ($subQ) use ($categoryId) {
                $subQ->where('quarters_cat_id', $categoryId)
                    ->whereNotNull('maintenance_fee');
            })->count();

        return $countTenantsQuarters;
    }

    public static function getCurrentTenantsByDistrict($districtId, $year, $month)
    {
        return self::where('data_status', 1)
            ->where(function ($query) use ($year, $month) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0) // belum keluar
                    ->whereMonth('leave_date', $month)
                    ->whereYear('leave_date', $year);
            })
            ->where('district_id', $districtId)
            ->get();
    }

    public static function laporan_dinamik_tenant($from, $to, $selectedQuartersCategory, $selectedServicesId, $ic)
    {
        $reportData = self::with(['user.office.organization', 'user.spouse', 'application'])
            ->where('data_status', 1)
            ->where(function ($query) use ($to) {
                $query->where('leave_status_id', null);
                if($to) $query->orWhere('leave_date', '>', $to);
            });
        if($from && $to) $reportData =$reportData->whereBetween('quarters_acceptance_date', [$from, $to]);

        if ($selectedQuartersCategory) {
            $reportData = $reportData->where('quarters_category_id', $selectedQuartersCategory->id);
        }

        if ($selectedServicesId || $ic) {
            $reportData = $reportData->whereHas('user', function ($userSubq) use ($selectedServicesId, $ic) {
                if($selectedServicesId) $userSubq->where('services_type_id', $selectedServicesId);
                if($ic) $userSubq->where('new_ic', $ic);
            });
        }

        return $reportData->get();
    }

    public static function getAllBlacklistTenants($categoryId)
    {
        return self::where('data_status', 1)
            ->where('quarters_category_id', $categoryId)
            ->blacklisted()
            ->get();
    }

    //check active tenant by latest application
    public static function checkTenant()
    {
        $result = self::where('data_status', 1)->where('user_id', loginId())
            ->where(function ($query) {
                $query->whereNull('is_draft_leave')
                    ->orWhere('is_draft_leave', '=', 0);          // belum nak keluar
            })
            ->where(function ($query) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            })
            ->orderBy('id', 'DESC')->first();
        return $result;
    }


    public static function getExitMonitoringList($districtId = null)
    {
        $data = self::where('data_status', 1)->whereIn('leave_status_id', [1,2]);

        if ($districtId) {
            $data = $data->where('district_id', $districtId);
        }

        $data = $data->orderBy('leave_application_date', 'desc')->get();

        return $data;
    }

    //Laporan Notis Bayaran
    public static function tenantPaymentNoticeAll($selectedYear, $selectedMonth, $selectedDistrict, $selectedQuartersCategory, $selectedServicesType, $searchNoticeNo, $searchIcNo)
    {
        $tenantPaymentNoticeAll = Tenant::from('tenants as t')
            ->select('tpn.*', 't.name', 't.quarters_id')
            ->join('tenants_payment_notice AS tpn', function ($q) {
                $q->on('tpn.tenants_id', '=', 't.id')
                    ->where('tpn.data_status', 1);
            })
            ->where('t.data_status', 1)
            ->orderBy('t.name', 'DESC');

        if ($selectedYear) $tenantPaymentNoticeAll             = $tenantPaymentNoticeAll->whereYear('tpn.notice_date', $selectedYear);
        if ($selectedMonth) $tenantPaymentNoticeAll            = $tenantPaymentNoticeAll->whereMonth('tpn.notice_date', $selectedMonth);
        if ($selectedDistrict) $tenantPaymentNoticeAll         = $tenantPaymentNoticeAll->where('t.district_id', $selectedDistrict);
        if ($selectedQuartersCategory) $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->where('t.quarters_category_id', $selectedQuartersCategory);
        if ($selectedServicesType) $tenantPaymentNoticeAll     = $tenantPaymentNoticeAll->where('t.services_type_id', $selectedServicesType);
        if ($searchNoticeNo) $tenantPaymentNoticeAll           = $tenantPaymentNoticeAll->where('tpn.payment_notice_no', 'LIKE', '%' . $searchNoticeNo . '%');
        if ($searchIcNo) $tenantPaymentNoticeAll               = $tenantPaymentNoticeAll->where('t.new_ic', $searchIcNo);

        $tenantPaymentNoticeAll =  $tenantPaymentNoticeAll->get();
        return $tenantPaymentNoticeAll;
    }

    // Tenant.php
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public static function getInhabitedQuarters($q_cat_id, $quarters_id) // Kuarters yang masih berpenghuni
    {
        $result = self::select('id','quarters_id','quarters_category_id')->where(['quarters_category_id'=> $q_cat_id ,'data_status'=> 1])
            ->where(function ($query) {
                $query->whereNull('is_draft_leave')
                    ->orWhere('is_draft_leave', '=', 0);          // belum mohon keluar
            })
            ->where(function ($query) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            });
            if($quarters_id){
                $result = $result->where('quarters_id', $quarters_id);
            }
        return $result->first();

    }



}
