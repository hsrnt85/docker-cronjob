<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Tenant extends Model
{
    use HasFactory;
    protected $table    = 'tenants';
    public $timestamps  = false;

    protected $dates = [
        'quarters_offer_date',
        'quarters_acceptance_date',
        'leave_date',
    ];

    public function user()
    {
        return $this->belongsTo(Api_User::class, 'user_id');
    }

    public function leave_option()
    {
        return $this->belongsTo(Api_LeaveOption::class, 'leave_option_id');
    }

    public function application()
    {
        return $this->belongsTo('App\Models\Application', 'application_id');
    }

    public function quarters_category()
    {
        return $this->belongsTo('App\Models\QuartersCategory', 'quarters_category_id');
    }

    public function quarters()
    {
        return $this->belongsTo('App\Models\Quarters', 'quarters_id');
    }

    public function tenant_inventory()
    {
        return $this->hasMany('App\Models\TenantQuartersInventory', 'tenants_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'other_district_id');
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

    public static function getLatestActiveTenantByUserId($userId)
    {
        $data = self::where('user_id', $userId)
        ->where("data_status", 1)
        ->where(function($query){
            $query->whereNot('leave_status_id', 1)
            ->orWhere('leave_status_id', null);
        })
        ->orderBy('quarters_offer_date', 'DESC')
        ->first();

        return $data;
    }

    //check active tenant by latest application
    public static function checkTenantUsingIc($new_ic)
    {
        $result = self::where('data_status', 1)->where('new_ic', $new_ic)
        ->where(function($query) {
            $query->whereNull('is_draft_leave')
                    ->orWhere('is_draft_leave', '=', 0);          // belum nak keluar
            })
        ->where(function($query) {
                $query->whereNull('leave_status_id')
                        ->orWhere('leave_status_id', '=', 0);     // belum keluar
        })
        ->orderBy('id', 'DESC')->first();
        return $result;
    }

}
