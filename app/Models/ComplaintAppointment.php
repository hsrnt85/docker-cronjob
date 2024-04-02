<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAppointment extends Model
{
    use HasFactory;

    protected $table = 'complaint_appointment';
    protected $primaryKey = 'id';
    protected $dates = ['appointment_date', 'appointment_time'];

    public $timestamps = false;

    public function status_appointment()
    {
        return $this->belongsTo(AppointmentStatus::class, 'appointment_status_id');
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id')->orderBy('complaint_date');
    }

    public function type()
    {
        return $this->has(Complaint::class, 'complaint_id');
    }

    public function delete_name()
    {
        return $this->belongsTo(User::class, 'delete_by');
    }
    public function status()
    {
        return $this->belongsTo(ComplaintStatus::class,'complaint_status_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'quarters_id');
    }

    public function tenants_remarks()
    {
        return $this->belongsTo(TenantsCancelRemarks::class, 'tenants_cancel_remarks_id');
    }

    public function appointment_attachment()
    {
        return $this->hasMany(ComplaintAppointmentAttachment::class, 'complaint_appointment_id')->where('data_status', 1);
    }

    //TEMUJANJI ADUAN -----------------------------------------------------------------------

    public static function getAppointmentApprovalList($district_id)
    {
        $data = self::join('complaint', 'complaint.id', '=', 'complaint_appointment.complaint_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->where('complaint_appointment.data_status', 1) ->whereNull('complaint_appointment.appointment_status_id')
        ->whereRaw("complaint_id NOT IN (SELECT complaint_id FROM complaint_appointment b where b.appointment_status_id =1 and b.data_status=1)")
        ->whereRaw("complaint_id IN (SELECT id FROM complaint WHERE data_status =1 and complaint_type =1)")
        ->orderBy('complaint_appointment.id','desc')
        ->groupby('complaint_appointment.complaint_id');

        //FILTER BY DISTRICT ID
        if($district_id)
        {
            $data = $data->where('quarters_category.district_id', $district_id);
        }

        $list = $data->get();

        return $list;
    }

    public static function getAppointmentHistoryList($district_id)
    {
        $data = self::join('complaint', 'complaint.id', '=', 'complaint_appointment.complaint_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->where(['complaint_appointment.data_status'=> 1 , 'complaint_appointment.appointment_status_id'=> 1])
        ->whereRaw("complaint_id IN (SELECT id FROM complaint WHERE data_status =1 and complaint_type =1)")
        ->orderBy('complaint_appointment.appointment_date','desc')
        ->groupBy('complaint_appointment.complaint_id');

        //FILTER BY DISTRICT ID
        if($district_id)
        {
            $data = $data->where('quarters_category.district_id', $district_id);
        }

        $list = $data->get();

        return $list;
    }

    public static function getCancelAppointmentList($district_id)
    {
        $data = self::select('complaint_appointment.*', 'complaint.complaint_type', 'complaint.id', 'complaint.complaint_status_id')
        ->join('complaint', 'complaint.id', '=', 'complaint_appointment.complaint_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->where(['complaint_appointment.data_status'=> 2, 'complaint.complaint_type' => 1, 'complaint.data_status' => 2])
        ->orderBy('complaint_appointment.appointment_date','desc');

        //FILTER BY DISTRICT ID
        if($district_id)
        {
            $data = $data->where('quarters_category.district_id', $district_id);
        }

        $list = $data->get();

        return $list;
    }

}
