<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceStatus extends Model
{
    use HasFactory;
    protected $table    = 'maintenance_status';
    public $timestamps  = false;
}
