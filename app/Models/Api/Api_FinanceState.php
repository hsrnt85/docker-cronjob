<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_FinanceState extends Model
{
    use HasFactory;

    protected $table = 'finance_state';

    public static function get_finance_state(){

        $data = Self::where('data_status','1')->first();
        return $data;
     }

}
