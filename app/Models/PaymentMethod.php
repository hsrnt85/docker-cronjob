<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_method';
    protected $primaryKey = 'id';
    protected $fillable = [
        'payment_method_code',
        'payment_method',
        'ispeks_payment_code_id',
        'payment_category_id',
        'data_status',
    ];
    

    public $timestamps = false;

    public function ispeksPaymentCode()
    {
        return $this->belongsTo(IspeksPaymentCode::class, 'ispeks_payment_code_id');
    }

    public function paymentCategoryCode()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'payment_method_id');
    }

    public static function getPaymentMethod($payment_category_id=0){

        $data = self::select('payment_method', 'id')->where('data_status' , 1);
        if($payment_category_id) $data = $data->where('payment_category_id' , $payment_category_id);
        $data = $data->get();
        return $data;
        
    }

    public static function getIspeksPaymentMethod(){

        $data = self::from('payment_method as pm')
            ->join('payment_category as pc', 'pc.id', '=', 'pm.payment_category_id')
            ->select('pm.payment_method', 'pm.id')
            ->where(['pm.data_status' =>  1, 'pc.data_status' =>  1, 'pc.flag_ispeks'=> 1])
            ->get();
        return $data;
        
    }

}
