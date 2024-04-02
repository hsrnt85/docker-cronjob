<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_ComplaintMonitoring extends Model
{
    use HasFactory;
    protected $table = 'complaint_monitoring';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $tableComplaint; // Declare the variable
    protected $tableComplaintMonitoring; // Declare the variable

    public function __construct(){

        parent::__construct(); // Make sure to call the parent constructor

        $this->tableComplaint =  'complaint';
        $this->tableComplaintMonitoring =  'complaint_monitoring';
    }

    public function complaint()
    {
        return $this->belongsTo(Api_Complaint::class, 'complaint_id')->orderBy('complaint_date');
    }

    public function scopeRange($query, $start, $end)
    {
         return $query->whereDate( $this->tableComplaint .'.complaint_date', '>=', $start)
            ->whereDate( $this->tableComplaint .'.complaint_date', '<=', $end);
    }

    public function scopeActive($query)
    {
        return $query->where( $this->tableComplaint .'.data_status', 1)
                     ->where( $this->tableComplaintMonitoring .'.data_status', 1);
    }

    public function scopeCompletedMonitoring($query)
    {
        return $query->whereIn('monitoring_status_id', [2,3])
                ->whereHas('complaint', function($subQ){
                    $subQ->complaintCompleted()
                    ->complaintViolation()
                    ->active();
                });
    }

    public function scopePendingMonitoring($query)
    {
        return $query
                ->whereHas('complaint', function($subQ){
                    $subQ->complaintAccepted()
                    ->complaintViolation()
                    ->active();
                });
    }

    public function scopeActiveMonitoring($query)
    {
        return $query->where('monitoring_status_id', 1) //pemantauan semula
                ->whereHas('complaint', function($subQ){
                    $subQ->complaintViolationActive()
                    ->complaintViolation()
                    ->active();
                });
    }



    public function scopeByOfficer($query, $user_id)
    {
        return $query->where($this->tableComplaintMonitoring.'.action_by', $user_id);
    }

    public static function getMonitoringById($returnType = 'get') // Apps Penghuni!
    {
        $data = self::select('complaint_monitoring.*')
                ->join('complaint', 'complaint.id', '=', 'complaint_monitoring.complaint_id')
                ->with([
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'complaint_description', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->completedMonitoring()
                ->orderBy('complaint.id', 'desc') // ADD
                ->$returnType();

        return $data;
    }


    public static function getCompletedMonitoring($user_id, $start, $end, $returnType = 'get') // Pemantauan Selesai !
    {
        $data = self::select('complaint_monitoring.*')
                ->join('complaint', 'complaint.id', '=', 'complaint_monitoring.complaint_id')
                ->with([
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'complaint_description', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->completedMonitoring()
                ->byOfficer($user_id)
                ->range($start, $end)
                ->orderBy('complaint.id', 'desc') // ADD
                ->$returnType();

        return $data;
    }

    public static function getPemantauanBerulang($user_id, $start, $end, $returnType)
    {
        $data = self::select('complaint_monitoring.*')
                ->join('complaint', 'complaint.id', '=','complaint_monitoring.complaint_id')
                ->with([
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'complaint_description', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    // 'complaint.quarters.category:id,district_id,name,data_status'
                ])
                ->active()
                ->where('complaint.complaint_type', 2)
                ->where('complaint.complaint_status_id', 4)
                ->range($start, $end)
                ->orderBy('complaint.id', 'desc') // ADD
                ->byOfficer($user_id)
                ->groupBy('complaint.id')->$returnType();

        return $data;
    }


//     public static function getAduanKerosakanDitolak($district_id, $start, $end, $returnType) dah ada
//     {
//     $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.quarters_id', 'complaint.complaint_type', 'complaint.complaint_status_id', 'complaint.complaint_description', 'complaint.remarks', 'complaint.officer_id', 'complaint.cancel_reason', 'complaint.data_status', 'complaint_appointment.appointment_date as formatted_appointment_date', DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'))
//     ->with([
//         'user:id,name,new_ic,email,phone_no_hp',
//         'officer:id,name,new_ic,email,phone_no_hp',
//         'quarters.category:id,district_id,name'
//     ])

//     ->leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
//     ->whereHas('current_complaint_appointment',function($query) use ($district_id){
//         //FILTER BY OFFICER DISTRICT ID
//          //    if($district_id)
//          //    {
//          //            $query = $query->where('quarters_category.district_id', $district_id);
//          //    }

//         $query
//                ->where('complaint.complaint_status_id', 2)
//                 ->where('complaint.data_status', 1)
//                 ->where('complaint_appointment.data_status', 1)
//                 ->where('complaint_appointment.appointment_status_id', 1) //setuju
//                 ->orderBy('complaint_appointment.id', 'desc')
//                 ->groupBy('complaint_appointment.complaint_id');
//     })
//     ->where('complaint.complaint_type', 1) //kerosakan
//     ->range($start, $end)
//     ->groupBy('complaint.id');
//     // ->where('complaint.data_status', 1);

//       $data = $data->$returnType();

//       return $data;
//  }


    public static function getCompletedMonitoringById($monitoringId, $user_id)
    {
        $data = self::select('complaint_monitoring.*')
                ->join('complaint', 'complaint.id', '=', 'complaint_monitoring.complaint_id')
                ->with([
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'complaint_description', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->completedMonitoring()
                ->byOfficer($user_id)
                ->where('complaint_monitoring.id', $monitoringId)
                ->first();

        return $data;
    }

    public static function getActiveMonitoringById($monitoringId, $user_id, $date = null)
    {
        $date = ($date) ? date_format(date_create($date), "Y-m-d") : date("Y-m-d");

        $data = self::select('complaint_monitoring.*')
                // ->with('complaint.user:id,name,new_ic,email,phone_no_hp')
                // ->with('complaint.officer:id,name,phone_no_hp')
                // ->with('complaint.quarters.category')
                ->join('complaint', 'complaint.id', '=', 'complaint_monitoring.complaint_id')
                ->with([
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_description', 'complaint_status_id', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                // ->activeMonitoring()
                // ->byOfficer($user_id)
                ->where('complaint_monitoring.id', $monitoringId)
                ->first();

        return $data;
    }

}
