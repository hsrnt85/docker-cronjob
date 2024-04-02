<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_BankAccountPnj extends Model
{
    use HasFactory;

    protected $table = 'bank_account_pnj';

    public static function get_bank_account(){ 

        $data = self::join('bank', 'bank.id', '=', 'bank_account_pnj.bank_id')->where(['bank.data_status'=> 1, 'bank_account_pnj.data_status' => 1])->first();
        return $data;
    }

}
