<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    use HasFactory;
    protected $table    = 'transaction_status';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    public static function checking_list()
    {
        $data = self::select('id', 'status')->where(['data_status'=> 1, 'flag_checking' => 1])->get();

        return $data;
    }

    public static function approval_list()
    {
        $data = self::select('id', 'status')->where(['data_status'=> 1, 'flag_approval' => 1])->get();

        return $data;
    }
}
