<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringStatus extends Model
{
    use HasFactory;
    protected $table = 'monitoring_status';
    public $timestamps = false;

}
