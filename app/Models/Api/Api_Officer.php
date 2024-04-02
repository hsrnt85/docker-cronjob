<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Officer extends Model
{
    use HasFactory;
    protected $table        = 'officer';
    protected $primaryKey   = 'id';

    public $timestamps = false;

    public function user()
    {
        // return $this->belongsTo(App\Models\User::class, 'users_id');
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    public function user_Api()
    {
        // return $this->belongsTo(App\Models\User::class, 'users_id');
        return $this->belongsTo(Api_User::class, 'users_id');
    }

    public static function getPegawaiPengesahByDaerah($district_id)
    {
        $data = self::with('user')
                ->where('data_status', 1)
                ->where('district_id', $district_id)
                ->whereRaw('FIND_IN_SET(?, officer_category_id)', [3])
                ->get();

        return $data;
    }

    public static function getPegawaiPemantauanByUserId($id)
    {
        $data = self::with('user')
                ->where(['data_status'=> 1, 'users_id' => $id])
                ->whereRaw('FIND_IN_SET(?, officer_category_id)', [4])
                ->get();

        return $data;
    }
}
