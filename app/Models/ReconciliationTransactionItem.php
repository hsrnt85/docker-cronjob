<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconciliationTransactionItem extends Model
{
    use HasFactory;
    protected $table = 'reconciliation_transaction_item';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function reconciliation_transaction_item($id)
    {
        $data = self::whereNot('data_status', 0)->where('id', $id)->get();
    
        return $data;
    }

}
