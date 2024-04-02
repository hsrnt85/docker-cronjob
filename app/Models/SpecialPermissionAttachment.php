<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialPermissionAttachment extends Model
{
    use HasFactory;

    protected $table    = 'special_permission_attachment';
    protected $primaryKey = 'id';
    protected $fillable = ['path_document'];

    public $timestamps  = false;
    
}