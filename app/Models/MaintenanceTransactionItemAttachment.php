<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTransactionItemAttachment extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction_item_attachment';

    public $timestamps = false;
}
