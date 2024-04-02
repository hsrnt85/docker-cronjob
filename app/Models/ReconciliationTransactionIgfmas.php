<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReconciliationTransactionIgfmas extends Model
{
    use HasFactory;
    protected $table = 'reconciliation_transaction_igfmas';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public static function get_year()
    {
        $data = self::select('year')->groupBy('year')->get();
        return $data;
    }

    public static function get_month()
    {
        $data = self::from('reconciliation_transaction_igfmas as rt')->join('month','rt.month','=','month.id')->select('month.name','month.id')->groupBy('month')->get();
        return $data;
    }
 
    public static function salary_deduction_sode_summary($year, $month)
    {
        $data = self::from('reconciliation_transaction_igfmas as rt')
                ->join('reconciliation_transaction_igfmas_item as rti','rt.id','=','rti.reconciliation_transaction_igfmas_id')
                ->selectRaw('rt.id, rt.salary_deduction_code, sum(amount) AS amount')
                ->where('rt.year', $year)->where('rt.month', $month)
                ->groupBy('rt.salary_deduction_code');
                
        return $data;
    }

    public static function reconciliation_transaction_by_id($id)
    {
        $data = self::where('id', $id)->first();
    
        return $data;
    }

    public static function reconciliation_transaction_by_year_month($year, $month)
    {
        $data = self::select('eft_no','eft_date')->where(['year'=> $year, 'month'=> $month])->whereNotNull('eft_no')->first();
    
        return $data;
    }

    public static function get_reconciliation_transaction_by_kod_potongan()
    {
        $data = self::get();

        return $data;
    }

    public static function reconciliation_transaction_list($year, $month, $payment_category_id)
    {
        $data = self::from('reconciliation_transaction_igfmas as rt')
        ->leftJoin("tenants_payment_transaction as tpt", function ($join) use($payment_category_id){
            $join->on(DB::raw('FIND_IN_SET(rt.id, tpt.reconciliation_transaction_id)'),">", DB::raw("'0'"));
                $join->where('tpt.payment_category_id', $payment_category_id);
                $join->where('tpt.data_status',1);
            })
            ->select('rt.*','tpt.payment_date','tpt.payment_receipt_no')
            ->where('rt.year', $year)->where('rt.month', $month)->whereNot('rt.data_status', 0)
            ->groupBy('rt.id');
        return $data;
    }

    public static function reconciliation_transaction($year, $month)
    {
        $data = self::from('reconciliation_transaction_igfmas as rt')
            ->select('rt.eft_no')
            ->where('rt.year', $year)->where('rt.month', $month)->where('rt.data_status', 2)->whereNot('rt.eft_no', "")
            ->first();
        return $data;
    }

    public static function reconciliation_transaction_item($salary_deduction_code, $year, $month)//$id
    {
        $data = self::from('reconciliation_transaction_igfmas as rt')
        ->join('reconciliation_transaction_igfmas_item as rti','rt.id','=','rti.reconciliation_transaction_igfmas_id')
        ->selectRaw('rti.ic_no, rt.salary_deduction_code, sum(rti.amount) AS amount')//
        ->where('salary_deduction_code', $salary_deduction_code)->where('year', $year)->where('month', $month)
        ->where('rt.data_status', 2)->where('rti.data_status', 2)//->where('rti.reconciliation_transaction_igfmas_id', $id)
        ->orderBy('ic_no','ASC')
        ->orderBy('salary_deduction_code','ASC')
        ->groupBy(['ic_no','salary_deduction_code'])
        ->get();

        return $data;
    }
}
