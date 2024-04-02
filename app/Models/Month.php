<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;
    protected $table = 'month';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function get_month($from_month=0, $to_month=0)
    {
        $data = self::select('id','name')->where('data_status', 1);
        if($from_month>0) $data = $data->where('id','>=',$from_month);
        if($to_month>0) $data = $data->where('id','<=',$to_month);
        $data = $data->get();
        
        return $data;
    }
}
