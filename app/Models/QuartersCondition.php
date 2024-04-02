<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersCondition extends Model
{
    use HasFactory;

    protected $table    = 'quarters_condition';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getAllConditions()
    {
        return self::where('data_status', 1)->get();
    }

}
