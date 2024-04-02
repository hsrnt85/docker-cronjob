<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintStatus extends Model
{
    use HasFactory;

    protected $table    = 'complaint_status';
    public $timestamps  = false;

}
