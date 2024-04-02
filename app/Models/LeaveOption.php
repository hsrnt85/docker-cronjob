<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveOption extends Model
{
    use HasFactory;

    protected $table    = 'leave_option';
    public $timestamps  = false;
}
