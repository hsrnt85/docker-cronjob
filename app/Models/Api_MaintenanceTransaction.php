<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_MaintenanceTransaction extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction';
    public $timestamps = false;
}
