<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionCriteria extends Model
{
    use HasFactory;

    protected $table = 'selection_criteria';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(CriteriaCategory::class, 'c_category_id');
    }

    public function subCriteriaAll()
    {
        return $this->hasMany(SelectionSubCriteria::class, 's_criteria_id')
        ->where('data_status', 1);
    }
}
