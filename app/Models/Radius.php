<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radius extends Model
{
    use HasFactory;

    protected $table = 'radius';
    protected $primaryKey = 'id';
    protected $dates = ['date_start'];
    public $timestamps = false;

}
