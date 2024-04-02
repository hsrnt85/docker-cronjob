<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    public $timestamps = false;
    protected $fillable = ['organization_id', 'department_name', 'data_status'];

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'organization_id');
    }

}
