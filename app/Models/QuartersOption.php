<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersOption extends Model
{
    use HasFactory;

    protected $table    = 'quarters_option_no';
    protected $primaryKey = 'id';
    protected $dates = ['execution_date'];
    public $timestamps = false;

}
