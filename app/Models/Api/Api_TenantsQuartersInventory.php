<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_TenantsQuartersInventory extends Model
{
    use HasFactory;
    protected $table    = 'tenants_quarters_inventory';
    public $timestamps  = false;
}
