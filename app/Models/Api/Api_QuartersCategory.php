<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_QuartersCategory extends Model
{
    use HasFactory;

    protected $table        = 'quarters_category';
    protected $primaryKey   = 'id';

    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(Api_District::class, 'district_id');
    }
}
