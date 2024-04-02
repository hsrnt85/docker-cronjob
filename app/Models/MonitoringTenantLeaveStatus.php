<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringTenantLeaveStatus extends Model
{
    use HasFactory;

    protected $table    = 'monitoring_leave_status';
    protected $primaryKey = 'id';
    public $timestamps  = false;
}
