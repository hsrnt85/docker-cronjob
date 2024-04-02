<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictManagement extends Model
{
    use HasFactory;

    protected $table = 'district_management';
    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
