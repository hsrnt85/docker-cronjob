<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceAttachment extends Model
{
    use HasFactory;
    protected $table = 'maintenance_attachment';

    public $timestamps = false;
}
