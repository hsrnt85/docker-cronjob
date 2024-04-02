<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Organization extends Model
{
    use HasFactory;
    protected $table        = 'organization';
    protected $primaryKey   = 'id';

    public static function get_organization($code=0){

        $data = Self::select('id','code','name')->where('data_status','1');
        if($code>0) $data = $data->where('code', $code);
        $data = $data->first();

        return $data;
    }
}
