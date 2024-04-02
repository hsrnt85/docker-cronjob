<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_account';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bank_id',
        'account_no',
        'account_name',
        'payment_method_id',
        'payment_category_id',
        'bank_account_type',
        'data_status',
    ];
    
    
    public $timestamps = false;

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

   //  MAKLUMAT BANK AJAX PP
   public static function get_bank_account($account_type=null, $payment_method_id=null, $return_type='get'){ //2:bank transit
      
        $data = self::select('bank_account.id','bank_account.bank_id', 'bank_account.payment_method_id', 'bank.bank_name', 'bank_account.account_no')
        ->join('bank', 'bank.id', '=', 'bank_account.bank_id')
        ->where( ['bank_account.data_status'=> 1, 'bank.data_status' => 1]);

        if($payment_method_id) $data = $data->where('bank_account.payment_method_id', $payment_method_id);
        if($account_type) $data = $data->where('bank_account.bank_account_type', $account_type);

        $data = $data->$return_type();

        return $data;
    }      

    public function bankAccountType()
    {
        return $this->belongsTo(BankAccountType::class, 'bank_account_type');
    }

}
