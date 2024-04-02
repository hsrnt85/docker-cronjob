<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryResponsibility extends Model
{
    use HasFactory;

    protected $table = 'inventory_responsibility';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
