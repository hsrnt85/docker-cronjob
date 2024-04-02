<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsPaymentTransactionVotList extends Model
{
    use HasFactory;
    protected $table    = 'tenants_payment_transaction_vot_list';
    protected $primaryKey = 'id';

    public $timestamps  = false;

    public function tenantPaymentTransaction()
    {
        return $this->belongsTo(TenantsPaymentTransaction::class, 'tenants_payment_transaction_id');
    }

    
}
