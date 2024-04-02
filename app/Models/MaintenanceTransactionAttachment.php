<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTransactionAttachment extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction_attachment';

    public $timestamps = false;
}
