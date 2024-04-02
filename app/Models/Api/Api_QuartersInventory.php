<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_QuartersInventory extends Model
{
    use HasFactory;
    
    protected $table    = 'quarters_inventory';
    public $timestamps  = false;

    public function quarters()
    {
        return $this->belongsTo(Api_Quarters::class, 'q_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Api_Inventory::class, 'i_id');
    }

    public function maintenance()
    {
        return $this->belongsTo(Api_aintenanceInventory::class, 'm_inventory_id');
    }

    public static function getInventoryByQuarters($quartersId)
    {
        $data = self::with('inventory')
        ->where('q_id', $quartersId)
        ->where('data_status', 1)
        ->get();

        return $data;
    }
}
