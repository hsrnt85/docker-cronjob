<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IncomeAccountCode extends Model
{
    use HasFactory;
    protected $table    = 'income_account_code';
    public $timestamps  = false;

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function payment_category()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

    public static function get_salary_deduction_code($payment_category_id){

        $data = self::select('salary_deduction_code','ispeks_account_description')->where(['data_status'=>1, 'payment_category_id'=>$payment_category_id])
            ->groupBy('salary_deduction_code')->orderBy('account_type_id')->orderBy('salary_deduction_code', 'ASC')->get();
        return $data;
    }

    public static function get_senarai_vot(){

         $data = self::select('id','ispeks_account_code', 'ispeks_account_description', 'income_code','income_code_description','payment_category_id')->where(['data_status'=> 1])->orderBy('ispeks_account_code')->orderBy('income_code')->get();//, 'flag_ispeks' => 1s
         return $data;
     }


    public static function get_jenis_terimaan(){

        $data = self::select('ispeks_account_code', 'ispeks_account_description')->where(['data_status'=>1])->groupBy('ispeks_account_code')->orderBy('ispeks_account_description', 'ASC')->get();
        return $data;
    }

    public static function get_jenis_tunggakan(){

        $data = self::from('income_account_code as iac')->join('outstanding_type as ot','ot.id','=','iac.flag_outstanding')
            ->select('iac.flag_outstanding','ot.outstanding_type')
            ->where(['iac.data_status'=>1,'ot.data_status'=>1])
            ->groupBy('iac.flag_outstanding')
            ->get();
        return $data;
    }


}
