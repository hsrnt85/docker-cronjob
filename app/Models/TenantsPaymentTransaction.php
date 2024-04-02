<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TenantsPaymentTransaction extends Model
{
    use HasFactory;
    protected $table    = 'tenants_payment_transaction';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    protected $casts = [
        'payment_date' => 'datetime', // This will cast payment_date to a Carbon instance
        'action_on' => 'datetime', // This will cast payment_date to a Carbon instance
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id')->where('data_status', 1);
    }
    
    public function transaction()
    {
        return $this->hasOne(TenantPaymentTransaction::class, 'tenants_payment_notice_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function payment_category()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id', 'id');
    }

    public function tenantPaymentTransactionVotList()
    {
        return $this->hasMany(TenantsPaymentTransactionVotList::class, 'tenants_payment_transaction_id');
    }

    //CHUNK PROCESS ----------------------------------------------------------------------------------------------------------------------------
    public static function get_total_payment_by_collector_statement_id($collectorStatementId){

        $data = self::select(DB::raw('SUM(total_payment) as total_payment'))->where(['collector_statement_id' => $collectorStatementId, 'data_status' => 1])
        ->groupBy('collector_statement_id')->first();

        return $data;
    }

    public static function get_payment_by_vot_using_collector_statement_id($collectorStatementId){

        $senariRekod = DB::table('tenants_payment_transaction as tpt')
        ->join('tenants_payment_transaction_vot_list as tpt_vot', function ($join){
            $join->on('tpt_vot.tenants_payment_transaction_id' , '=', 'tpt.id');
        })
        ->where(['tpt.data_status' => 1, 'tpt_vot.data_status' => 1])
        ->where('tpt.collector_statement_id', $collectorStatementId)
        ->groupBy('tpt_vot.income_account_code_id')
        ->select('tpt.collector_statement_id', 'tpt.payment_date', 'tpt_vot.amount' , 'tpt_vot.income_account_code_id', 'tpt_vot.income_code' ,DB::raw('SUM(tpt_vot.amount) as total_amount'))
        ->get();

        return $senariRekod;

    }

    // END CHUNK PROCESS  -------------------------------------------------------------------------------------------------------------------------

    //EDIT PAGE ->> TAB 2
    public static function get_tenants_payment_transaction($collector_statement_id){

        $data = DB::table('tenants_payment_transaction as tpt')
        ->join('tenants_payment_transaction_vot_list as tpt_vl', function ($join){
            $join->on('tpt_vl.tenants_payment_transaction_id' , '=', 'tpt.id');
        })
        ->where([ 'tpt_vl.data_status' => 1, 'tpt.data_status' => 1, 'tpt.collector_statement_id' => $collector_statement_id])
        ->select('tpt.id as tpt_id', 'tpt.id' , 'tpt.payment_notice_no', 'tpt.payment_date',  'tpt.payment_description', 'tpt.payment_receipt_no', 'tpt_vl.tenants_payment_transaction_id', 'tpt.total_payment',  DB::raw('SUM(tpt_vl.amount) as total_amount'),)
        ->groupBy('tpt.id')
        ->get();

        return $data;
    }

    public static function get_tenants_payment_transaction_by_month_year($year, $month, $payment_category_id){

        $data = self::whereYear('notice_date',$year)->whereMonth('notice_date',$month)->where('payment_category_id', $payment_category_id)->where('data_status',1)->first();
        return $data;
    }


}
