<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringLeaveStatus extends Model
{
    use HasFactory;
    protected $table = 'monitoring_leave_status';
    public $timestamps = false;

}
