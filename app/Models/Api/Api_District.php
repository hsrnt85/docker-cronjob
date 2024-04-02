<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_District extends Model
{
    use HasFactory;
    protected $table        = 'district';
    protected $primaryKey   = 'id';

    public $timestamps = false;

}
