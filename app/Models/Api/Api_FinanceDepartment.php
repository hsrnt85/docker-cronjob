<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_FinanceDepartment extends Model
{
    use HasFactory;

    protected $table = 'finance_department';

    public static function get_finance_department(){

        $data = Self::select('id','agency_code','department_code','department_name', 'ptj_code', 'ptj_name','email')->where('data_status','1')->first();
        return $data;
     }

}
