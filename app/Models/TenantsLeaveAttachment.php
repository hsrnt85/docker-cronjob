<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsLeaveAttachment extends Model
{
    use HasFactory;
    protected $table    = 'tenants_leave_attachment';
    protected $primaryKey = 'id';
    public $timestamps  = false;
}
