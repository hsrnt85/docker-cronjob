<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends Model
{
    use HasFactory;
    protected $table    = 'users_policy';
    public $timestamps  = false;

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'roles_id');
    }

}
