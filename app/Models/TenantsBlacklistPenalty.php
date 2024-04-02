<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsBlacklistPenalty extends Model
{
    use HasFactory;
    protected $table    = 'tenants_blacklist_penalty';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    protected $dates = [
        'penalty_date',
    ];

    public function tenants()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id');
    }

}
