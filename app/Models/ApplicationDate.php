<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDate extends Model
{
    use HasFactory;
    protected $table = 'application_date';
    protected $dates= ['date_open','date_close'];
    public $timestamps = false;
}
