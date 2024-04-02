<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_ComplaintAppointment extends Model
{
    use HasFactory;

    protected $table = 'complaint_appointment';
    protected $primaryKey = 'id';
    protected $dates = ['appointment_date', 'appointment_time'];

    public $timestamps = false;

    public function status_appointment()
    {
        return $this->belongsTo(Api_AppointmentStatus::class, 'appointment_status_id');
    }

    public function complaint()
    {
        return $this->belongsTo(Api_Complaint::class, 'complaint_id')->orderBy('complaint_date');
    }

    public function quarters()
    {
        return $this->belongsTo(Api_Quarters::class, 'quarters_id');
    }

    public function scopeComplaint_inventory($query)
    {

        return $query->select('complaint_inventory.*')->leftjoin('complaint_inventory', 'complaint_inventory.complaint_id', '=', 'complaint_appointment.id')
        ->where('complaint_inventory.data_status', 1);
    }

    public function type()
    {
        return $this->has(Api_Complaint::class, 'complaint_id');
    }

    public function delete_name()
    {
        return $this->belongsTo(Api_User::class, 'delete_by');
    }

    public function tenants_remarks()
    {
        return $this->belongsTo(Api_TenantsCancelRemarks::class, 'tenants_cancel_remarks_id')->select('remarks');
    }

    public function scopeActive($query)
    {
        return $query->where('complaint_appointment.data_status', 1);
    }

    public function scopeByOfficer($query, $user_id)
    {
        return $query->where('complaint_appointment.action_by', $user_id);
    }

    public function scopeComplaintDamage($query)
    {
        return $query->where('complaint_type', 1);
    }

    public function scopeRange($query, $start, $end)
    {
        $query->whereDate('complaint_appointment.appointment_date', '>=', $start)
            ->whereDate('complaint_appointment.appointment_date', '<=', $end);
    }

    public function scopePendingAppointment($query)
    {
        return $query->whereNull('complaint_appointment.appointment_status_id')
                    ->whereHas('complaint', function($subQ){
                    $subQ->complaintDamageActive()
                    ->complaintDamage()
                    ->active();
                });
    }

    public function scopeRejectedAppointment($query)
    {
        return $query->where('complaint_appointment.appointment_status_id', 2);
    }

    public function scopeActiveAppointment($query)
    {
        return $query->where('complaint_appointment.appointment_status_id', 1)
                ->whereHas('complaint', function($subQ){
                    $subQ->complaintNew()
                    ->complaintDamage()
                    ->active();
                });
    }

    public function scopeCompletedAppointment($query)
    {
        return $query->where('complaint_appointment.appointment_status_id', 1)
                ->whereHas('complaint', function($subQ){
                    $subQ->complaintCompleted()
                    ->complaintDamage()
                    ->active();
                });
    }


    public static function getCompletedAppointment($user_id, $start, $end, $returnType = 'get')
    {
        $data = self::with('complaint.user:id,name,new_ic,email,phone_no_hp')
                ->with('complaint.officer:id,name,new_ic,email,phone_no_hp')
                ->with('complaint.quarters.category')
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->completedAppointment()
                ->byOfficer($user_id)
                ->range($start, $end)
                ->orderBy('complaint_appointment.id', 'desc') // ADD
                ->$returnType();

        return $data;
    }

     //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Temujanji > Temujanji Baru By ID & List
    //-------------------------------------------------------------------------------------------------------------
    public static function getPendingAppointmentByTenants($user_id, $returnType = 'get')
    {
        $data = self::
                // with('complaint.user:id,name,new_ic,email,phone_no_hp')
                // ->with('complaint.officer:id,name,new_ic,email,phone_no_hp')
                // ->with('complaint.quarters.category')
                leftjoin('complaint', function($query) use($user_id){
                    $query->on('complaint.id','=','complaint_appointment.complaint_id')
                        ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment group by complaint_appointment.complaint_id)')
                        ->whereNull('complaint_appointment.appointment_status_id') //belum buat temujanji
                        ->where('complaint.data_status' , 1)
                        ->where('complaint.users_id', $user_id)
                        ->where('complaint.complaint_status_id', '0'); //only aduan baru
                })
                ->select('complaint_appointment.id', 'complaint.id as complaint_id')
                ->active()
                ->where('complaint.data_status' , 1)
                ->where('complaint.complaint_type', 1)
                ->get();

        return $data;
    }

    public static function getPendingAppointmentById($users_id, $id)
    {
        $data = self::from('complaint_appointment as app')
                ->select('app.id', 'app.complaint_id', DB::raw('DATE_FORMAT(app.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(app.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'app.tenant_respond_on', 'app.tenants_cancel_remarks_id', 'app.cancel_remarks', 'app.monitoring_remarks', 'app.data_status')
                ->leftjoin('complaint', function($query) use($users_id){
                    $query->on('complaint.id','=','app.complaint_id')
                        ->whereRaw('app.id IN (select MAX(app.id) from complaint_appointment group by app.complaint_id)')
                        ->whereNull('app.appointment_status_id')
                        ->where('complaint.data_status' , 1)
                        ->where('complaint.users_id', $users_id)
                        ->where('complaint.complaint_status_id', '0'); //only aduan baru
                })
                ->with([
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->where('app.id', $id )
                ->where('app.data_status', 1)
                ->first();

        return $data;
    }

    // public static function getPendingAppointmentById($user_id, $id)
    // {
    //     $data = self::
    //             // with('complaint.user:id,name,new_ic,email,phone_no_hp')
    //             // ->with('complaint.officer:id,name,new_ic,email,phone_no_hp')
    //             // ->with('complaint.quarters.category')
    //             leftjoin('complaint', function($query) use($user_id){
    //                 $query->on('complaint.id','=','complaint_appointment.complaint_id')
    //                     ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment group by complaint_appointment.complaint_id)')
    //                     ->whereNull('complaint_appointment.appointment_status_id') //belum buat temujanji
    //                     ->where('complaint.data_status' , 1)
    //                     ->where('complaint.users_id', $user_id)
    //                     ->where('complaint.complaint_status_id', '0'); //only aduan baru
    //             })
    //             ->select('complaint_appointment.id', 'complaint.id as complaint_id')
    //             ->active()
    //             ->complaintDamage()
    //             ->get();

    //     return $data;
    // }

    public static function getActiveAppointment($user_id, $start, $end, $returnType = 'get')
    {
        $data = self::with('complaint.user:id,name,new_ic,email,phone_no_hp')
                ->with('complaint.officer:id,name,new_ic,email,phone_no_hp')
                ->with('complaint.quarters.category')
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->activeAppointment()
                ->byOfficer($user_id)
                ->range($start, $end)
                ->orderBy('complaint_appointment.id', 'desc') // ADD
                ->$returnType();

        return $data;
    }

    //Pemantauan > Seleai By Id
    public static function getCompletedAppointmentById($appmtId, $user_id)
    {
        $data = self::select('complaint_appointment.id', 'complaint_appointment.complaint_id', 'complaint_appointment.appointment_status_id', DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'complaint_appointment.tenant_respond_on', 'complaint_appointment.tenants_cancel_remarks_id', 'complaint_appointment.cancel_remarks', 'complaint_appointment.monitoring_remarks', 'complaint_appointment.data_status')
                ->with([  // return selected column only in sql
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->completedAppointment()
                ->byOfficer($user_id)
                ->where('complaint_appointment.id', $appmtId)
                ->first();

        return $data;
    }

    public static function getActiveAppointmentById($appmtId, $user_id)
    {
        // $date = ($date) ? date_format(date_create($date), "Y-m-d") : date("Y-m-d");

        $data = self::with('complaint.user:id,name,new_ic,email,phone_no_hp')
                ->with('complaint.officer:id,name,new_ic,email,phone_no_hp')
                ->with('complaint.quarters.category')
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->active()
                ->activeAppointment()
                ->byOfficer($user_id)
                ->where('id', $appmtId)
                ->first();

        return $data;
    }

    public static function latestAppointmentById($appointment_id) //cancel by tenant
    {

        $data = self::from('complaint_appointment as appoinment')
                ->select('appoinment.*', 'complaint.ref_no', 'complaint.officer_id')
                ->join('complaint', 'complaint.id', '=', 'appoinment.complaint_id')
                ->where(['appoinment.id'=> $appointment_id, 'complaint.complaint_status_id' => 0, 'complaint.complaint_type' => 1, 'appoinment.data_status'=> 1,  'complaint.data_status'=> 1])
                ->orderBy('appoinment.id', 'desc')
                ->first();

        return $data;
    }

    public static function latestAppointmentByComplaintId($complaint_id) //cancel by officer
    {

        $data = self::select('appoinment.id', 'complaint.ref_no', 'complaint.id as complaint_id')
        ->from('complaint_appointment as appoinment')
        ->join('complaint', 'complaint.id', '=', 'appoinment.complaint_id')
        ->where(['appoinment.complaint_id'=> $complaint_id, 'complaint.complaint_status_id' => 0, 'complaint.complaint_type' => 1, 'appoinment.data_status'=> 1,  'complaint.data_status'=> 1])
        ->whereNotNull('appoinment.appointment_date')
        ->orderBy('appoinment.id', 'desc')->first();

        return $data;
    }

    public static function getlatestAppointmentByComplaintId($complaint_id) //latest // apps penghuni
    {

        $data = self::select('id', 'monitoring_remarks', 'cancel_remarks')->where('complaint_id', $complaint_id)->where('data_status', 1)->orderBy('id', 'desc')->first();

        return $data;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Temujanji > Senarai Temujanji By ID & List
    //-------------------------------------------------------------------------------------------------------------
    public static function getLatestAppointmentByTenants($user_id)
    {
        $data = self::select('complaint_appointment.id', 'complaint_appointment.complaint_id', 'complaint_appointment.appointment_date','complaint_appointment.id as app_id' ,'complaint_appointment.appointment_status_id', 'complaint.complaint_status_id' ,'complaint.users_id', 'complaint.ref_no')
                ->join('complaint', function($query) use ($user_id) {
                    $query->on('complaint.id','=','complaint_appointment.complaint_id')
                        ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment group by complaint_appointment.complaint_id)')
                        ->whereNotNull('complaint_appointment.appointment_status_id')
                        ->where(['complaint.users_id'=> $user_id , 'complaint.data_status' => 1]);
                    })
                ->orderBy('complaint_appointment.appointment_date', 'desc')
                ->get();
        return $data;
    }

    public static function getLatestAppointmentById($user_id, $id)
    {
        $data = self::select('complaint_appointment.id', 'complaint_appointment.complaint_id', 'complaint_appointment.appointment_status_id',  DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(complaint_appointment.appointment_time, "%h:%i %p") as formatted_appointment_time'),   'complaint_appointment.cancel_remarks',  'complaint_appointment.tenants_cancel_remarks_id', 'complaint_appointment.data_status as appointment_ds', 'complaint_appointment.delete_by',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'))
                ->join('complaint', function($query) use ($user_id) {
                    $query->on('complaint.id','=','complaint_appointment.complaint_id')
                        ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment group by complaint_appointment.complaint_id)')
                        ->whereNotNull('complaint_appointment.appointment_status_id')
                        ->where(['complaint.users_id'=> $user_id , 'complaint.data_status' => 1]);
                    })
                    ->with([  // return selected column only in sql
                        'complaint' => function ($query) {
                            $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                        },
                        'complaint.user:id,name,new_ic,email,phone_no_hp',
                        'complaint.officer:id,name,new_ic,email,phone_no_hp',
                        'complaint.quarters.category:id,district_id,name'
                    ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->orderBy('complaint_appointment.appointment_date', 'desc')
                ->where('complaint_appointment.id' , $id)
                ->first();
        return $data;
    }

    //TEMUJANJI > BATAL TEMUJANJI!

    public static function getCancelAppointmentList($user_id) //appointment latest yg cancel // batal auto
    {
        $data = self::select('complaint_appointment.id', 'complaint_appointment.complaint_id', 'complaint_appointment.appointment_date','complaint_appointment.id as app_id' ,'complaint_appointment.appointment_status_id', 'complaint.complaint_status_id' ,'complaint.users_id', 'complaint.ref_no')
                ->join('complaint', function($query) use ($user_id) {
                    $query->on('complaint.id','=','complaint_appointment.complaint_id')
                        ->whereRaw('complaint_appointment.id IN (select MAX(complaint_appointment.id) from complaint_appointment group by complaint_appointment.complaint_id)')
                        ->whereNull('complaint_appointment.appointment_status_id')
                        ->where('complaint.data_status', 2)
                        ->where('complaint.users_id', $user_id );
                    })
                ->where('complaint_appointment.data_status' , 2)
                ->orderBy('complaint_appointment.appointment_date', 'desc')
                ->get();

        return $data;
    }

    public static function getCancelAppointmentListById($user_id, $id) //tak join dgn appointments_status
    {
        $data = self::from('complaint_appointment as app')
            ->select('app.id', 'app.complaint_id', DB::raw('DATE_FORMAT(app.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(app.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'app.tenants_cancel_remarks_id', 'app.cancel_remarks','app.data_status', 'app.delete_by', 'complaint.id as complaint_id')

                ->join('complaint', function($query) use ($user_id) {
                    $query->on('complaint.id','=','app.complaint_id')
                        ->whereRaw('app.id IN (select MAX(app.id) from complaint_appointment group by app.complaint_id)')
                        ->whereNull('app.appointment_status_id')
                        ->where('complaint.data_status', 2)
                        ->where('complaint.users_id', $user_id );
                    })
                ->with([  // return selected column only in sql
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'officer_id', 'officer_respond_on', 'cancel_reason');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->where('app.data_status' , 2)
                ->where('app.id', $id )
                ->first();

        return $data;
    }


    public static function getCancelAppointmentById( $id) //tak join dgn appointments_status
    {
        $data = self::from('complaint_appointment as app')
            ->select('app.id', 'app.complaint_id', DB::raw('DATE_FORMAT(app.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(app.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'app.cancel_remarks','app.data_status')
                ->join('complaint','complaint.id' , '=', 'app.complaint_id')
                ->with([  // return selected column only in sql
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'officer_id', 'officer_respond_on', 'cancel_reason');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->where('app.id', $id )
                ->first();

        return $data;
    }

    // APPS PENGHUNI !


    public static function getAppointmentById($id)
    {
        $data = self::from('complaint_appointment as app')
                ->select('app.id', 'app.complaint_id', 'app_status.appointment_status', DB::raw('DATE_FORMAT(app.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(app.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'app.tenant_respond_on', 'app.tenants_cancel_remarks_id', 'app.cancel_remarks', 'app.monitoring_remarks', 'app.data_status', 'app.data_status as appointment_ds')
                ->join('complaint','complaint.id' , '=', 'app.complaint_id')
                ->with([  // return selected column only in sql
                    'complaint' => function ($query) {
                        $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
                    },
                    'complaint.user:id,name,new_ic,email,phone_no_hp',
                    'complaint.officer:id,name,new_ic,email,phone_no_hp',
                    'complaint.quarters.category:id,district_id,name,description,data_status'
                ])
                ->with(['complaint.quarters' => function ($q) {
                    $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
                ->join('appointment_status as app_status', 'app_status.id', '=', 'app.appointment_status_id')
                ->where('app.id', $id )
                ->first();

        return $data;
    }

    public static function getAllAppointmentById($id)
    {
        $data = self::from('complaint_appointment as app')
        ->select('app.id', 'app.complaint_id',  DB::raw('DATE_FORMAT(app.appointment_date, "%Y-%m-%d") as formatted_appointment_date'), DB::raw('TIME_FORMAT(app.appointment_time, "%h:%i %p") as formatted_appointment_time'), 'app.tenant_respond_on', 'app.tenants_cancel_remarks_id', 'app.cancel_remarks', 'app.monitoring_remarks', 'app.data_status', 'app.data_status as appointment_datastatus')
        ->leftjoin('complaint','complaint.id' , '=', 'app.complaint_id')
        ->with([  // return selected column only in sql
            'complaint' => function ($query) {
                $query->select('id', 'ref_no', 'users_id',  DB::raw('DATE_FORMAT(complaint.complaint_date, "%Y-%m-%d") as formatted_complaint_date'), 'quarters_id', 'complaint_type', 'complaint_status_id', 'remarks', 'officer_id', 'officer_respond_on', 'cancel_reason', 'flag_maintenance', 'data_status');
            },
            'complaint.user:id,name,new_ic,email,phone_no_hp',
            'complaint.officer:id,name,new_ic,email,phone_no_hp',
            'complaint.quarters.category:id,district_id,name,description,data_status'
        ])
        ->with(['complaint.quarters' => function ($q) {
            $q->select('quarters.id', 'quarters.quarters_cat_id', 'quarters.unit_no', 'quarters.address_1',  DB::raw('IFNULL(quarters.address_2, "") as address_2'), DB::raw('IFNULL(quarters.address_3, "") as address_3')); }])
        ->where('app.id', $id )
        // ->whereIn('app.data_status' , [1,2])
        ->first();


        return $data;
    }


}
