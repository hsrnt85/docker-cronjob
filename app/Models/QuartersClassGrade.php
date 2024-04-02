<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersClassGrade extends Model
{
    use HasFactory;

    protected $table = 'quarters_class_grade';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function quartersClass()
    {
        return $this->belongsTo(QuartersClass::class, 'q_class_id');
    }

    public function positionGrade()
    {
        return $this->belongsTo(PositionGrade::class, 'p_grade_id');
    }

    public function servicesType()
    {
        return $this->belongsTo(ServicesType::class, 'services_type_id');
    }

    public function officerType()
    {
        return $this->belongsTo(OfficerType::class, 'officer_type_id');
    }

    public static function getRental($classIdArr, $positionGradeId)
    {
        $data = self::where('data_status', 1)
                    ->whereIn('q_class_id', $classIdArr)
                    ->where('p_grade_id', $positionGradeId)
                    ->first()?->rental_fee;
    
        return $data;
    }

}
