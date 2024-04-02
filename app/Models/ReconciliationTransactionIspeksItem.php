<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconciliationTransactionIspeksItem extends Model
{
    use HasFactory;
    protected $table = 'reconciliation_transaction_ispeks_item';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function reconciliation_transaction_item_by_id($id)
    {
        $data = self::whereNot('data_status', 0)->where('reconciliation_transaction_ispeks_id', $id)->get();
    
        return $data;
    }

}
