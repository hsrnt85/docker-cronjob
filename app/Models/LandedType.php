<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandedType extends Model
{
    use HasFactory;

    protected $table    = 'landed_type';
    public $timestamps  = false;
}
