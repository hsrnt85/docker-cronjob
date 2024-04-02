<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceUtility extends Model
{
    use HasFactory;

    protected $table    = 'maintenance_utility';
    public $timestamps  = false;
}
