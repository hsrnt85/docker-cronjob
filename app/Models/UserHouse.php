<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHouse extends Model
{
    use HasFactory;
    protected $table    = 'users_address_house';
    public $timestamps  = false;

    protected $fillable = [
        'users_id',
        'address_type',
        'address_1',
        'address_2',
        'address_3',
        'latitude',
        'longitude',
        'distance',
        'postcode',
        'action_on',
    ];
}
