<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionGrade extends Model
{
    use HasFactory;

    protected $table = 'position_grade';
    public $timestamps = false;

    protected $fillable = ['grade_no', 'data_status', 'action_on'];

}
