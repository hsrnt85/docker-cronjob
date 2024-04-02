<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesType extends Model
{
    use HasFactory;

    protected $table = 'services_type';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['code', 'data_status', 'action_on'];

}
