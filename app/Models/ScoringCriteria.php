<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringCriteria extends Model
{
    use HasFactory;

    protected $table = 'scoring_criteria';
    public $timestamps = false;

    public function scoringMappingHrmis()
    {
        return $this->belongsTo(ScoringMappingHrmis::class, 'scoring_mapping_hrmis_id')->where('data_status', 1);
    }

    public function scoringSubCriteria()
    {
        return $this->belongsTo(ScoringSubCriteria::class, 'scoring_criteria_id')->where('data_status', 1);
    }

}
