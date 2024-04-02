<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsCancelRemarks extends Model
{
    use HasFactory;
    protected $table    = 'tenants_cancel_remarks';
    public $timestamps  = false;
}
