<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization';

    protected $fillable = ['code', 'name', 'data_status', 'action_on'];

    public $timestamps = false;

    public function payment_notice_transaction()
    {
        return $this->belongsTo(PaymentNoticeTransaction::class, 'tenants_id')->where('data_status', 1);
    }

    public function tenants_payment_notice()
    {
        return $this->hasMany(TenantsPaymentNotice::class, 'organization_id')->where('data_status', 1);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'service_type_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'organization_id');
    }
    
    public static function getAllAgencyWithTenant($year=0, $month=0)
    {
        //SHOW AGENCY WITH TENANT>0
        $data = self::withCount(['tenants_payment_notice as total_tenants' => function($q) use($year, $month) {
                $q->whereYear('notice_date', '=', $year)
                ->whereMonth('notice_date', '=', $month)
                ->where('tenants_payment_notice.district_id', '=', districtId());
            }]) 
            ->having('total_tenants', '>', 0)
            ->orderBy('organization.name')
            ->get();

        return $data;
    }

}
