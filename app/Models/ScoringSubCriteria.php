<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringSubCriteria extends Model
{
    use HasFactory;

    protected $table = 'scoring_sub_criteria';
    public $timestamps = false;

    public function criteria()
    {
        return $this->belongsTo(ScoringCriteria::class, 'scoring_criteria_id');
    }

}
