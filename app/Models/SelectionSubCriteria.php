<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionSubCriteria extends Model
{
    use HasFactory;

    protected $table = 'selection_sub_criteria';
    public $timestamps = false;

    public function criteria()
    {
        return $this->belongsTo(SelectionCriteria::class, 's_criteria_id');
    }

}
