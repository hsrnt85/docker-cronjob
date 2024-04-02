<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersClass extends Model
{
    use HasFactory;

    protected $table = 'quarters_class';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function grade()
    {
        return $this->belongsToMany(PositionGrade::class, 'quarters_class_grade', 'q_class_id', 'p_grade_id')->withPivot('id', 'data_status', 'action_by', 'action_on', 'delete_by', 'delete_on');
    }

    public function class_grade()
    {
        return $this->hasMany(QuartersClassGrade::class, 'q_class_id');
    }
}
