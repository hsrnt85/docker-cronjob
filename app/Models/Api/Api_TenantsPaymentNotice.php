<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_TenantsPaymentNotice extends Model
{
    use HasFactory;
    protected $table    = 'tenants_payment_notice';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    public function tenant()
    {
        return $this->belongsTo(Api_Tenant::class, 'tenants_id')->where('data_status', 1);
    }
    
    public function quarters_category()
    {
        return $this->belongsTo('App\Models\QuartersCategory', 'quarters_category_id');
    }

    public function income_account()
    {
        return $this->belongsTo(Api_IncomeAccountCode::class, 'income_account_code_id');
    }

    public static function get_payment_notice_by_district($district_id){

        $data = self::from('tenants_payment_notice as tpn')
        ->select('tpn.id','tpn.payment_notice_no', 'tpn.payment_date', 'tpn.payment_description','tpn.payment_receipt_no','tpn.tenants_id')
        ->join('tenants_payment_notice_vot_list as tpn_vot', 'tpn.id' , '=', 'tpn_vot.tenants_payment_notice_id')
        ->where(['tpn.data_status' => 1, 'tpn_vot.data_status' => 1, 'district_id' => $district_id])
        ->where('payment_status', 2) // 2 : full paid
        ->groupBy('tpn_vot.tenants_payment_notice_id')
        ->get();

        return $data;
    }

    public static function get_tenants_payment_notice_by_district($district_id, $search_date_from, $search_date_to){ //INDEX PAGE

        $data = self::from('tenants_payment_notice as tpn')
        ->select('tpn.id','tpn_vot.income_account_code_id',  DB::raw('SUM(tpn_vot.amount) as total_amount'), 'tpt.payment_date', 'tpt.payment_description',)
        ->join('tenants_payment_notice_vot_list as tpn_vot', 'tpn.id' , '=', 'tpn_vot.tenants_payment_notice_id')
        ->join('tenants_payment_transaction as tpt', 'tpt.tenants_payment_notice_id' , '=', 'tpn.id')
        ->where(['tpn.data_status' => 1, 'tpn_vot.data_status' => 1, 'tpt.data_status' => 1,'tpn.payment_status'=> 2])
        ->groupBy( 'tpn_vot.tenants_payment_notice_id');

        if($district_id){ $data = $data->where('tpn.district_id', $district_id);  }
        if($search_date_from){ $data = $data->where('tpn.notice_date','>=' , $search_date_from); }
        if($search_date_to  ){ $data = $data->where('tpn.notice_date','<=' , $search_date_to); }

        $data = $data->get();

        return $data;
    }

    public static function get_total_amount_by_tpn_id($id){

        $data = DB::table('tenants_payment_notice as tpn')
        ->select('tpn.id','tpn.payment_notice_no',  DB::raw('SUM(tpn_vot.amount) as total_amount'), 'tpn.payment_date', 'tpn.payment_description', 'tpn.payment_receipt_no')
        ->join('tenants_payment_notice_vot_list as tpn_vot', 'tpn.id' , '=', 'tpn_vot.tenants_payment_notice_id')
        ->where(['tpn.data_status' => 1, 'tpn_vot.data_status' => 1, 'tpn.id' => $id])
        ->groupBy( 'tpn_vot.tenants_payment_notice_id')
        ->first();

        return $data;
    }

    public function transaction()
    {
        return $this->hasOne(Api_TenantsPaymentTransaction::class, 'tenants_payment_notice_id', 'id');
    }

    public function paymentCategory()
    {
        return $this->belongsTo(Api_PaymentCategory::class, 'payment_category_id');
    }
}
