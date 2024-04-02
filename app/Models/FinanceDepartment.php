<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceDepartment extends Model
{
    protected $table = 'finance_department';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function finance_department_by_district($id){

        $data = self::select('department_code', 'department_name', 'ptj_code', 'ptj_name')->where('data_status', 1);
        
        if($id)  $data = $data->where('district_id' , $id);
        
        $data = $data->first();

        return $data;
    }
    
}
