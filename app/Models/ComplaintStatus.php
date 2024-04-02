<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintStatus extends Model
{
    use HasFactory;

    protected $table = 'complaint_status';
    protected $primaryKey = 'id';

    public $timestamps = false;

    //Report aduan kerosakan & awam
    public static function getComplaintStatus()
    {
        $data = self::select('id', 'complaint_status')->where(['status_data'=> 1,'flag_report'=>1])->get();

        return $data;
    }

}
