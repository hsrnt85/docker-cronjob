<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChild extends Model
{
    use HasFactory;

    protected $table    = 'users_child';
    public $timestamps  = false;

    protected $fillable = ['users_id', 'new_ic', 'child_name', 'birth_cert', 'is_cacat', 'data_status', 'action_on'];

}
