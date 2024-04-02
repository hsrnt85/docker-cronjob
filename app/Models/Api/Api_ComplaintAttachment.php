<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintAttachment extends Model
{
    use HasFactory;

    protected $table    = 'complaint_attachment';
    public $timestamps  = false;

    public static function getOthersAttachmentbyId($complaint_oth_id, $complaint_id, $returnType = 'get')
    {
        $data = self::select('complaint_attachment.id', 'complaint_attachment.complaint_id', 'complaint_attachment.complaint_others_id', 'complaint_attachment.path_document', 'complaint_attachment.data_status')
                ->leftJoin('complaint_others', 'complaint_others.id', '=', 'complaint_attachment.complaint_others_id')
                ->where('complaint_others.data_status', 1)
                ->where('complaint_attachment.data_status', 1)
                ->where('complaint_attachment.complaint_others_id', $complaint_oth_id)
                ->where('complaint_attachment.complaint_id', $complaint_id)
                ->$returnType();

        return $data;
    }

    public static function getViolationAttachmentbyComplaintId($complaint_id, $returnType = 'get')
    {
        $data = self::select('complaint_attachment.id', 'complaint_attachment.complaint_id', 'complaint_attachment.complaint_others_id', 'complaint_attachment.path_document', 'complaint_attachment.data_status')
                ->leftJoin('complaint', 'complaint.id', '=', 'complaint_attachment.complaint_id')
                ->where('complaint.data_status', 1)
                ->where('complaint_attachment.data_status', 1)
                ->where('complaint_attachment.complaint_id', $complaint_id)
                ->$returnType();

        return $data;
    }
}
