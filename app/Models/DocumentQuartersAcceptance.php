<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentQuartersAcceptance extends Model
{
    use HasFactory;

    protected $table = 'documents_quarters_acceptance';
    public $timestamps = false;
}
