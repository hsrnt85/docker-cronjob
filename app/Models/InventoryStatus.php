<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStatus extends Model
{
    use HasFactory;
    protected $table = 'inventory_status';
    protected $primaryKey = 'id';

    public $timestamps = false;

}
