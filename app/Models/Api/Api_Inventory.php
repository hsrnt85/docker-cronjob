<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Inventory extends Model
{
    use HasFactory;

    protected $table        = 'inventory';
    protected $primaryKey   = 'id';

    public $timestamps = false;
}
