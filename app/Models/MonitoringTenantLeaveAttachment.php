<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringTenantLeaveAttachment extends Model
{
    use HasFactory;
    protected $table    = 'monitoring_tenants_leave_attachment';
    protected $primaryKey = 'id';
    public $timestamps  = false;

}
