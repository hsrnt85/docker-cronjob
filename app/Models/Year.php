<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;
    protected $table = 'year';
    protected $primaryKey = 'id';
    protected $fillable = ['year'];

    public $timestamps = false;

    public static function get_year()
    {
        $data = self::select('id','year')->where('data_status', 1)->get();
        return $data;
    }

}
