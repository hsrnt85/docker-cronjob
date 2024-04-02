<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;
    protected $table = 'account_type';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function getAccountTypeAll()
    {
        $data = self::select('account_type', 'id')->where('data_status' , 1)->get();
        return $data;
    }

}
