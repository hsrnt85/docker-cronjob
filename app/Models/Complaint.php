<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaint';
    protected $primaryKey = 'id';
    protected $dates= ['complaint_date'];
    protected $fillable = ['row'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function user_office()
    {
        return $this->belongsTo(UserOffice::class, 'users_id');
    }

    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'quarters_id');
    }

    public function complaint_category()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type');
    }

    public function status()
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id');
    }

    public function type()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type');
    }

    public function complaint_appointment()
    {
        return $this->belongsTo(ComplaintAppointment::class, 'complaint_id');
    }

    public function officer()
    {
        return $this->belongsTo(ComplaintStatus::class, 'officer_id');
    }

    public function officer_Api()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function complaint_attachment()
    {
        return $this->hasOne(ComplaintAttachment::class, 'complaint_id');
    }

    public function attachment()
    {
        return $this->hasMany(ComplaintAttachment::class, 'complaint_id')->where('data_status', 1);
    }

    public function complaint_inventory()
    {
        return $this->hasMany(ComplaintInventory::class, 'complaint_id')->where('data_status', 1);
    }

    public function complaint_others()
    {
        return $this->hasMany(ComplaintOthers::class, 'complaint_id')->where('data_status', 1);
    }

    public function inventory()
    {
        return $this->belongsToMany(Inventory::class,'complaint_inventory' , 'complaint_id', 'inventory_id');
    }

    public static function convert_date_db($date){
        if(!empty($date)){

            $date =  date('Y-m-d', strtotime($date));
        }
        return $date;
    }

    public function current_complaint_appointment()
    {
        return $this->hasOne(ComplaintAppointment::class, 'complaint_id')->latestOfMany();
    }

    public function maintenance_status()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id');
    }

    public function monitoring_officer()
    {
        return $this->belongsTo(Officer::class, 'officer_id');
    }

    public function monitoring_officer_2()
    {
        return $this->belongsTo(Officer::class, 'monitoring_officer_id');
    }

    public function maintenance_transaction()
    {
        return $this->hasMany(MaintenanceTransaction::class, 'complaint_id');
    }

    public function current_maintenance_transaction()
    {
        return $this->hasOne(MaintenanceTransaction::class, 'complaint_id')->latestOfMany();
    }

    public function quarters_category_by_quarters()
    {
        return $this->quarters->where('data_status', 1)->first();
    }

    public function complaint_monitoring()
    {
        return $this->belongsTo(ComplaintMonitoring::class, 'complaint_monitoring_id')->where('data_status',1);
    }

    //TRANSAKSI PENYELENGGARAAN --------------------------------
    public static function getMaintenanceTransaction($district_id)
    {
        $data = self::select('maintenance_transaction.monitoring_officer_id', 'complaint.id', 'complaint.ref_no', 'complaint.complaint_date')
        ->leftjoin('maintenance_transaction','maintenance_transaction.complaint_id','=','complaint.id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->where(['complaint.data_status' => 1, 'complaint.complaint_status_id' => 5, 'complaint.complaint_type' => 1]) //BELUM SELENGGARA
        ->where(function ($query) use ($district_id){
            //FILTER BY OFFICER DISTRICT ID
            if($district_id)
            {
                    $query = $query->where('quarters_category.district_id', $district_id);
            }
        })
        ->orwhereHas('current_maintenance_transaction', function ($query) use ($district_id){ //BELUM SELESAI (PICK LATEST ID)

            //FILTER BY OFFICER DISTRICT ID
            if($district_id)
            {
                    $query = $query->where('quarters_category.district_id', $district_id);
            }
            $query->where('data_status', 1)
                ->where('maintenance_status_id', 1)
                ->select('monitoring_officer_id');
        })
        ->groupBy('complaint.id')->orderBy('complaint.id','desc')->get();

        return $data;
    }

    // PEMANTAUAN ADUAN---------------------------------------------
    public static function getNewMonitoringList($district_id = null) //PEMANTAUAN BARU
    {

        $data =  self::leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
        ->join('users','users.id','=','complaint.users_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->select('complaint.id as complaint_ids','complaint.complaint_type','complaint_appointment.id','complaint_appointment.complaint_id', 'complaint.officer_id', 'complaint.ref_no',
        'users.name','quarters_category.name as quarters_name', 'complaint_appointment.appointment_date', 'complaint.complaint_date', 'complaint_appointment.data_status', 'quarters_category.district_id as district_ids')
        //aduan kerosakan
        ->where('complaint.complaint_type', 1)
        ->where(function($query){
            $query->where('complaint.data_status', 1);
            $query->where('complaint.complaint_status_id', 0); //baru
            $query->where('complaint_appointment.data_status', 1);
            // $query->whereHas('current_complaint_appointment');
            $query->where('complaint_appointment.appointment_status_id', 1); //setuju
            $query->orderBy('complaint_appointment.id', 'desc'); //setuju
        })
         //aduan awam
        ->orwhere('complaint.complaint_type', 2)
        ->where(function($query){
            $query->where('complaint.complaint_status_id', 1); //diterima
            $query->where('complaint.data_status', 1);
        });

        //FILTER BY OFFICER DISTRICT ID
        if($district_id)
        {
                $data = $data->where('quarters_category.district_id', $district_id);
        }

        $list = $data->orderBy('complaint.id', 'desc')->get();
        return $list;
    }

    public static function getMonitoringList($complaint_status_id, $district_id=null) //PEMANTAUAN DITOLAK/TERIMA
    {

        $data = self::leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
        ->join('users','users.id','=','complaint.users_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->select('complaint.id as complaint_ids','complaint.complaint_type','complaint_appointment.id','complaint_appointment.complaint_id', 'complaint.ref_no',
        'users.name','quarters_category.name as quarters_name', 'complaint_appointment.appointment_date', 'complaint.complaint_date', 'complaint.officer_id','quarters_category.district_id as district_ids')
        ->where('complaint.complaint_type', 2) //awam
        ->where(function($query) use ($complaint_status_id, $district_id){

            //FILTER BY OFFICER DISTRICT ID
            if($district_id)
            {
                $query = $query->where('quarters_category.district_id', $district_id);
            }

            $query->where('complaint.complaint_status_id', $complaint_status_id )
            ->where('complaint.data_status', 1);

        })
        ->orwhere('complaint.complaint_type', 1) //kerosakan
        ->whereHas('current_complaint_appointment',function($query) use ($complaint_status_id , $district_id){
            //FILTER BY OFFICER DISTRICT ID
            if($district_id)
            {
                    $query = $query->where('quarters_category.district_id', $district_id);
            }

            $query ->where('complaint.complaint_status_id', $complaint_status_id)
                    ->where('complaint_appointment.data_status', 1)
                    ->where('complaint_appointment.appointment_status_id', 1) //setuju
                    ->groupBy('complaint_appointment.complaint_id');
        })
        ->groupBy('complaint.id');
        // ->where('complaint.data_status', 1);

          $list = $data->orderBy('complaint.id', 'desc')->get();

        return $list;
    }

    public static function getRepeatedMonitoringList($district_id) //PEMANTAUAN BERULANG // ADUAN AWAM SAHAJA
    {
        $data = self::leftJoin('complaint_monitoring', 'complaint_monitoring.complaint_id', '=', 'complaint.id')
        ->join('users','users.id','=','complaint.users_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->leftjoin('monitoring_counter', 'monitoring_counter.counter', '=', 'complaint_monitoring.monitoring_counter')
        ->select('complaint.id as complaint_ids','complaint.complaint_type', 'complaint.ref_no', 'complaint.officer_id',
        'users.name','quarters_category.name as quarters_name', 'complaint.complaint_date', 'monitoring_counter.label as monitoring_status', 'complaint.complaint_status_id')
        ->where('complaint.complaint_type', 2) // aduan awam shj
        ->where('complaint.complaint_status_id', 4) //pemeriksaan berulangs
       ->where('complaint.data_status', 1)->groupBy('complaint.id');

        //FILTER BY OFFICER DISTRICT ID
        if($district_id)
        {
                $data = $data->where('quarters_category.district_id', $district_id);
        }

        $list = $data->orderBy('complaint.id', 'desc')->get();

        return $list;
    }

    public static function getMaintananceMonitoringList($district_id) // PEMANTAUAN ADUAN YANG DISELENGGARA
    {
        $data = self::leftjoin('complaint_appointment','complaint_appointment.complaint_id','=','complaint.id')
        ->join('users','users.id','=','complaint.users_id')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->select('complaint.id as complaint_ids','complaint.complaint_type','complaint_appointment.id','complaint_appointment.complaint_id', 'complaint.ref_no',
        'users.name','quarters_category.name as quarters_name', 'complaint_appointment.appointment_date', 'complaint.complaint_date', 'complaint.officer_id')
        ->where('complaint_type', 1) // aduan kerosakan shj
        ->whereHas('current_complaint_appointment',function($query){
            $query  ->where('complaint.complaint_status_id', 5) //penyelenggaraan
                    // ->where('complaint.is_maintenance', 1) //penyelenggaraan
                    ->where('complaint_appointment.data_status', 1)
                    ->where('complaint_appointment.appointment_status_id', 1) //setuju
                    ->groupBy('complaint_appointment.complaint_id');
        })  ->where('complaint.data_status', 1)->groupBy('complaint.id');

          //FILTER BY OFFICER DISTRICT ID
          if($district_id)
          {
                  $data = $data->where('quarters_category.district_id', $district_id);
          }

          $list = $data->orderBy('complaint.id', 'desc')->get();

        return $list;
    }

    //TEMUJANJI ADUAN ----------------------------------------------
    public static function getNewAppointmentList($district_id)
    {

        $data = self::select('complaint.id', 'complaint.users_id' ,'complaint.ref_no', 'complaint.officer_id','complaint.complaint_date', 'complaint.complaint_status_id', 'quarters_category.name as quarters_name')
        ->join('quarters','quarters.id','=','complaint.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->where('complaint.data_status', 1)->where('complaint.complaint_status_id', 0)->where('complaint.complaint_type',1)
        ->WhereHas('current_complaint_appointment', function ($query) use ($district_id){

            if($district_id)
            {
                    $query = $query->where('quarters_category.district_id', $district_id);
            }

            $query->where('data_status', 2)//batal
                ->orWhere('appointment_status_id', 2);//tidak setuju
        })
        ->orWhere('complaint.data_status', 1)->where('complaint.complaint_status_id', 0)->where('complaint.complaint_type',1)
        ->doesntHave('current_complaint_appointment')//check if any appointment
        ->orderBy('complaint.complaint_date','desc')
        ->orderBy('complaint.ref_no', 'desc');

          //FILTER BY  OFFICER DISTRICT ID
          if($district_id)
          {
                  $data = $data->where('quarters_category.district_id', $district_id);
          }

          $list = $data->get();

        return $list;
    }

    // PENGESAHAN ADUAN AWAM ------------------------------------------

    public static function getPublicComplaintApprovalList($complaint_status_id, $district_id)
    {

        $data = self::select('complaint.id', 'complaint.users_id', 'complaint.ref_no','complaint.complaint_date', 'complaint.complaint_status_id', 'quarters_category.name as quarters_name')
                    ->join('quarters','quarters.id','=','complaint.quarters_id')
                    ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
                    ->where(['complaint.data_status'=> 1 ,'complaint.complaint_status_id' => $complaint_status_id , 'complaint.complaint_type'=> 2] )
                    ->orderBy('complaint.id','desc');

          //FILTER BY OFFICER DISTRICT ID
          if($district_id)
          {
                $data = $data->where('quarters_category.district_id', $district_id);
          }

          $list = $data->get();

        return $list;
    }

    public static function getPublicComplaintHistoryList($district_id)
    {

        $data = self::select('complaint.id', 'complaint.users_id', 'complaint.ref_no','complaint.complaint_date', 'complaint.complaint_status_id', 'quarters_category.name as quarters_name')
                    ->join('quarters','quarters.id','=','complaint.quarters_id')
                    ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
                    ->where(['complaint.data_status'=> 1 , 'complaint.complaint_type'=> 2] )
                    ->whereIn('complaint.complaint_status_id', [2,3])
                    ->orderBy('complaint.id','desc');

          //FILTER BY OFFICER DISTRICT ID
          if($district_id)
          {
                $data = $data->where('quarters_category.district_id', $district_id);
          }

          $list = $data->get();

        return $list;
    }

    // LAPORAN PEMANTAUAN ------------------------------------------

    public static function getMonitoringReport($district_id, $search_date_from, $search_date_to, $search_quarters_cat, $search_status,  $search_type)
    {

        $list = Complaint::select('complaint.id', 'complaint.ref_no', 'complaint.complaint_date', 'complaint.users_id','complaint.quarters_id', 'complaint.complaint_description','complaint.complaint_status_id', 'q.quarters_cat_id',
        'quarters_category.district_id', 'complaint.officer_id', 'complaint.complaint_type', 'complaint.data_status');

        if ($search_type == 1) { //aduan kerosakan
            $list->addSelect('appointment.appointment_date', 'appointment.monitoring_remarks as monitor_remarks_for_damage', 'appointment.id as complaint_appointment_id');
        } else { //aduan awam
            $list->addSelect('monitor.*', 'monitor.id as complaint_monitoring_id');
        }

        $list->join('quarters as q', 'q.id', '=', 'complaint.quarters_id');
        $list->join('quarters_category', 'q.quarters_cat_id', '=', 'quarters_category.id');
        if($search_type == 1){
            $list->leftJoin('complaint_appointment as appointment', function($q_app) { //temujanji
                $q_app->on('appointment.complaint_id','=','complaint.id')
                    ->whereRaw('appointment.id IN (select MAX(appointment.id) from complaint_appointment where data_status = 1 group by appointment.complaint_id)')
                    ->whereNotNull('appointment.appointment_status_id');
                })
                ->where('complaint.data_status' , 1 );
        }else{
            $list->leftJoin('complaint_monitoring as monitor', function($q_monitor) { //pemantauan awam
                $q_monitor->on('monitor.complaint_id' , '=', 'complaint.id')->where('monitor.data_status', 1);
                    // ->join('complaint_monitoring_attachment as monitor_attach' , function($q_attach) { // gambar pemantauan awam
                    //     $q_attach->on('monitor_attach.complaint_monitoring_id','=','monitor.id')
                    //     ->on('monitor_attach.monitoring_counter','=','monitor.monitoring_counter')
                    //     ->where('monitor_attach.data_status', 1);
                // });
            });
        }
        $list->where('complaint.data_status' , 1 );

       if($district_id){ $list = $list->where('quarters_category.district_id', $district_id);  }
       if($search_date_from){ $list = $list->where('complaint.complaint_date','>=' , $search_date_from); }
       if($search_date_to  ){ $list = $list->where('complaint.complaint_date','<=' , $search_date_to); }
       if($search_quarters_cat){ $list = $list->where('q.quarters_cat_id', $search_quarters_cat);  }
       if($search_type){ $list = $list->where('complaint.complaint_type', $search_type);  }
       $list = $list->where('complaint.complaint_status_id', ($search_status == null) ? '0' : $search_status); //0:baru

       $getMonitoringList = $list->orderBy('complaint.id','desc')->groupBy('complaint.id')->get();

        return $getMonitoringList;
    }

    //------------------------------------------------------------------------------------------------------------------------
    // DASHBOARD
    //------------------------------------------------------------------------------------------------------------------------
    public static function getDashboardComplaint($district_id, $complaint_type){

        $counterNew = Complaint::where(['data_status'=> 1, 'complaint_type'=> $complaint_type])->whereRaw('(complaint_status_id IS NULL OR complaint_status_id=0)');
        if($district_id) $counterNew = $counterNew->where('district_id', $district_id);
        $counterNew = $counterNew->count();

        $counterInAction = Complaint::where(['data_status'=> 1, 'complaint_type'=> $complaint_type])->whereIn('complaint_status_id', [1,4,5]);
        if($district_id) $counterInAction = $counterInAction->where('district_id', $district_id);
        $counterInAction = $counterInAction->count();

        $counterRejected = Complaint::where(['data_status'=> 1, 'complaint_type'=> $complaint_type])->where('complaint_status_id',2);
        if($district_id) $counterRejected = $counterRejected->where('district_id', $district_id);
        $counterRejected = $counterRejected->count();

        $counterDone = Complaint::where(['data_status'=> 1, 'complaint_type'=> $complaint_type])->where('complaint_status_id',3);
        if($district_id) $counterDone = $counterDone->where('district_id', $district_id);
        $counterDone = $counterDone->count();

        $counterAll = $counterNew + $counterInAction + $counterRejected + $counterDone;

        $dataArr['new'] = $counterNew;
        $dataArr['in_action'] = $counterInAction;
        $dataArr['rejected'] = $counterRejected;
        $dataArr['done'] = $counterDone;
        $dataArr['total'] = $counterAll;

        return $dataArr;
    }


}
