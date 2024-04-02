<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersCategoryInventory extends Model
{
    use HasFactory;

    protected $table = 'quarters_category_inventory';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
