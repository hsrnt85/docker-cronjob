<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_PositionGradeType extends Model
{
    use HasFactory;

    protected $table = 'position_grade_code';
    public $timestamps = false;

    protected $fillable = ['grade_type', 'data_status', 'action_on'];

}
