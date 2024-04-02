<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_TenantsCancelRemarks extends Model
{
    use HasFactory;
    protected $table    = 'tenants_cancel_remarks';
    public $timestamps  = false;
}
