<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaint';
    protected $primaryKey = 'id';
    protected $dates= ['complaint_date', 'action_on'];
    protected $fillable = ['row'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(Api_User::class, 'users_id');
    }

    public function officer()
    {
        return $this->belongsTo(Api_User::class, 'officer_id');
    }

    public function complainant()
    {
        return $this->belongsTo(Api_User::class, 'action_by');
    }
    public function quarters()
    {
        return $this->belongsTo(Api_Quarters::class, 'quarters_id')->select('id', 'quarters_cat_id', 'unit_no', 'address_1', 'address_2', 'address_3', 'data_status');
    }

    public function complaint_category()
    {
        return $this->belongsTo(Api_ComplaintType::class, 'complaint_type');
    }

    public function status()
    {
        return $this->belongsTo(Api_ComplaintStatus::class, 'complaint_status_id')->select('id' , 'complaint_status', 'remarks');
    }

    public function complaint_inventory()
    {
        return $this->belongsTo(Api_ComplaintInventory::class);
    }

    public function complaint_appointment()
    {
        return $this->belongsTo(Api_ComplaintAppointment::class, 'complaint_id');
    }

    public function maintenance_status()
    {
        return $this->belongsTo(Api_MaintenanceStatus::class, 'maintenance_status_id');
    }

    public static function convert_date_db($date){
        if(!empty($date)){

            $date =  date('Y-m-d', strtotime($date));
        }
        return $date;
    }

    public function scopeRange($query, $start, $end)
    {
        $query->whereDate('complaint.complaint_date', '>=', $start)
            ->whereDate('complaint.complaint_date', '<=', $end);
    }

    public function scopeActive($query)
    {
        return $query->where('complaint.data_status', 1);
    }

    public function scopeComplaintNew($query)
    {
        return $query->where('complaint_status_id', 0);
    }

    public function scopeComplaintAccepted($query)
    {
        return $query->where('complaint_status_id', 1);
    }

    public function scopeComplaintCompleted($query)
    {
        return $query->where('complaint.complaint_status_id', 3); //changed
    }

    public function scopeComplaintRejected($query)
    {
        return $query->where('complaint_status_id', 2); //changed
    }

    public function scopeComplaintDamageActive($query)
    {
        return $query->whereIn('complaint_status_id', [0,1,5])   //baru,diterima,selenggara
                    ->where('complaint_type', 1);
    }

    public function scopeComplaintViolationActive($query)
    {
        return $query->whereIn('complaint_status_id', [0,1,4])  //baru,diterima,berulang
                    ->where('complaint_type', 2);
    }

    public function scopeComplaintActive($query)
    {
        return $query->whereIn('complaint_status_id', [0,1]);  //baru,diterima!

    }

    public function scopeByUser($query, $user_id)
    {
        return $query->where('action_by', $user_id);
    }

    public function scopeByOfficer($query, $user_id)
    {
        return $query->where('complaint.officer_id', $user_id);
    }

    public function scopeComplaintDamage($query)
    {
        return $query->where('complaint.complaint_type', 1);
    }

    public function scopeComplaintViolation($query)
    {
        return $query->where('complaint.complaint_type', 2);
    }


    public function current_complaint_appointment()
    {
        return $this->hasOne(Api_ComplaintAppointment::class, 'complaint_id')->latestOfMany();
    }


    //1.1 Pending By Id -----------
    public static function getComplaintDamageDetailsById($complaint_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->complaintNew()
                ->complaintDamage()
                ->where('id', $complaint_id)
                ->whereNull('officer_id')
                ->first();

        return $data;
    }

    public static function getComplaintViolationDetailsById($complaint_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->complaintNew()
                ->complaintViolation()
                ->where('id', $complaint_id)
                ->whereNull('officer_id')
                ->first();

        return $data;
    }

    //1.2 Active By Id-----------
    public static function getComplaintAppointmentDetailsById($complaint_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type','complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                    ->leftJoin('complaint_appointment', function($query) {
                        $query->on('complaint_appointment.complaint_id','=','complaint.id')
                            ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment where data_status = 1 group by complaint_appointment.complaint_id)')
                            ->whereNotNull('complaint_appointment.appointment_status_id');
                    })
                    ->where([
                                ['complaint.data_status', 1],
                                ['complaint.complaint_type',1],
                            ])
                    ->with('user:id,name,phone_no_hp')
                    ->with('officer:id,name,phone_no_hp')
                    ->with('quarters.category')
                    ->with(['quarters' => function ($q) {
                        $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                    ->orderBy('complaint_appointment.id', 'DESC')
                    ->whereIn('complaint.complaint_status_id', [0,1,5]) //baru,diterima,selenggara
                    ->where('complaint.id', $complaint_id)
                    ->first();

        return $data;
    }

    public static function getActiveComplaintViolationDetailsById($complaint_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                    ->with('user:id,name,phone_no_hp')
                    ->with('officer:id,name,phone_no_hp')
                    ->with('quarters.category')
                    ->active()
                    ->complaintViolation()
                    ->where('id', $complaint_id)
                    ->complaintViolationActive() //baru,diterima,berulang
                    ->first();

        return $data;
    }

     //1.3 Completed by Id --------------------
     public static function getCompletedComplaintDamageInspectionById($complaint_id)
     {
         $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                 ->with('user:id,name,phone_no_hp')
                 ->with('officer:id,name,phone_no_hp')
                 ->with('quarters.category')
                 ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                 ->active()
                 ->complaintCompleted()
                 ->complaintDamage()
                 ->where('id', $complaint_id)
                 ->first();

         return $data;
     }

     public static function getCompletedComplaintViolationInspectionById($complaint_id)
     {
         $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                 ->complaintCompleted()
                 ->complaintViolation()
                 ->where('id', $complaint_id)
                 ->first();

         return $data;
     }

     // Apps Penguatkuasa : pemantauan > Aduan yg perlu dipantau
    public static function getComplaintDamagePendingMonitorById($complaint_id, $user_id)
    {
        $data = self:: with('user:id,name,new_ic,email,phone_no_hp')
                ->with('officer:id,name,new_ic,email,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->leftJoin('complaint_appointment', 'complaint_appointment.complaint_id', '=', 'complaint.id')
                ->where(function ($query) {
                    $query->where('complaint.complaint_status_id', 0); // baru
                    $query->where('complaint_appointment.data_status', 1);
                    $query->where('complaint_appointment.appointment_status_id', 1); // setuju
                    $query->orderBy('complaint_appointment.id', 'desc'); //latest appointment
                })
                ->complaintDamage()
                ->active()
                ->orderBy('complaint_appointment.id', 'desc') // move the orderBy outside the where closure
                ->byOfficer($user_id)
                ->where('complaint.id', $complaint_id)
                ->select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status', 'complaint_appointment.id as appointment_id', 'complaint_appointment.appointment_status_id', DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'complaint_appointment.monitoring_remarks')
                ->first();


        return $data;
    }


    //1.4 Pending List---------------- ADUAN>ADUAN
    public static function getPendingComplaintViolationList($userId, $start, $end, $returnType='get')
    {
        $data = self::active()
                ->complaintNew()
                ->complaintViolation()
                ->where('complaint.users_id' , $userId)
                ->whereNull('officer_id')
                ->range($start, $end)
                ->orderBy('complaint.id', 'desc')
                ->$returnType();

        return $data;
    }

    // public static function getPendingComplaintDamageList($userId, $start, $end, $returnType='get') //xx
    // {
    //     $data = self::active()
    //             ->complaintNew()
    //             ->complaintDamage()
    //             ->where('complaint.users_id' , $userId)
    //             ->range($start, $end)
    //             ->whereNull('officer_id')
    //             ->orderBy('complaint.id', 'desc')
    //             ->$returnType();

    //     return $data;
    // }

    //1.5 Active List -----------------
    // public static function getActiveComplaintViolationList($start, $end, $returnType='get')
    // {
    //     $data = self::active()
    //     ->complaintViolation()
    //     ->complaintViolationActive()
    //     ->range($start, $end)
    //     ->orderBy('complaint.id', 'desc')
    //     ->$returnType();

    //     return $data;
    // }

    // public static function getActiveComplaintDamageList($start, $end, $returnType='get')
    // {
    //     $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
    //     ->with('user:id,name,phone_no_hp')
    //     ->with('officer:id,name,phone_no_hp')
    //     ->with('quarters.category')
    //     ->active()
    //     ->complaintDamage()
    //     ->complaintDamageActive()
    //     ->range($start, $end)
    //     ->orderBy('complaint.id', 'desc')
    //     ->$returnType();

    //     return $data;
    // }

    //1.6 Completed List ----------------
    // public static function getCompletedComplaintViolationList($start, $end, $returnType='get')
    // {
    //     $data = self::active()
    //     ->complaintViolation()
    //     ->complaintCompleted()
    //     ->range($start, $end)
    //     ->orderBy('complaint.id', 'desc')
    //     ->$returnType();

    //     return $data;
    // }

    // public static function getCompletedComplaintDamageList($userId, $start, $end, $returnType='get') // REPLACE BY API ..COMPLETEDBYTENANTS
    // {
    //         $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
    //                     ->with('user:id,name,phone_no_hp')
    //                     ->with('officer:id,name,phone_no_hp')
    //                     ->with('quarters.category')
    //                     ->active()
    //                     ->where('complaint.users_id' , $userId)
    //                     ->complaintCompleted()
    //                     ->complaintDamage()
    //                     ->range($start, $end)
    //                     ->orderBy('complaint.id', 'desc') // ADD
    //                     ->$returnType();
    //         return $data;
    // }

    //PENDING MONITORING
    public static function getPendingMonitoring($user_id, $start, $end, $returnType='get')
    {
        $data = self::active()
                ->complaintAccepted()
                ->complaintViolation()
                ->range($start, $end)
                ->byOfficer($user_id)
                ->where('complaint.users_id' , $user_id)
                ->orderBy('complaint.id', 'desc')
                ->$returnType();

        return $data;
    }

    // Apps Penguatkuasa : pemantauan > Aduan yg perlu dipantau
    public static function getPendingMonitoringById($complaintId, $user_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->complaintAccepted()
                ->complaintViolation()
                ->byOfficer($user_id)
                ->where('id', $complaintId)
                ->orderBy('complaint.id', 'desc')
                ->first();

        return $data;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Pemantauan > Aduan Baru !
    //--------------------------------------------------------------------------------------------------------------
    public static function getComplaintDamagePendingMonitor($user_id, $start, $end, $returnType)
    {
        $data = self::leftJoin('complaint_appointment', 'complaint_appointment.complaint_id', '=', 'complaint.id')
                    ->where(function ($query) {
                        $query->where('complaint.complaint_status_id', 0); // baru
                        $query->where('complaint_appointment.data_status', 1);
                        $query->where('complaint_appointment.appointment_status_id', 1); // setuju
                        $query->orderBy('complaint_appointment.id', 'desc'); //latest appointment setuju
                    })
                    ->complaintDamage()
                    ->active()
                    ->byOfficer($user_id)
                    ->range($start, $end)
                    ->orderBy('complaint.id', 'desc')
                    ->select('complaint.id', 'complaint_appointment.id as appointment_id')
                    ->$returnType();

        return $data;
    }

    //cer guna ni semua ******************
    public static function getComplaintDamageById($complaint_id, $appointment_id)
    {
        $data = self:: with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->leftJoin('complaint_appointment', 'complaint_appointment.complaint_id', '=', 'complaint.id')
                ->where('complaint.id', $complaint_id)

                // ->where('complaint_appointment.id', $appointment_id)
                ->select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id',  'complaint.remarks', 'complaint.data_status', 'complaint_appointment.id as appointment_id', 'complaint_appointment.appointment_status_id', DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'complaint_appointment.monitoring_remarks')
                ->orderBy('complaint_appointment.id', 'desc');

                if($appointment_id) {   $data = $data->where('complaint_appointment.id', $appointment_id); }

                $data = $data->first();


        return $data;
    }

    // PEMANTAUAN > ADUAN BARU
    public static function getComplaintViolationPendingMonitor($user_id, $start, $end, $returnType = 'get') // new for pemantauan > Aduan yg perlu dipantau
    {
        $data = self::select('complaint.id')
                ->with('user:id,name,new_ic,email,phone_no_hp')
                ->with('officer:id,name,new_ic,email,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->complaintAccepted()
                ->complaintViolation()
                ->active()
                ->byOfficer($user_id)
                ->range($start, $end)
                ->orderBy('complaint.id', 'desc')
                ->$returnType();

        return $data;
    }

    // Apps Penguatkuasa : pemantauan > Aduan yg selesai
    public static function getComplaintDamageCompleteMonitorById($complaint_id, $user_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status', 'complaint_appointment.id as appointment_id', 'complaint_appointment.appointment_status_id', DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'complaint_appointment.monitoring_remarks')
                ->with(['user:id,name,new_ic,email,phone_no_hp', 'officer:id,name,new_ic,email,phone_no_hp', 'quarters.category']) // Eager load the current_complaint_appointment relationship
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
                ->where(function ($query) {
                    $query->where('complaint_appointment.data_status', 1);
                    $query->where('complaint_appointment.appointment_status_id', 1); // setuju
                    $query->orderBy('complaint_appointment.id', 'desc'); //latest appointment
                })
                ->active()
                ->complaintDamage()
                ->complaintCompleted()
                ->where('complaint.id', $complaint_id)
                ->byOfficer($user_id)
                ->first();

        return $data;
    }

    // Apps Penguatkuasa : pemantauan > Aduan selesai List !
    public static function getComplaintDamageCompleteMonitor($user_id, $start, $end, $returnType = 'get')
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description', 'complaint.complaint_status_id', 'complaint.officer_id', 'complaint.data_status', 'complaint_appointment.id as appointment_id', 'complaint_appointment.appointment_status_id', DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'complaint_appointment.monitoring_remarks')
            ->with(['user:id,name,new_ic,email,phone_no_hp', 'officer:id,name,new_ic,email,phone_no_hp',  'quarters.category']) // Eager load the current_complaint_appointment relationship
            ->with(['quarters' => function ($q) {
                $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
            ->leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
            ->complaintCompleted()
            ->active()
            ->complaintDamage()
            ->byOfficer($user_id)
            ->range($start, $end)
            ->orderBy('complaint.id', 'desc')
            ->$returnType();

        return $data;
    }


    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Selenggara List !
    //--------------------------------------------------------------------------------------------------------------
    public static function getAduanDiselenggara($user_id, $start, $end, $returnType = 'get')
    {

    $data = self:: leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
    ->select('complaint.id', 'complaint.ref_no', 'complaint_appointment.id as appointment_id')
    ->where('complaint_type', 1) // aduan kerosakan shj
    ->whereHas('current_complaint_appointment',function($query){
        $query  ->where('complaint.complaint_status_id', 5) //penyelenggaraan
                ->where('complaint_appointment.data_status', 1)
                ->where('complaint_appointment.appointment_status_id', 1) //setuju
                ->groupBy('complaint_appointment.complaint_id');
    })  ->where('complaint.data_status', 1)->byOfficer($user_id)
    ->range($start, $end)->groupBy('complaint.id');

      $list = $data->$returnType();

        return $list;
    }


    public static function getAduanDiselenggaraById($id)
    {

    $data = self:: leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
    ->select('complaint.id', 'complaint.ref_no', 'complaint_appointment.id as appointment_id')
    ->where('complaint_type', 1) // aduan kerosakan shj
    ->whereHas('current_complaint_appointment',function($query){
        $query  ->where('complaint.complaint_status_id', 5) //penyelenggaraan
                ->where('complaint_appointment.data_status', 1)
                ->where('complaint_appointment.appointment_status_id', 1) //setuju
                ->groupBy('complaint_appointment.complaint_id');
    })  ->where('complaint.data_status', 1)->where('complaint_id', $id)->first();


        return $data;
    }

    // FOR APPS PENGHUNI

     //--------------------------------------------------------------------------------------------------------------
    //  Penghuni : Aduan > Senarai Aduan Selesai
    //--------------------------------------------------------------------------------------------------------------

    public static function getCompletedComplaintList($user_id, $returnType='get')
    {
        $data = self::select('complaint.*')
                    ->with('user:id,name,phone_no_hp')
                    ->with('officer:id,name,phone_no_hp')
                    ->with('quarters.category')
                    ->with(['quarters' => function ($q) {
                        $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                    ->active()
                    ->complaintCompleted()
                    ->where('complaint.users_id', $user_id)
                    ->orderBy('complaint.id', 'desc') // ADD
                    ->$returnType();
        return $data;
    }

    public static function getCompletedComplaintById($user_id, $id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id',  'complaint.officer_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description',  'complaint.complaint_status_id', 'complaint.remarks',  'complaint.data_status')
                    ->with('user:id,name,phone_no_hp')
                    ->with('officer:id,name,phone_no_hp')
                    ->with('quarters.category')
                    ->with(['quarters' => function ($q) {
                        $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                    ->active()
                    ->complaintCompleted()
                    ->where('complaint.users_id', $user_id)
                    ->where('complaint.id', $id)
                    ->first();
        return $data;
    }


    //--------------------------------------------------------------------------------------------------------------
    //  Penghuni : Aduan > Senarai Aduan Baru
    //--------------------------------------------------------------------------------------------------------------

    public static function getActiveComplaintList($user_id)
    {
        $data = self::select('complaint.*')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->complaintActive() // baru & diterima
                ->where('complaint.users_id', $user_id)
                ->orderBy('complaint.id', 'desc')
                ->get();
        return $data;
    }

    public static function getActiveComplaintById($user_id, $id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id',  'complaint.officer_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description',  'complaint.complaint_status_id', 'complaint.remarks',  'complaint.data_status')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->complaintActive() // baru & diterima
                ->where('complaint.users_id', $user_id)
                ->where('complaint.id', $id)
                ->first();
        return $data;
    }

    //--------------------------------------------------------------------------------------------------------------
    //  Penghuni : Aduan > Senarai Aduan Ditolak
    //--------------------------------------------------------------------------------------------------------------

    public static function getRejectedComplaint($user_id, $returnType='get')
    {
        $data = self::active()
                ->complaintRejected()
                ->byUser($user_id)
                ->$returnType();

        return $data;
    }

    public static function getRejectedComplaintById($user_id, $id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id',  'complaint.officer_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description',  'complaint.complaint_status_id', 'complaint.remarks',  'complaint.data_status')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->complaintRejected()
                ->where('complaint.id', $id)
                ->where('complaint.users_id', $user_id)
                ->first();

        return $data;
    }

    //ADUAN > ADUAN BARU !!
    // public static function getPendingComplaintList($user_id, $returnType='get')
    // {
    //     $data = self::active()
    //             ->complaintNew()
    //             ->whereNull('officer_id')
    //             ->where('complaint.users_id', $user_id)
    //             ->orderBy('complaint.id', 'desc')
    //             ->$returnType();

    //             // $data = self::active()

    //             // // ->whereNull('officer_id')
    //             // ->select('complaint.ref_no')
    //             // ->where('complaint.users_id', $user_id)
    //             // ->where('complaint.complaint_status_id', 1)
    //             // ->orWhere('complaint.complaint_status_id', 0)
    //             // ->where('complaint.complaint_type', 2)
    //             // ->orderBy('complaint.id', 'desc')
    //             // ->$returnType();


    //     return $data;
    // }



    ////dfe
    public static function getComplaintById($complaint_id)
    {
        $data = self::select('complaint.id', 'complaint.ref_no', 'complaint.users_id',  'complaint.officer_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_description',  'complaint_status.complaint_status', 'complaint.complaint_status_id', 'complaint.remarks',  'complaint.data_status')
                ->join('complaint_status' , 'complaint_status.id', '=', 'complaint.complaint_status_id')
                ->with('user:id,name,phone_no_hp')
                ->with('officer:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->where('complaint.id', $complaint_id)
                ->first();

        return $data;
    }

    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Dtolak List
    //--------------------------------------------------------------------------------------------------------------
       public static function getAduanAwamDitolak($users_id, $start, $end, $returnType)
       {
           $data = self::select('id', 'ref_no')
                   ->active()
                   ->where('complaint_type', 2)
                   ->where('complaint_status_id', 2) //ditolak
                   ->range($start, $end)
                   ->where('officer_id', $users_id)
                   ->orderBy('id', 'desc');

                   $list= $data->$returnType();

           return $list;
       }

        public static function getAduanAwamDitolakById($id)
        {
           $data = self::select('id', 'ref_no', 'officer_id')->active()
                        ->where(['complaint_type' => 2, 'complaint_status_id'=> 2, 'id' => $id])->first();

           return $data;
        }
    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Dtolak List
    //--------------------------------------------------------------------------------------------------------------

       public static function getAduanKerosakanDitolak($userId, $start, $end, $returnType)
       {
            $data = self::select('complaint.id', 'complaint.ref_no', 'complaint_appointment.id as appointment_id')
            ->leftJoin('complaint_appointment', function($query) {
                $query->on('complaint_appointment.complaint_id','=','complaint.id')
                    ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment where data_status = 1 and appointment_status_id = 1 group by complaint_appointment.complaint_id)');
                })
                ->where('complaint.complaint_status_id', 2) //ditolak
                ->where('complaint.data_status', 1)
                ->where('complaint.complaint_type', 1) //kerosakan
                ->where('complaint.officer_id', $userId)
                ->range($start, $end)
                ->groupBy('complaint.id');

                $data = $data->$returnType();

            return $data;
    }

    public static function getAduanKerosakanDitolakById($id)
    {
         $data = self::select('complaint.id', 'complaint.ref_no', 'complaint_appointment.id as appointment_id')
         ->leftJoin('complaint_appointment', function($query) {
             $query->on('complaint_appointment.complaint_id','=','complaint.id')
                 ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment where data_status = 1 and appointment_status_id = 1 group by complaint_appointment.complaint_id)');
             })
             ->where('complaint.complaint_status_id', 2) //ditolak
             ->where('complaint.data_status', 1)
             ->where('complaint.complaint_type', 1) //kerosakan
             ->where('complaint.id' , $id)
             ->groupBy('complaint.id')->first();

         return $data;
    }

    //--------------------------------------------------------------------------------------------------------------
    //  Penyelenggaraan  > Untuk Tindakan
    //--------------------------------------------------------------------------------------------------------------

    public static function getMaintenanceTransaction($officerId, $start, $end)
    {
        $data = DB::table('complaint as c')
            ->select( 'c.id', 'c.ref_no',  DB::raw('DATE_FORMAT(c.complaint_date, "%Y-%m-%d") as formatted_complaint_date'),
            'mt.id as maintenance_id', 'mt.monitoring_officer_id','mt.maintenance_status_id'
            )
            ->where(['c.data_status'=> 1,'c.complaint_status_id'=> 5, 'c.complaint_type'=> 1])
            ->rightJoin('maintenance_transaction as mt', function($query) use ($officerId) {
                $query->on('mt.complaint_id','=','c.id')
                    ->where(['mt.monitoring_officer_id' => $officerId, 'mt.maintenance_status_id' => 1]) //belum selesai
                    ->groupBy('mt.complaint_id')
                    ->orderBy('id', 'desc');
                })
            ->where (function ($q) use ($start, $end) {
                $q->whereDate('c.complaint_date', '>=', $start)
                ->whereDate('c.complaint_date', '<=', $end);
            })
            ->groupBy('mt.complaint_id')
            ->get();

        return $data;
    }

    public static function getMaintenanceTransactionById($officerId, $id)
    {
        $data = DB::table('complaint as c')
        ->select(  'c.id', 'c.ref_no',  DB::raw('DATE_FORMAT(c.complaint_date, "%Y-%m-%d") as formatted_complaint_date'),  'mt.id as maintenance_id', 'mt.monitoring_officer_id','mt.maintenance_status_id' )
        ->where(['c.id' => $id, 'c.data_status'=> 1,'c.complaint_status_id'=> 5, 'c.complaint_type'=> 1 ] )
        ->rightJoin('maintenance_transaction as mt', function($query) use ($officerId) {
            $query->on('mt.complaint_id','=','c.id')
                ->where(['mt.monitoring_officer_id' => $officerId, 'mt.maintenance_status_id' => 1])  //belum selesai
                ->groupBy('mt.complaint_id')
                ->orderBy('id', 'desc');
            })
        ->first();

        return $data;
    }


    public static function getMaintenanceTransactionDetailsById($complaint_id) // lebih details utk page transaksi selenggara
    {
        $data = self:: with('user:id,name,phone_no_hp')
                ->with('quarters.category')
                ->with(['quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->leftJoin('complaint_appointment', 'complaint_appointment.complaint_id', '=', 'complaint.id')
                ->where('complaint.id', $complaint_id)
                ->select('complaint.id', 'complaint.ref_no', 'complaint.users_id', 'complaint.quarters_id', DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'complaint.complaint_type', 'complaint.complaint_status_id')
                ->orderBy('complaint_appointment.id', 'desc');

                $data = $data->first();


        return $data;
    }


    public static function getMaintenanceTransactionHistory($officerId, $start, $end, $maintenance_status, $complaint_status)
    {
        $data = DB::table('complaint as c')
            ->leftJoin('maintenance_transaction as mt', 'mt.complaint_id', '=', 'c.id')
            ->select(  'c.id', 'c.ref_no',  DB::raw('DATE_FORMAT(c.complaint_date, "%Y-%m-%d") as formatted_complaint_date'),  'mt.id as maintenance_id', 'mt.monitoring_officer_id','mt.maintenance_status_id' )
            ->where(['c.data_status'=> 1,'c.complaint_status_id'=> $complaint_status, 'c.complaint_type'=> 1, 'mt.maintenance_status_id' => $maintenance_status, 'mt.monitoring_officer_id' => $officerId] )
            ->where (function ($q) use ($start, $end) {
                $q->whereDate('c.complaint_date', '>=', $start)
                ->whereDate('c.complaint_date', '<=', $end);
            })
            ->get();

        return $data;
    }


    public static function getMaintenanceTransactionHistoryById($officerId, $id, $maintenance_status, $complaint_status)
    {
        $data = DB::table('complaint as c')
        ->leftJoin('maintenance_transaction as mt', 'mt.complaint_id', '=', 'c.id')
        ->select(  'c.id', 'c.ref_no',  DB::raw('DATE_FORMAT(c.complaint_date, "%Y-%m-%d") as formatted_complaint_date'),  'mt.id as maintenance_id', 'mt.monitoring_officer_id','mt.maintenance_date', 'mt.maintenance_status_id' )
        ->where(['c.data_status'=> 1,'c.complaint_status_id'=> $complaint_status, 'c.complaint_type'=> 1, 'mt.maintenance_status_id' => $maintenance_status, 'mt.monitoring_officer_id' => $officerId, 'c.id' => $id] )
        ->first();

        return $data;
    }


}
