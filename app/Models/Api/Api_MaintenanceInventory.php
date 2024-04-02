<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_MaintenanceInventory extends Model
{
    use HasFactory;

    protected $table    = 'maintenance_inventory';
    public $timestamps  = false;
}
