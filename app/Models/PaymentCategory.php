<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;
    protected $table = 'payment_category';
    public $timestamps = false;

    public function ispeks_category()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(bankAccount::class, 'payment_category');
    }

    public static function getPaymentCategory()
    {
        $data = self::select('payment_category', 'id')->where('data_status' , 1)->get();
        return $data;
    }
}
