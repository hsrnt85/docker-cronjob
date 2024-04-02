<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_TenantsOptionAttachment extends Model
{
    use HasFactory;
    protected $table    = 'tenants_options_attachment';
    public $timestamps  = false;
}
