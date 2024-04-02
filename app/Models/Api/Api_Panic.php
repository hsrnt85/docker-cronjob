<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Panic extends Model
{
    use HasFactory;

    protected $table        = 'panic';
    protected $primaryKey   = 'id';

    protected $fillable = ['status', 'user_id'];

    public $timestamps = false;
}
