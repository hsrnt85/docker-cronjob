<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicReporting extends Model
{
    use HasFactory;

    protected $table = 'report_config_dynamic';
    public $timestamps = false;
}