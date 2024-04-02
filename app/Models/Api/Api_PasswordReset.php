<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Api_PasswordReset extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';
    protected $dates= ['action_on'];
    protected $fillable = ['data_status'];
    public $timestamps = false;

    public static function token(){

        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = substr(str_shuffle($permitted_chars), 0, 20);

        return $token;
    }
}
