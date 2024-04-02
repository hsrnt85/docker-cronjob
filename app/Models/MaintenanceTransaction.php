<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTransaction extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction';

    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id');
    }

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'monitoring_officer_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function attachment()
    {
        return $this->hasOne(MaintenanceTransactionAttachment::class, 'maintenance_transaction_id');
    }

}
