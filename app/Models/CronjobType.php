<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronjobType extends Model
{
    use HasFactory;
    protected $table = 'cronjob_type';
    protected $primaryKey = 'id';

    public $timestamps = false;

}
