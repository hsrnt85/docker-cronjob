<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceOfficerCategory extends Model
{
    use HasFactory;

    protected $table = 'finance_officer_category';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function FinanceOfficerByCategory($finance_officer_cat_id)
    {

        $data = self::where(['id' => $finance_officer_cat_id, 'data_status' => 1])->first();

        return $data;
    }
}
