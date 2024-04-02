<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineInspectionTransactionAttachment extends Model
{
    use HasFactory;

    protected $table    = 'routine_inspection_transaction_attachment';
    public $timestamps  = false;

    public static function getAttachmentAll($transaction_id)
    {
        $data = self::where('routine_inspection_transaction_id', $transaction_id)
                ->where('data_status', 1)
                ->get();
        
        return $data;
    }
}
