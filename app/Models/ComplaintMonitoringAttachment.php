<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintMonitoringAttachment extends Model
{

    use HasFactory;
    protected $table = 'complaint_monitoring_attachment';
    public $timestamps = false;

    public static function getComplaintMonitoringAttachmentAll($monitoring_id, $monitoring_counter) // ADUAN AWAM // PEMANTAUAN  BERULANG
    {
        $data = self::where('data_status', 1)->where('complaint_monitoring_id', $monitoring_id )->where('monitoring_counter', $monitoring_counter)->get();

        return $data;
    }

}
