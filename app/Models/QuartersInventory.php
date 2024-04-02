<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersInventory extends Model
{
    use HasFactory;

    protected $table    = 'quarters_inventory';
    public $timestamps  = false;

    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'q_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'i_id');
    }

    public function maintenance()
    {
        return $this->belongsTo(MaintenanceInventory::class, 'm_inventory_id');
    }
}
