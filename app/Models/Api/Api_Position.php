<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Position extends Model
{
    use HasFactory;

    protected $table = 'position';
    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = ['position_name', 'data_status', 'action_on'];

}
