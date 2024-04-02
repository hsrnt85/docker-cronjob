<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTransactionItem extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction_item';

    public $timestamps = false;

}
