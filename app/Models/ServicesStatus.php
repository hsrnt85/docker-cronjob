<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesStatus extends Model
{
    use HasFactory;

    protected $table = 'services_status';
    protected $primaryKey = 'id';

    public $timestamps = false;

}
