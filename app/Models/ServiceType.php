<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'services_type';
    protected $primaryKey = 'id';
    protected $fillable = ['services_type'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'services_type_id', 'id');
    }

    public static function get_services_type()
    {
       $data = self::select('id', 'services_type', 'code')->where('data_status', 1)->get();
       return $data;
    }
}
