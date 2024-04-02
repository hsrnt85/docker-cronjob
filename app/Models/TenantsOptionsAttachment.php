<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsOptionsAttachment extends Model
{
    use HasFactory;
    protected $table    = 'tenants_options_attachment';
    protected $primaryKey = 'id';
    public $timestamps  = false;
}
