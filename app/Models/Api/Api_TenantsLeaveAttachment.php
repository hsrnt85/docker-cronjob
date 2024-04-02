<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_TenantsLeaveAttachment extends Model
{
    use HasFactory;
    protected $table    = 'tenants_leave_attachment';
    public $timestamps  = false;
}
