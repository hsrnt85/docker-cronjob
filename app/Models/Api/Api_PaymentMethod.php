<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_method';
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    public function ispeksPaymentCode()
    {
        return $this->belongsTo(Api_IspeksPaymentCode::class, 'ispeks_payment_code_id');
    }

    public static function getPaymentMethod($payment_category_id=0){

        $data = self::join('ispeks_payment_code as ipc', 'ipc.id', '=', 'payment_method.ispeks_payment_code_id')
            ->select('ipc.description as payment_method', 'ipc.ispeks_payment_code', 'payment_method.id')->where('payment_method.data_status' , 1);
        if($payment_category_id) $data = $data->where('payment_method.payment_category_id' , $payment_category_id);
        $data = $data->get();
        return $data;
        
    }

}
