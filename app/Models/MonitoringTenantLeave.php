<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringTenantLeave extends Model
{
    use HasFactory;
    protected $table    = 'monitoring_tenants_leave';
    protected $primaryKey = 'id';
    public $timestamps  = false;


    public function status()
    {
        return $this->belongsTo(MonitoringStatus::class, 'monitoring_status_id');
    }
    public function monitoring_leave_status()
    {
        return $this->belongsTo(MonitoringLeaveStatus::class, 'monitoring_leave_status_id');
    }
    public function monitoring_officer()
    {
        return $this->belongsTo(Officer::class, 'officer_id');
    }

    public function attachments()
    {
        return $this->hasMany(MonitoringTenantLeaveAttachment::class, 'monitoring_tenants_leave_id');
    }
}
