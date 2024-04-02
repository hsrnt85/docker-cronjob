<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantQuartersInventory extends Model
{
    use HasFactory;

    protected $table    = 'tenants_quarters_inventory';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function inventory_status()
    {
        return $this->belongsTo(InventoryStatus::class, 'inventory_status_id_in');
    }

    public function inventory_status_out()
    {
        return $this->belongsTo(InventoryStatus::class, 'inventory_status_id_out');
    }

    public function monitoring_status()
    {
        return $this->belongsTo(InventoryStatus::class, 'monitoring_inventory_status_id');
    }

    public function condition()
    {
        return $this->belongsTo(InventoryCondition::class, 'monitoring_inventory_condition_id');
    }

}

