<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildAttachment extends Model
{
    use HasFactory;

    protected $table = 'users_child_attachment';
    protected $dates= ['action_on'];
    public $timestamps = false;
}
