<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceInventory extends Model
{
    use HasFactory;

    protected $table    = 'maintenance_inventory';
    public $timestamps  = false;
}
