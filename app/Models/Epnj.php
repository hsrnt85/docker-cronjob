<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epnj extends Model
{
    use HasFactory;
    protected $table = 'epnj';
    protected $dates = ['action_on'];
    public $timestamps = false;

    protected $fillable = ['ic', 'is_epnj', 'ownership_no', 'data_status', 'state', 'district_id', 'mukim', 'lot_type', 'lot_no', 'house_type', 'loan_type', 'action_on'];

}