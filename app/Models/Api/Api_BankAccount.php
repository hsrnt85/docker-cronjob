<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_account';

    public static function get_bank_account(){ 

        $data = self::join('bank', 'bank.id', '=', 'bank_account.bank_id')->where(['bank.data_status'=> 1, 'bank_account.data_status' => 1])->first();
        return $data;
    }

     // SLIP BANK PDF  (PP) 
   public static function get_bank_account_by_type($payment_method_id, $account_type){ 

    $data = self::select('id', 'account_no', 'account_name', 'bank_id', 'payment_method_id')
                ->where(['bank_account_type' => $account_type , 'payment_method_id' => $payment_method_id  ,'data_status' => 1])
                ->first();

    return $data;
    }

}
