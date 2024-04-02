<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_IspeksPaymentCode extends Model
{
    use HasFactory;

    protected $table = 'ispeks_payment_code';
    protected $primaryKey = 'id';
    public $timestamps = false;

}
