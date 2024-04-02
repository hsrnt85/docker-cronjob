<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationScore extends Model
{
    use HasFactory;
    protected $table = 'application_score';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(CriteriaCategory::class, 'c_category_id');
    }

    public function criteria()
    {
        return $this->belongsTo(SelectionCriteria::class, 's_criteria_id');
    }

    public function subCriteria()
    {
        return $this->belongsTo(SelectionSubCriteria::class, 's_sub_criteria_id');
    }
}
