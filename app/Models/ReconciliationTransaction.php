<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconciliationTransaction extends Model
{
    use HasFactory;
    protected $table = 'reconciliation_transaction';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function reconciliation_transaction($flag_transaction)
    {
        $data = self::where('flag_transaction', $flag_transaction)->get();
    
        return $data;
    }

}
