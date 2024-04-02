<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCondition extends Model
{
    use HasFactory;

    protected $table    = 'inventory_condition';
    protected $primaryKey = 'id';
    public $timestamps  = false;
}
