<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IspeksPaymentCode extends Model
{
 
    use HasFactory;

    protected $table = 'ispeks_payment_code';
    protected $fillable = ['ispeks_payment_code', 'description'];
    public $timestamps = false;

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, 'ispeks_payment_code_id');
    }
}
