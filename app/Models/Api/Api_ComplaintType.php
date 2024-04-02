<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintType extends Model
{
    use HasFactory;

    protected $table    = 'complaint_type';
    public $timestamps  = false;

}
