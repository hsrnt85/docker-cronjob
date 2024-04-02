<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersImage extends Model
{
    use HasFactory;
    protected $table = 'quarters_images';
    protected $primaryKey = 'id';
    protected $fillable = ['path_image'];

    public $timestamps  = false;
}