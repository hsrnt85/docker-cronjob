<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintOthers extends Model
{
    use HasFactory;
    protected $table    = 'complaint_others';
    protected $primaryKey = 'id';

    public $timestamps  = false;

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class, 'complaint_others_id');
    }

    public static function getComplaintOthersMaintanance($complaint_id) //selenggara
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->where('flag_action', 1)->get();

        return $data;
    }
    public static function getComplaintOthersRejected($complaint_id) //ditolak
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->whereNull('flag_action')->get();

        return $data;
    }

    public static function getComplaintOthersAll($complaint_id)
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->get();

        return $data;
    }

    //MAINTENANCE TRANSACTION
    public static function getComplaintOthersById($id)
    {
        $data = self::join('complaint as c', 'c.id' , '=', 'complaint_others.complaint_id')->where(['complaint_others.id' => $id, 'complaint_others.data_status' => 1, 'c.data_status' => 1])->first();

        return $data;
    }

    public function maintenance_status()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id');
    }

    public function monitoring_officer()
    {
        return $this->belongsTo(Officer::class, 'monitoring_officer_id');
    }

    public function current_maintenance_transaction()
    {
        return $this->hasOne(MaintenanceTransaction::class, 'complaint_others_id')->latestOfMany();
    }

}
