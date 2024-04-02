<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaCategory extends Model
{
    use HasFactory;

    protected $table = 'criteria_category';
    public $timestamps = false;

    public function criteriaAll()
    {
        return $this->has(SelectionCriteria::class, 'c_category_id')
        ->where('data_status', 1);
    }
}
