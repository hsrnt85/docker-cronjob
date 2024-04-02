<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintMonitoring extends Model
{
    use HasFactory;
    protected $table = 'complaint_monitoring';
    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo(MonitoringStatus::class, 'monitoring_status_id');
    }

    public function monitoring_attachment_counter_1()
    {
        return $this->hasMany(ComplaintMonitoringAttachment::class, 'complaint_monitoring_id')->where(['data_status'=> '1','monitoring_counter'=> 1] );
    }

    public function monitoring_attachment_counter_2()
    {
        return $this->hasMany(ComplaintMonitoringAttachment::class, 'complaint_monitoring_id')->where(['data_status'=> '1','monitoring_counter'=> 2] );
    }

    public function monitoring_attachment_counter_3()
    {
        return $this->hasMany(ComplaintMonitoringAttachment::class, 'complaint_monitoring_id')->where(['data_status'=> '1','monitoring_counter'=> 3] );
    }
}
