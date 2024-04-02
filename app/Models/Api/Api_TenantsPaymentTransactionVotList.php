<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_TenantsPaymentTransactionVotList extends Model
{
    use HasFactory;
    protected $table    = 'tenants_payment_transaction_vot_list';
    protected $primaryKey = 'id';
    public $timestamps  = false;
}
