<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintAppointmentAttachment extends Model
{
    use HasFactory;
    protected $table = 'complaint_appointment_attachment';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function getAppointmentAttachmentbyAppointmentId($appointmentId, $returnType = 'get')
    {
        $data = self::from('complaint_appointment_attachment as attachment')->select('attachment.id', 'attachment.complaint_appointment_id', 'attachment.path_document')
                ->leftJoin('complaint_appointment', 'complaint_appointment.id', '=', 'attachment.complaint_appointment_id')
                ->where('complaint_appointment.data_status', 1)
                ->where('attachment.data_status', 1)
                ->where('attachment.complaint_appointment_id', $appointmentId)
                ->$returnType();

        return $data;
    }
}
