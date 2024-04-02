<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficerGroup extends Model
{
    use HasFactory;
    protected $table = 'officer_group';
    public $timestamps = false;
}
