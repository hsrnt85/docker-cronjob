<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TenantsPaymentNotice extends Model
{
    use HasFactory;
    protected $table    = 'tenants_payment_notice';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id')->where('data_status', 1);
    }

    public function income_account()
    {
        return $this->belongsTo(IncomeAccountCode::class, 'income_account_code_id');
    }

    public function transaction()
    {
        return $this->hasOne(TenantsPaymentTransaction::class, 'tenants_payment_notice_id', 'id');
    }

    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

    public static function get_latest_payment_notice_by_district($district_id){

        $sub1 = self::select(DB::raw('MAX(id) as id'))->groupBy('tenants_id');

        $data = self::from('tenants_payment_notice as tpn1')    
            ->select('tpn1.*')
            ->join(DB::raw('(' . $sub1->toSql() . ') tpn2'), function ($join) use($district_id){
                $join->on('tpn1.id', '=', 'tpn2.id')
                    ->where(['data_status' => 1, 'district_id' => $district_id]);
            })
            ->where(['data_status' => 1, 'district_id' => $district_id])
            ->orderByDesc('notice_date')
            ->orderByDesc('running_no')
            ->get();

        return $data;
    }

    public static function get_total_amount_by_tpn_id($id){

        $data = DB::table('tenants_payment_notice as tpn')
            ->select('tpn.id','tpn.payment_notice_no',  DB::raw('SUM(tpn_vot.amount) as total_amount'), 'tpn.notice_date')
            ->join('tenants_payment_notice_vot_list as tpn_vot', 'tpn.id' , '=', 'tpn_vot.tenants_payment_notice_id')
            ->where(['tpn.data_status' => 1, 'tpn_vot.data_status' => 1, 'tpn.id' => $id])
            ->groupBy( 'tpn_vot.tenants_payment_notice_id')
            ->first();

        return $data;
    }

    //account reconciliation ispeks/igfmas
    public static function tenants_payment_notice_vot($salary_deduction_code, $year, $month, $payment_category_id)
    {
        $data = self::from('tenants_payment_notice as tpn')->join('tenants_payment_notice_vot_list as tpnvl','tpn.id','=','tpnvl.tenants_payment_notice_id')
            ->selectRaw('tpnvl.income_account_code_id, tpnvl.salary_deduction_code,tpnvl.ispeks_account_code,tpnvl.ispeks_account_description,tpnvl.income_code,tpnvl.income_code_description,tpnvl.account_type_id,tpnvl.payment_category_id,tpnvl.flag_outstanding,tpnvl.services_status_id,tpnvl.flag_ispeks,sum(tpnvl.amount) AS amount')
            ->whereYear('tpn.notice_date', $year)->whereMonth('tpn.notice_date', $month)->where('salary_deduction_code', $salary_deduction_code)
            ->where('tpnvl.payment_category_id',$payment_category_id)->whereNot('payment_status', 2)->where('tpn.data_status', 1)->where('tpnvl.data_status', 1)
            ->orderBy('no_ic','ASC')
            ->orderBy('salary_deduction_code','ASC')
            ->groupBy(['income_code'])
            ->get();

        return $data;
    }

    public static function tenants_payment_notice_item($salary_deduction_code, $year, $month, $payment_category_id)
    {
        $data = self::from('tenants_payment_notice as tpn')->join('tenants_payment_notice_vot_list as tpnvl','tpn.id','=','tpnvl.tenants_payment_notice_id')
            ->selectRaw('tpn.no_ic as ic_no, sum(tpnvl.total_amount_after_adjustment) AS amount')
            ->whereYear('tpn.notice_date', $year)->whereMonth('tpn.notice_date', $month)->where('salary_deduction_code', $salary_deduction_code)
            ->where('tpnvl.payment_category_id',$payment_category_id)->whereNot('payment_status', 2)->where('tpn.data_status', 1)->where('tpnvl.data_status', 1)
            ->orderBy('no_ic','ASC')
            ->orderBy('salary_deduction_code','ASC')
            ->groupBy(['no_ic','salary_deduction_code'])
            ->get();

        return $data;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

}
