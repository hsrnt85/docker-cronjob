<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSpouse extends Model
{
    use HasFactory;
    protected $table    = 'users_spouse';
    public $timestamps  = false;

    protected $fillable = ['users_id', 'new_ic', 'spouse_name', 'data_status', 'action_on'];
    
}
