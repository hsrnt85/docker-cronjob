<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_MaintenanceTransaction extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction';
    public $timestamps  = false;

    public function officer()
    {
        return $this->belongsTo(Api_Officer::class, 'monitoring_officer_id');
    }

}
