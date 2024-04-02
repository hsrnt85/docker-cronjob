<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersCategoryClass extends Model
{
    use HasFactory;

    protected $table = 'quarters_cat_class';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function quartersClass()
    {
        return $this->belongsTo(QuartersClass::class, 'q_class_id');
    }
}
