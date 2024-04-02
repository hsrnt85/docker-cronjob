<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesAbilities extends Model
{
    use HasFactory;

    protected $table = 'roles_abilities';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
