<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintAppointment;
use App\Models\ComplaintAttachment;
use App\Http\Requests\ComplaintAppointmentPostRequest;
use App\Models\ComplaintInventoryAttachment;
use App\Models\ComplaintInventory;
use App\Models\ComplaintOthers;
use App\Notifications\ComplaintAppointmentNotification;
use App\Notifications\AppointmentLateApprovalNotification;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class ComplaintAppointmentController extends Controller
{
    //for notification - url
    private $folderPathTemp = "modules.Monitoring.ComplaintAppointment";

    public function __construct()
    {
        set_time_limit(0);
    }

    public function index()
    {

        if(!getFolderPath()){
            $folderPath = $this->folderPathTemp;
        }else{
            $folderPath = getFolderPath();
        }

        //Appointment will cancel automatically if tenant LATE SUBMIT APPOINTMENT APPROVAL to admin ---------------------------------------------------------------------------------------
        $cancelAppointmentAll = ComplaintAppointment::where('data_status', 1) ->whereNull('appointment_status_id')
        ->whereRaw("complaint_id NOT IN (SELECT complaint_id FROM complaint_appointment b where b.appointment_status_id =1 and b.data_status=1)")
        ->whereRaw("complaint_id IN (SELECT id FROM complaint WHERE data_status =1 and complaint_type =1)")
        ->where(function($query){
            $query->where('appointment_date' , '<=',  now()->format('Y-m-d') )  //Boleh sahkan hari yang sama dengan hari temujanji
                  ->where('appointment_time' , '<=',  now()->format('H:i:s') ); //Batal setelah sampai masa temujanji
        })
        ->orderBy('id','desc')
        ->groupby('complaint_id')
        ->get();

        if($cancelAppointmentAll)
        {
            //update table 1
            foreach($cancelAppointmentAll as $cancelAppointment)
            {
                $complaint = Complaint::where('id', $cancelAppointment->complaint_id)->first();

                $complaint->cancel_reason             = 'Dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan. Sila buat aduan baru untuk tetapan temujanji yang baru.';
                $complaint->data_status               = 2;
                $complaint->delete_on                 = currentDate();
                $complaint->save();
            }

            //update table 2
            foreach($cancelAppointmentAll as $cancelAppointment)
            {
                $complaintAppointment = ComplaintAppointment::where('id', $cancelAppointment->id)->first();

                $complaintAppointment->cancel_remarks            = 'Dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan. Sila buat aduan baru untuk tetapan temujanji yang baru.';
                $complaintAppointment->data_status               = 2;
                $complaintAppointment->delete_on                 = currentDate();
                $complaintAppointment->save();

                //SEND NOTIFICATION TO USER
                //LATE SUBMIT APPOINTMENT APPROVAL
                $appointment_id = $cancelAppointment->id;
                $complaint = Complaint::where('id', $cancelAppointment->complaint_id )->first();
                $complaint_ref_no = $complaint->ref_no;

                $complaint->user?->notify(new AppointmentLateApprovalNotification($appointment_id, $complaint_ref_no));
            }
        }

        //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        //aduan baru, temujanji yg pengadu tidak setuju & temujanji batal sahaja.
        $senaraiAduanAll = Complaint::getNewAppointmentList($district_id);

        //temujanji yang telah dihantar ke pengadu dan sedang menunggu pengesahan.
        $pengesahanTemujanjiAll = ComplaintAppointment::getAppointmentApprovalList($district_id);

        //temujanji yang telah dipersetujui oleh pengadu
        $senaraiTemujanjiAll = ComplaintAppointment::getAppointmentHistoryList($district_id);

        //yang dibatalkan oleh sistem sahaja
        $temujanjiDibatalkan =  ComplaintAppointment::getCancelAppointmentList($district_id);

        return view(  $folderPath.'.list',
        [
            'senaraiAduanAll' => $senaraiAduanAll,
            'pengesahanTemujanjiAll' => $pengesahanTemujanjiAll,
            'senaraiTemujanjiAll' => $senaraiTemujanjiAll,
            'temujanjiDibatalkan' => $temujanjiDibatalkan,
        ]);
    }

    public function create(Request $request)
    {
        if(!getFolderPath()){
            $folderPath = $this->folderPathTemp;
        }else{
            $folderPath = getFolderPath();
        }

        $id = $request->id;
        //HAS APPOINTMENT ACTIVE = TEMUJANJI BELUM DISAHKAN OLEH PENGADU / PENGADU DAH SETUJU
        $hasAppointment = ComplaintAppointment::select('id', 'appointment_status_id')->where(['complaint_id' => $id, 'data_status' => 1])
                                                ->where(function ($query){
                                                    $query->whereNull('appointment_status_id')
                                                    ->orWhere('appointment_status_id', 1); // setuju
                                                })
                                                ->orderBy('id', 'desc')->first();

        if($hasAppointment){

            //block if officer dah buat temujanji (notification)
            return redirect()->route('complaintAppointment.index')->with('error', 'Pegawai telah membuat Temujanji Aduan!');
        }

        $complaint = Complaint::select('complaint.*', DB::raw('complaint.ref_no'))
        ->where([['data_status', 1],['id', $id]])
        ->first();

        $complaint_inventory = ComplaintInventory::join('complaint', 'complaint_inventory.complaint_id', '=', 'complaint.id')
        ->join('inventory', 'complaint_inventory.inventory_id', '=', 'inventory.id')
        ->select('complaint.id As complaint_id','complaint_inventory.id AS complaint_inventory_id','complaint_inventory.description', 'inventory.name')
        ->where([['complaint.id', $id], ['complaint.data_status',1], ['complaint_inventory.data_status',1]])
        ->get();

        $complaintInventory = ComplaintInventory::where('data_status', 1)->where('complaint_id', $id )->first();

        if($complaintInventory){
            $complaintInventoryAttachment = ComplaintInventoryAttachment::where('data_status', 1)->where('complaint_inventory_id', $complaintInventory->id)->get();
        }
        else{
            $complaintInventoryAttachment = ComplaintInventoryAttachment::where('data_status', 1)->get();
        }

        $complaint_others = Complaint::join('complaint_others', 'complaint_others.complaint_id', '=', 'complaint.id')
        ->where([['complaint.id', $id], ['complaint.data_status',1], ['complaint_others.data_status',1]])
        ->get();

        $complaintOthers =  ComplaintOthers::where('data_status', 1)->where('complaint_id', $id )->first();
        if($complaintOthers)
        {
            $complaint_attachment = ComplaintAttachment::where('complaint_id', $complaint->id)->where('data_status', 1)->get();
        }
        else{
            $complaint_attachment = ComplaintAttachment::where('data_status', 1)->get();
        }

        $tab = (!$hasAppointment) ? 'baru' : '';

        // if(checkPolicy("U"))
        // {
            return view( $folderPath.'.create',
            [
                'complaint' => $complaint,
                'complaint_inventory' => $complaint_inventory,
                'complaint_others' => $complaint_others,
                'complaintInventoryAttachment' => $complaintInventoryAttachment,
                'complaint_attachment' => $complaint_attachment,
                'cdn' => config('env.upload_ftp_url'),
                'tab' => $tab
            ]);
        // }
        // else
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }

    }

    public function store(ComplaintAppointmentPostRequest $request)
    {

        $id = $request->id;

        $appointmentDate   = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->appointment_date));

        $appointment = new ComplaintAppointment;
         //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data before changes
        $data_before = $appointment->getRawOriginal();//dd($data_before);
        $data_before['item']= $appointment->toArray() ?? [];//dd($data_before);
        //------------------------------------------------------------------------------------------------------------------

        $appointment->complaint_id                 = $id;
        $appointment->appointment_date             = $appointmentDate;
        $appointment->appointment_time             = $request->appointment_time;
        $appointment->data_status                  = 1;
        $appointment->action_by                    = loginId();
        $appointment->action_on                    = currentDate();

        $saved = $appointment->save();

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data after changes
        $data_after = $appointment;
        $data_after['item'] = $appointment->toArray() ?? [];

        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Save
        setUserActivity("U", "Temujanji Aduan ".$appointment->complaint?->ref_no, $data_before_json, $data_after_json);
        //------------------------------------------------------------------------------------------------------------------


        Complaint::where('id', $request->id)->where('data_status', 1)
                                            ->update([ 'officer_id'   => loginId(), 'officer_respond_on' => currentDate() ]);

        //SEND NOTIFICATION TO PORTAL
        $flag_proses = "store";
        $complaint = Complaint::where('id', $id)->where('data_status', 1)->first();
        $complaint_ref_no = $complaint->ref_no;
        $appointment_id =  $appointment->id;

        $complaint->user?->notify(new ComplaintAppointmentNotification($complaint_ref_no, $flag_proses, $appointment_id));

        if(!$saved)
        {
            return redirect()->route('complaintAppointment.create')->with('error', 'Maklumat Temujanji Aduan tidak berjaya dihantar!');
        }
        else
        {
            return redirect()->route('complaintAppointment.index')->with('success', 'Maklumat Temujanji Aduan berjaya dihantar!');
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $appointmentCheck = ComplaintAppointment::where(['complaint_id' => $id , 'data_status' => 1])->orderBy('id', 'desc')->first();

        if($appointmentCheck->appointment_status_id)
        {
            //block if tenant dah sahkan temujanji(notification)
            return redirect()->route('complaintAppointment.index')->with('error', 'Pengadu telah membuat Pengesahan Temujanji Aduan!');
        }

        $complaint = Complaint::select('complaint.*', DB::raw('complaint.ref_no'))
        ->where([['data_status', 1],['id', $id]])
        ->first();
        $complaint_appointment = ComplaintAppointment::where('complaint_id', $complaint->id)->where('data_status', 1)
                                                        ->orderBy('appointment_date', 'desc')->get();

        $complaintInventoryAll = ComplaintInventory::where('data_status', 1)->where('complaint_id', $complaint->id )->get();
        $complaintInventory = ComplaintInventory::where('data_status', 1)->where('complaint_id', $complaint->id )->first();

        if($complaintInventory){
            $complaintInventoryAttachment = ComplaintInventoryAttachment::where('data_status', 1)->where('complaint_inventory_id', $complaintInventory->id)->get();
        }
        else{
            $complaintInventoryAttachment = ComplaintInventoryAttachment::where('data_status', 1)->get();
        }

        $complaint_attachment = ComplaintAttachment::where('complaint_id', $complaint->id)->where('data_status', 1)->get();
        $complaint_others = Complaint::join('complaint_others', 'complaint_others.complaint_id', '=', 'complaint.id')
        ->where([['complaint.id', $id], ['complaint.data_status',1], ['complaint_others.data_status',1]])
        ->get();

        $tab = 'pengesahan';

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'complaint' => $complaint,
                'complaint_appointment' => $complaint_appointment,
                'complaintInventoryAll' => $complaintInventoryAll,
                'complaintInventory' => $complaintInventory,
                'complaintInventoryAttachment' => $complaintInventoryAttachment,
                'complaint_attachment' => $complaint_attachment,
                'complaint_others' => $complaint_others,
                'cdn' => config('env.upload_ftp_url'),
                'tab' => $tab,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(ComplaintAppointmentPostRequest $request)
    {

        $appointment = ComplaintAppointment::where(['complaint_id' => $request->complaint_id ,'data_status'=> 1])->orderBy('id', 'desc')->first();

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data before changes
        $data_before = $appointment->getRawOriginal();//dd($data_before);
        $data_before['item']= $appointment->toArray() ?? [];//dd($data_before);
        //------------------------------------------------------------------------------------------------------------------

        $appointmentDate   = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->appointment_date));

        $appointment->complaint_id                 = $request->complaint_id;
        $appointment->appointment_date             = $appointmentDate;
        $appointment->appointment_time             = $request->appointment_time;
        $appointment->data_status                  = 1;
        $appointment->action_by                    = loginId();
        $appointment->action_on                    = currentDate();

        $saved = $appointment->save();

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data after changes
        $data_after = $appointment;
        $data_after['item'] = $appointment->toArray() ?? [];

        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);
        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Save
        setUserActivity("U", "Temujanji Aduan ".$appointment->complaint?->ref_no, $data_before_json, $data_after_json);
        //------------------------------------------------------------------------------------------------------------------


        // $updateAppointmentStatus = ComplaintAppointment::where('complaint_id', $request->complaint_id)
        //                             ->where('data_status', 1)
        //                             ->where('appointment_status_id', 2)
        //                             ->update([
        //                             'appointment_status_id' => NULL,
        //                             'action_by'           => auth()->user()->id,
        //                             'action_on'           => date('Y-m-d H:i:s'),
        //                             ]);

        if(!$saved)
        {
            return redirect()->route('complaintAppointment.edit')->with('error', 'Pengesahan Temujanji Aduan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('complaintAppointment.index')->with('success', 'Pengesahan Temujanji Aduan berjaya dikemaskini!');
        }
    }

    //cancel Appointment
    public function cancel(Request $request)
    {
        $id = $request->id;
        $cancel_remarks = $request->officer_cancel_remarks;

        $complaint_appointment_latest = ComplaintAppointment::where('complaint_id', $id)->where('data_status', 1)
        ->orderBy('appointment_date', 'desc')->first();

        $complaint_appointment_latest->cancel_remarks          =  $cancel_remarks;
        $complaint_appointment_latest->data_status             =  2;
        $complaint_appointment_latest->delete_by               = loginId();
        $complaint_appointment_latest->delete_on               = currentDate();
        $saved = $complaint_appointment_latest->save();

        //SEND NOTIFICATION TO PORTAL
        $flag_proses = "cancel";
        $complaint = Complaint::where('id', $id)->where('data_status', 1)->first();
        $complaint_ref_no = $complaint->ref_no;
        $appointment_id =  $complaint_appointment_latest->id;

        $complaint->user?->notify(new ComplaintAppointmentNotification($complaint_ref_no, $flag_proses, $appointment_id));

        if(!$saved)
        {
            return redirect()->route('complaintAppointment.index')->with('error', 'Temujanji Aduan tidak berjaya dibatalkan!');
        }
        else
        {
            return redirect()->route('complaintAppointment.index')->with('success', 'Temujanji Aduan berjaya dibatalkan!');
        }
    }

    public function view(Request $request)
    {
        $folderPath = getFolderPath();
        if(!getFolderPath()){
            $folderPath = $this->folderPathTemp;
        }

        $id = $request->id;
        $complaint = Complaint::where('id', $id)->first();

        $complaint_appointment_history =  ComplaintAppointment::where('complaint_id', $complaint->id)
                                                            // ->where('data_status', 1)
                                                            ->orderBy('id', 'desc')->get();

        $complaint_appointment_history->shift();
        $complaint_appointment_latest =  ComplaintAppointment::where('complaint_id', $complaint->id)
                                                            // ->where('data_status', 1)
                                                            // ->orderBy('appointment_date', 'desc')
                                                            ->orderBy('id', 'desc')
                                                            ->first();

        $complaint_inventory = ComplaintInventory::join('complaint', 'complaint_inventory.complaint_id', '=', 'complaint.id')
        ->join('inventory', 'complaint_inventory.inventory_id', '=', 'inventory.id')
        ->select('complaint.id As complaint_id','complaint_inventory.id AS complaint_inventory_id','complaint_inventory.description', 'inventory.name')
        ->where([['complaint.id', $id], ['complaint.data_status',1], ['complaint_inventory.data_status',1]])
        ->get();

        $complaint_others = Complaint::join('complaint_others', 'complaint_others.complaint_id', '=', 'complaint.id')
        ->where([['complaint.id', $id], ['complaint.data_status',1], ['complaint_others.data_status',1]])
        ->get();

        $complaintInventory = ComplaintInventory::where('data_status', 1)->where('complaint_id', $complaint->id )->first();

        if($complaintInventory){
            $complaintInventoryAttachment = ComplaintInventoryAttachment::where('data_status', 1)->where('complaint_inventory_id', $complaintInventory->id)->get();
        }
        else{
            $complaintInventoryAttachment = ComplaintInventoryAttachment::where('data_status', 1)->get();
        }


        $complaint_attachment = ComplaintAttachment::where('complaint_id', $complaint->id)->where('data_status', 1)->get();

        $tab = ($complaint_appointment_latest?->data_status==2 ) ? 'batal' : 'senarai';

        // if(checkPolicy("V"))
        // {
            return view( $folderPath.'.view',
            [

                'complaint' => $complaint,
                'complaint_attachment' => $complaint_attachment,
                'complaint_appointment' => $complaint_appointment_history,
                'complaint_appointment_latest' => $complaint_appointment_latest,
                'complaint_inventory' => $complaint_inventory,
                'complaint_others' => $complaint_others,
                'complaintInventoryAttachment' => $complaintInventoryAttachment,
                'cdn' => config('env.upload_ftp_url'),
                'tab' => $tab
            ]);
        // }
        // else
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }

    }

    public function ajaxGetAppointmentList(Request $request)
    {
        $tarikh_temujanji = convertDatePickerDb($request->tarikh_temujanji);

        /*SQL  WASTING TIMEEE !!!*/
        // $complaintInventoryAttachment = ComplaintAppointment::join('complaint', 'complaint_appointment.complaint_id', '=', 'complaint.id')
        //     ->leftjoin('complaint_inventory', function ($join){
        //         $join->on('complaint_inventory.complaint_id','=','complaint.id')
        //             ->where([['complaint_inventory.data_status','=', 1],['complaint.complaint_type','=', 1]]);
        //             // ->where('complaint_inventory.inventory_id','=', $inventory_id);
        //         })
        //     ->leftjoin('complaint_others', function ($join){
        //         $join->on('complaint_others.complaint_id','=','complaint.id')
        //             ->where([['complaint_others.data_status','=', 1],['complaint.complaint_type','=', 1]]);
        //                 // ->where('complaint_inventory.inventory_id','=', $inventory_id);
        //         })
        //     ->select('complaint_appointment.id',
        //         DB::raw('(
        //             CASE
        //                 WHEN complaint.complaint_description IS NULL AND  complaint_inventory.description IS NULL then complaint_others.description
        //                 WHEN complaint.complaint_description IS NULL AND  complaint_others.description IS NOT NULL
        //                 THEN CONCAT(GROUP_CONCAT(complaint_inventory.description) , " ,", complaint_others.description)
        //                 WHEN complaint.complaint_description IS NULL THEN complaint_inventory.description

        //                 ELSE complaint.complaint_description

        //             END) AS complaint_description' )
        //         )
        //     ->where([
        //             ['complaint_appointment.data_status', 1],
        //             ['complaint.data_status',1],
        //             ['complaint_appointment.appointment_date', '=', $tarikh_temujanji]
        //         ])
        //     ->whereRaw('(complaint_appointment.appointment_status_id = 1 OR complaint_appointment.appointment_status_id IS NULL)')
        //     ->get();



        $complaintInventoryAttachment = ComplaintAppointment::join('complaint', 'complaint_appointment.complaint_id', '=', 'complaint.id')
            ->join('quarters', 'quarters.id', '=', 'complaint.quarters_id')
            ->join('users', 'users.id', '=', 'complaint.users_id')
            ->select('complaint_appointment.id', 'complaint_appointment.appointment_time', 'users.name', 'quarters.unit_no','quarters.address_1','quarters.address_2','quarters.address_3', DB::raw('complaint.ref_no'))
            ->where([
                    ['complaint_appointment.data_status', 1],
                    ['complaint.data_status',1],
                    ['complaint_appointment.appointment_date', '=', $tarikh_temujanji]
                ])
            ->whereRaw('(complaint_appointment.appointment_status_id = 1 OR complaint_appointment.appointment_status_id IS NULL)')
            ->orderBy('complaint_appointment.appointment_time', 'asc')
            ->get();

        return response()->json(
            [
                'data' => $complaintInventoryAttachment
            ], 201
        );

    }

    public function ajaxGetComplaintInventoryAttachmentList(Request $request)
    {
        $complaint_inventory_id = $request->cid;

        $complaintInventoryAttachment = ComplaintInventoryAttachment::select('complaint_inventory_attachment.path_document', 'complaint_inventory_attachment.complaint_inventory_id', 'complaint_inventory_attachment.id')
                ->where([
                    ['complaint_inventory_attachment.data_status', 1],
                    ['complaint_inventory_attachment.complaint_inventory_id', '=', $complaint_inventory_id]
                ])
                ->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.view-complaint-inventory-attachment')
                ->with(compact('complaintInventoryAttachment'))
                ->render();

        return response()->json(['success' => true, 'html' => $data]);
    }

    public function ajaxGetComplaintOthersAttachmentList(Request $request)
    {
        $complaint_others_id = $request->cod;

        $complaintAttachment = ComplaintAttachment::select('complaint_attachment.path_document', 'complaint_attachment.complaint_others_id', 'complaint_attachment.complaint_id', 'complaint_attachment.id',)
                ->where([
                    ['complaint_attachment.data_status', 1],
                    ['complaint_attachment.complaint_others_id', '=', $complaint_others_id],
                ])
                ->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.view-complaint-others-attachment')
                ->with(compact('complaintAttachment'))
                ->render();

                // dd($data);
        return response()->json(['success' => true, 'html' => $data]);
    }
}
