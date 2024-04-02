<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintMonitoringAttachment extends Model
{
    use HasFactory;
    protected $table = 'complaint_monitoring_attachment';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function getMonitoringAttachmentbyMonitoringId($monitoring_id, $returnType = 'get')
    {
        $data = self::select('complaint_monitoring_attachment.*')
                ->leftJoin('complaint_monitoring', 'complaint_monitoring.id', '=', 'complaint_monitoring_attachment.complaint_monitoring_id')
                ->where('complaint_monitoring.data_status', 1)
                ->where('complaint_monitoring_attachment.data_status', 1)
                ->where('complaint_monitoring_attachment.complaint_monitoring_id', $monitoring_id)
                ->$returnType();

        return $data;
    }
}
