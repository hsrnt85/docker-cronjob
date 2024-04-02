<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutstandingType extends Model
{
    use HasFactory;
    protected $table    = 'outstanding_type';
    public $timestamps  = false;

    public static function get_outstanding_type(){

        //  $data = self::from('income_account_code as iac')
        //             ->join('outstanding_type as ot','ot.id','=','iac.flag_outstanding')
        //             ->select('iac.flag_outstanding','ot.outstanding_type')
        //             ->where(['iac.data_status'=>1,'ot.data_status'=>1])->groupBy('iac.flag_outstanding')->get();
    
        $data = self::select('outstanding_type')->where('data_status', 1)->get();

        return $data;
    }

}
