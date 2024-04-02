<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_PaymentNoticeSchedule extends Model
{
    use HasFactory;
    protected $table = 'payment_notice_schedule';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
