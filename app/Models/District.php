<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';
    public $timestamps = false;

    public function postcodes()
    {
        return $this->hasMany(Postcode::class, 'district_id');
    }
}
