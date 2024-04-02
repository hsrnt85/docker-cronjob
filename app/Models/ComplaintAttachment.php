<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    use HasFactory;

    protected $table    = 'complaint_attachment';
    protected $primaryKey = 'id';
    protected $fillable = ['path_document'];

    public $timestamps  = false;

    //COMPLAINT MONITORING
    public static function getComplaintAttachmentAll($complaint_id) // ADUAN AWAM
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->get();

        return $data;
    }

    //MAINTENANCE TRANSACTION
    public static function getOthersAttachment($complaint_id , $id)
    {
        $data = self::where(['data_status' => 1 , 'complaint_id' => $complaint_id , 'complaint_others_id' => $id])->get();

        return $data;
    }


}
