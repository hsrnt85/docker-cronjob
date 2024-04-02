<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringScheme extends Model
{
    use HasFactory;

    protected $table = 'scoring_scheme';
    protected $dates= ['execution_date'];
    protected $fillable = ['data_status'];
    public $timestamps = false;

    public function scoringSubCriteria()
    {
        return $this->belongsTo(ScoringCriteria::class, 'scoring_scheme_id')->where('data_status', 1);
    }

}
