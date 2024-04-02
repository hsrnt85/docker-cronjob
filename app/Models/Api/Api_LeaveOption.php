<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_LeaveOption extends Model
{
    use HasFactory;
    protected $table        = 'leave_option';
    protected $primaryKey   = 'id';

    public $timestamps = false;
}
