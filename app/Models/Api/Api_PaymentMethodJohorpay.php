<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_PaymentMethodJohorpay extends Model
{
    use HasFactory;

    protected $table = 'payment_method_johorpay';
    protected $primaryKey = 'id';
    
    public $timestamps = false;

}
