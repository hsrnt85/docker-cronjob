<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_RoutineInspectionTransactionAttachment extends Model
{
    use HasFactory;

    protected $table    = 'routine_inspection_transaction_attachment';
    public $timestamps  = false;
}
