<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\ComplaintAppointment;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintAppointmentAttachment;
use App\Models\ComplaintOthers;
use App\Models\ComplaintInventory;
use App\Models\ComplaintMonitoring;
use App\Models\ComplaintInventoryAttachment;
use App\Http\Requests\ComplaintMonitoringPostRequest;
use App\Models\ApplicationAttachment;
use App\Models\ComplaintMonitoringAttachment;
use App\Models\InventoryCondition;
use App\Models\LeaveOption;
use App\Models\MonitoringStatus;
use App\Models\InventoryStatus;
use App\Models\MonitoringTenantLeave;
use App\Models\MonitoringTenantLeaveStatus;
use App\Models\MonitoringTenantLeaveAttachment;
use App\Models\Tenant;
use App\Models\TenantQuartersInventory;
use App\Models\TenantsLeaveAttachment;
use App\Models\TenantsOptionsAttachment;
use App\Notifications\ComplaintMonitoringNotification;
use App\Notifications\MonitoringTenantsLeaveNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ComplaintMonitoringController extends Controller
{
    public function index()
    {

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $senaraiPemantauanBaruAll = Complaint::getNewMonitoringList($district_id);

        $senaraiPemantauanDitolakAll = Complaint::getMonitoringList(2, $district_id);

        $senaraiPemantauanSelesaiAll = Complaint::getMonitoringList(3, $district_id);

        $senaraiPemantauanBerulangAll = Complaint::getRepeatedMonitoringList($district_id);

        $senaraiPemantauanSelenggaraAll = Complaint::getMaintananceMonitoringList($district_id);

        $senaraiPemantauanKeluarAll = Tenant::getExitMonitoringList($district_id);

        // dd($senaraiPemantauanKeluarAll);

        return view( getFolderPath().'.list',
        [
            'senaraiPemantauanBaruAll' => $senaraiPemantauanBaruAll,
            'senaraiPemantauanDitolakAll' => $senaraiPemantauanDitolakAll,
            'senaraiPemantauanSelesaiAll' => $senaraiPemantauanSelesaiAll,
            'senaraiPemantauanBerulangAll' => $senaraiPemantauanBerulangAll,
            'senaraiPemantauanSelenggaraAll' => $senaraiPemantauanSelenggaraAll,
            'senaraiPemantauanKeluarAll' => $senaraiPemantauanKeluarAll,
        ]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $complaint = Complaint::where('id', $id)->first();
        $complaint_id =  $complaint->id;

        $complaint_monitoring = ComplaintAppointment::select('complaint_appointment.*', 'complaint.id', 'complaint.complaint_type')
        ->join('complaint', 'complaint.id', '=', 'complaint_appointment.complaint_id')
        ->where(['complaint_appointment.complaint_id' => $complaint_id, 'complaint_appointment.data_status' => 1, 'complaint.complaint_type' => 1 ])
        ->orderBy('complaint_appointment.appointment_date', 'desc')->first();

        $complaint_appointment_latest = ComplaintAppointment::where('complaint_id', $complaint->id)->where('data_status', 1)->orderBy('appointment_date', 'desc')->first();

        $complaintInventoryAll = ComplaintInventory::getComplaintInventoryAll($complaint_id);
        $complaint_attachment = ComplaintAttachment::getComplaintAttachmentAll($complaint_id);
        $complaintOthersAll = ComplaintOthers::where(['data_status' => 1 , 'complaint_id' => $complaint_id ])->get();

        $complaintStatus= ComplaintStatus::where(['status_data'=> 1, 'flag_aduan_kerosakan' => 1])->orderBy('id','desc')->get();
        $monitoringStatus= MonitoringStatus::where(['data_status'=> 1, 'flag_monitoring' => 1])->get();

        $tab = 'baru' ;

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'complaint' => $complaint,
                'complaint_monitoring' => $complaint_monitoring,
                'complaintInventoryAll' => $complaintInventoryAll,
                'complaint_attachment' => $complaint_attachment,
                'complaintOthersAll' => $complaintOthersAll,
                'complaintStatus' => $complaintStatus,
                'monitoringStatus' => $monitoringStatus,
                'complaint_appointment_latest' => $complaint_appointment_latest,
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function store(ComplaintMonitoringPostRequest $request)
    {
        $complaint_id = $request->id;
        $inventoryCheck = $request->inventoryCheck;
        $complaint_type = $request->complaint_type;

        if($complaint_type == 1) //ADUAN KEROSAKAN
        {
            if( $inventoryCheck){

                foreach($inventoryCheck as $i=>$icheck)
                {
                     ComplaintInventory::where(['id'=> $request->complaint_inventory_id[$i], 'inventory_id' => $icheck, 'data_status' => 1])
                        ->update([
                            'flag_action'         => 1,
                            'action_by'           => loginId(),
                            'action_on'           => currentDate(),
                            ]);
                }
            }
            if($request->complaintCheck){

                foreach($request->complaintCheck as $i=>$check)
                {
                    ComplaintOthers::where(['id'=> $request->complaint_others_id[$i], 'data_status' => 1])
                    ->update([
                        'flag_action'         => 1,
                        'action_by'           => loginId(),
                        'action_on'           => currentDate(),
                        ]);
                }
            }

            if($request->monitoring_file != null)
            {
                foreach($request->monitoring_file  as $key => $file)
                {
                    $path = $file->store('documents/complaint_monitoring', 'assets-upload');

                    $monitoring = new ComplaintAppointmentAttachment;

                    $monitoring->complaint_appointment_id     = $request->appointment_id;
                    $monitoring->path_document                = $path;
                    $monitoring->data_status                  = 1;
                    $monitoring->action_by                    = loginId();
                    $monitoring->action_on                    = currentDate();

                    $saved = $monitoring->save();
                }
            }

            // update_monitoring_remarks
            ComplaintAppointment::where('complaint_id', $request->id) ->where('data_status', 1)->update([  'monitoring_remarks'  => $request->remarks,
                                                                                                            'action_by'           => loginId(),
                                                                                                            'action_on'           => currentDate(),
                                                                                                        ]);
            // update_complaint_status
            //if selesai = selenggara

            $complaint = Complaint::where('id', $request->id) ->first();
            $complaint->complaint_status_id      = ($request->complaint_status == 3) ? 5 : 2; //if selesai = penyelenggaraan else ditolak
            $complaint->remarks                  =  $request->rejected_reason ?? '' ;

            $saved =  $complaint->save();

        }
        else //ADUAN AWAM
            {
                $monitoring = new ComplaintMonitoring;

                $monitoring->complaint_id                 =  $complaint_id;
                $monitoring->monitoring_remarks           =  $request->remarks;
                $monitoring->monitoring_status_id         =  $request->monitoring_status;
                $monitoring->monitoring_counter           = 1; //kali pertama turun site
                $monitoring->data_status                  = 1;
                $monitoring->action_by                    = loginId();
                $monitoring->action_on                    = currentDate();
                $saved = $monitoring->save();

                if($request->monitoring_file != null)
                {
                    foreach($request->monitoring_file  as $key => $file)
                    {
                        $path = $file->store('documents/complaint_monitoring', 'assets-upload');

                        $monitoringAttachment = new ComplaintMonitoringAttachment;

                        $monitoringAttachment->complaint_monitoring_id      = $monitoring->id;
                        $monitoringAttachment->path_document                = $path;
                        $monitoringAttachment->monitoring_counter           = $monitoring->monitoring_counter;
                        $monitoringAttachment->data_status                  = 1;
                        $monitoringAttachment->action_by                    = loginId();
                        $monitoringAttachment->action_on                    = currentDate();
                        $saved = $monitoringAttachment->save();
                    }
                }

               // update_complaint_status
               $complaint_status = 0;
               if($monitoring->monitoring_status_id == 1) //pemantauan semula
               {
                    $complaint_status = 4; //penyelenggaraan
               }
               else if($monitoring->monitoring_status_id == 2) // selesai
               {
                    $complaint_status = 3;
               }
               else
               {
                    $complaint_status = 2; //ditolak
               }

               $complaint = Complaint::where('id', $complaint_id) ->first();
               if($complaint){
                    $complaint->complaint_status_id  = $complaint_status;
                    $complaint->officer_id           = loginId();
                    $complaint->officer_respond_on   = currentDate();
    
                    $saved = $complaint->save();
               }

        }
         //SEND NOTIFICATION TO PORTAL
        $complaint = Complaint::where('id', $complaint_id)->where('data_status', 1)->first();
        if($complaint){
            $complaint_ref_no = $complaint->ref_no;
            $complaint->user?->notify(new ComplaintMonitoringNotification($complaint_ref_no, $complaint_id, $complaint_type));
        }
        
        if(!$saved)
        {
            return redirect()->route('complaintMonitoring.index')->with('error', 'Maklumat Pemantauan Aduan tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('complaintMonitoring.index')->with('success', 'Maklumat Pemantauan Aduan berjaya ditambah!');
        }
    }

    public function view_aduan_selesai(Request $request)
    {
        $id = $request->id;
        $complaint = Complaint::where('id', $id)->first();
        $complaint_id = $complaint->id;

        //ADUAN KEROSAKAN -------
        $complaint_appointment_latest = ComplaintAppointment::where('complaint_id', $complaint->id)->where('data_status', 1)->orderBy('appointment_date', 'desc')->first();
        $complaint_appointment_attachment = ComplaintAppointmentAttachment::where('complaint_appointment_id', $complaint_appointment_latest?->id) ->where('data_status', 1) ->get();

        $complaintInventoryAll = ComplaintInventory::getComplaintInventoryAll($complaint_id);
        $complaint_others = ComplaintOthers::getComplaintOthersAll($complaint_id);

        //ADUAN AWAM -------
        $complaint_attachment = ComplaintAttachment::getComplaintAttachmentAll($complaint_id);
        $complaint_monitoring = ComplaintMonitoring::where('data_status', 1)->where('complaint_id', $complaint_id)->first();

        $monitoring_id = $complaint_monitoring?->id;
        $monitoring_attachment_1 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 1);
        $monitoring_attachment_2 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 2);
        $monitoring_attachment_3 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 3);

        $tab = 'selesai' ;

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view_aduan_selesai',
            [
                'complaint' => $complaint,
                'complaint_attachment' => $complaint_attachment,
                'complaint_appointment_latest' => $complaint_appointment_latest,
                'complaint_appointment_attachment' => $complaint_appointment_attachment,
                'complaintInventoryAll' => $complaintInventoryAll,
                'complaint_others' => $complaint_others,
                'complaint_monitoring' => $complaint_monitoring,
                'monitoring_attachment_1' => $monitoring_attachment_1,
                'monitoring_attachment_2' => $monitoring_attachment_2,
                'monitoring_attachment_3' => $monitoring_attachment_3,
                'tab' => $tab

            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view_aduan_ditolak(Request $request)
    {
        $id = $request->id;
        $complaint = Complaint::where('id', $id)->first();
        $complaint_id = $complaint->id;

        //ADUAN KEROSAKAN -------
        $complaint_appointment_latest = ComplaintAppointment::where('complaint_id', $complaint->id)->where('data_status', 1) ->orderBy('appointment_date', 'desc')->first();
        $complaint_appointment_attachment = ComplaintAppointmentAttachment::where('complaint_appointment_id', $complaint_appointment_latest?->id)->where('data_status', 1) ->get();
        $complaintInventoryAll = ComplaintInventory::getComplaintInventoryRejected($complaint_id);
        $complaint_others = ComplaintOthers::getComplaintOthersRejected($complaint_id);

        //ADUAN AWAM -------
        $complaint_attachment = ComplaintAttachment::getComplaintAttachmentAll($complaint_id);
        $complaint_monitoring = ComplaintMonitoring::where('data_status', 1)->where('complaint_id', $complaint_id)->first();

        $monitoring_id = $complaint_monitoring?->id;
        $monitoring_attachment_1 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 1);
        $monitoring_attachment_2 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 2);

        $tab = 'ditolak' ;

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view_aduan_ditolak',
            [
                'complaint' => $complaint,
                'complaint_appointment_latest' => $complaint_appointment_latest,
                'complaintInventoryAll' => $complaintInventoryAll,
                'complaint_others' => $complaint_others,
                'complaint_attachment' => $complaint_attachment,
                'complaint_appointment_attachment' => $complaint_appointment_attachment,
                'complaint_monitoring' => $complaint_monitoring,
                'monitoring_attachment_1' => $monitoring_attachment_1,
                'monitoring_attachment_2' => $monitoring_attachment_2,
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view_aduan_selenggara(Request $request) //ADUAN KEROSAKAN SHJ
    {
        $id = $request->id;
        $complaint = Complaint::where('id', $id)->first();
        $complaint_id = $complaint->id;

        $complaint_appointment_latest = ComplaintAppointment::where('complaint_id', $complaint->id)->orderBy('appointment_date', 'desc')->first();
        $complaint_appointment_attachment = ComplaintAppointmentAttachment::where('complaint_appointment_id', $complaint_appointment_latest->id)->where('data_status', 1)->get();

        $complaint_others = ComplaintOthers::getComplaintOthersMaintanance($complaint_id);
        $complaintInventoryAll = ComplaintInventory::getComplaintInventoryMaintanance($complaint_id);

        $tab = 'selenggara' ;

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view_aduan_selenggara',
            [
                'complaint' => $complaint,
                'complaint_appointment_latest' => $complaint_appointment_latest,
                'complaint_appointment_attachment' => $complaint_appointment_attachment,
                'complaintInventoryAll' => $complaintInventoryAll,
                'complaint_others' => $complaint_others,
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view_aduan_berulang(Request $request) //ADUAN AWAM SHJ //EDIT PAGE BUKAN VIEW
    {
        $complaint_id = $request->id;
        $complaint = Complaint::where('id', $complaint_id)->first();
        $complaint_attachment = ComplaintAttachment::getComplaintAttachmentAll($complaint_id);

        $complaint_monitoring = ComplaintMonitoring::where('data_status', 1)->where('complaint_id', $complaint_id)->first();

        $monitoring_status_repeat = MonitoringStatus::where('data_status', 1)->where('flag_repeat', 1)->get();
        $monitoring_status_final = MonitoringStatus::where('data_status', 1)->where('flag_final', 1)->get();

        $monitoring_id = $complaint_monitoring->id;
        $monitoring_attachment_1 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 1);
        $monitoring_attachment_2 = ComplaintMonitoringAttachment::getComplaintMonitoringAttachmentAll($monitoring_id, 2);
        $tab = 'berulang' ;

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.view_aduan_berulang',
            [
                'complaint' => $complaint,
                'complaint_attachment' => $complaint_attachment,
                'complaint_monitoring' => $complaint_monitoring,
                'monitoring_status_repeat' => $monitoring_status_repeat,
                'monitoring_status_final' => $monitoring_status_final,
                'monitoring_attachment_1' => $monitoring_attachment_1,
                'monitoring_attachment_2' => $monitoring_attachment_2,
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view_penghuni_keluar(Tenant $tenant) //ADUAN PENGHUNI KELUAR
    {
        // if (!checkPolicy("U")) {
        //     return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        // }

        // $tenant = Tenant::getSingleTenant($category->id, $tenant->id);

        $applicationAttachmentAll = ApplicationAttachment::where('a_id', $tenant->application->id)
            ->where('data_status', 1)
            ->orderBy('d_id', 'asc')
            ->get();

        if ($tenant->leave_status_id == 1) {
            $leave_id_arr = stringToArray($tenant->leave_option_id, ','); 
            $leaveOptionIdAll = LeaveOption::where('data_status', 1)->whereIn('id', $leave_id_arr)->get();

            $tenantsLeaveAttachment = TenantsLeaveAttachment::where(['data_status' => 1, 'tenants_id' => $tenant->id])->first();

            $tenantsQuartersInventoryAll = TenantQuartersInventory::with('monitoring_status')
            ->with('inventory')
            ->with('condition')
            ->with('inventory_status')
            ->with('inventory_status_out')
            ->where(['data_status' => 1, 'tenants_id' => $tenant->id])
                ->get();

            $leaveOptionDocumentAll = TenantsOptionsAttachment::select('tenants_options_attachment.*', 'leave_option.description', 'leave_option.flag_option')
            ->leftJoin('leave_option', 'leave_option.id', '=', 'tenants_options_attachment.leave_option_id')
            ->where('leave_option.data_status', 1)->where('tenants_options_attachment.data_status', 1)->where('leave_option.flag_option', 2)->where('tenants_options_attachment.tenants_id', $tenant->id)->get();

            $inventoryStatus = InventoryStatus::where('data_status', 1)->get();
            $inventoryCondition = InventoryCondition::select('id','inventory_condition')->where('data_status', 1)->get();
            $monitoringLeaveStatus = MonitoringTenantLeaveStatus::select('id','monitoring_status')->where('data_status',1)->get();
        }

        //Monitoring attachment
        $attachmentAll = $tenant->monitor_leave->attachments ?? null;

        return view(
            getFolderPath() . '.view_penghuni_keluar',
            [
                'tenant' => $tenant,
                'applicationAttachmentAll' => $applicationAttachmentAll,
                'leaveOptionIdAll' => $leaveOptionIdAll ?? null,
                'tenantsLeaveAttachment' => $tenantsLeaveAttachment ?? null,
                'tenantsQuartersInventoryAll' => $tenantsQuartersInventoryAll ?? null,
                'leaveOptionDocumentAll' => $leaveOptionDocumentAll ?? null,
                'attachmentAll' => $attachmentAll,
                'inventoryStatus' => $inventoryStatus ?? null,
                'inventoryCondition' => $inventoryCondition ?? null,
                'monitoringLeaveStatus' => $monitoringLeaveStatus ?? null,
                'cdn' => getCdn(),
                'tab' => 'keluar'
            ]
        );
    }

    public function update_penghuni_keluar(Request $request)
    {
        $user    = auth()->user();
        $officer = $user->officer();

        $tqidAll = $request->tqi_id; //tenant quarters inventory id
        $tenant_id = $request->tenant_id; 
        $tenant = Tenant::find($tenant_id);

        DB::beginTransaction();

        try {

            foreach($tqidAll as $key => $tid) {

                $inventory_status = isset($request->inventory_status[$key]) ? $request->inventory_status[$key] : null;
                $condition = isset($request->condition[$key]) ? $request->condition[$key] : null;
                $quantity = isset($request->quantity[$key]) ? $request->quantity[$key] : null;

                $update = TenantQuartersInventory::where('id', $tid)
                ->update([
                    'monitoring_inventory_status_id' => $inventory_status,
                    'monitoring_inventory_condition_id' => $condition,
                    'monitoring_quantity' => $quantity,
                ]);
            }      

            $updateTenant = Tenant::where(['id'=> $tenant_id, 'data_status' => 1])->orderBy('id','desc')
                ->update([
                    'leave_status_id' => 2,
                    'action_on' => currentDate(),
                    'action_by' => $user->id,
                ]);


            $mtl = new MonitoringTenantLeave;
            $mtl->tenants_id = $request->tenant_id;
            $mtl->monitoring_date = currentDate();
            $mtl->monitoring_leave_status_id = $request->monitor_leave_status;
            $mtl->description = $request->remarks;
            $mtl->officer_id = $officer->id;
            $mtl->action_on = currentDate();
            $mtl->action_by = loginId();
            $mtl->save();
            // $mtl->refresh();

            if ($request->leave_attachment != null) {
                foreach ($request->leave_attachment  as $key => $file) {
                    $path = $file->store('documents/monitoring_tenant_leave', 'assets-upload');

                    $attachment = new MonitoringTenantLeaveAttachment();

                    $attachment->monitoring_tenants_leave_id = $mtl->id;
                    $attachment->path_document  = $path;
                    $attachment->data_status    = 1;
                    $attachment->action_by      = $user->id;
                    $attachment->action_on      = currentDate();

                    $saved = $attachment->save();
                }
            }

            $mtl->monitoring_officer->user->notify(new MonitoringTenantsLeaveNotification($tenant->new_ic, $tenant->quarters_category_id, $tenant->id));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('complaintMonitoring.index')->with('error', 'Pemantauan penghuni keluar tidak berjaya dihantar!');
        }
        return redirect()->route('complaintMonitoring.index')->with('success', 'Pemantauan penghuni keluar berjaya dihantar!');
    }


    public function update_aduan_berulang(ComplaintMonitoringPostRequest $request) //ADUAN AWAM SHJ
    {
        $complaint_id = $request->id;

        $monitoring = ComplaintMonitoring::where('data_status', 1)->where('complaint_id', $complaint_id)->first();
        $complaint  = Complaint::where('id', $complaint_id)->where('data_status', 1)->first();

        if($monitoring->monitoring_counter == 1) //pemantauan kedua
        {
            $monitoring -> monitoring_remarks_repeat = $request->remarks_repeat;
            $monitoring -> monitoring_counter        = 2 ;//kali kedua turun site

            if($request-> monitoring_status == 2 ) //pemantauan selesai
            {
                $complaint  -> complaint_status_id   = 3 ; // aduan selesai
            }
            else // pemantauan semula
            {
                $complaint  -> complaint_status_id   = 4 ; // pemeriksaan berulang
            }
        }
        else //pemantauan akhir
        {
            $monitoring -> monitoring_remarks_final = $request->remarks_final;
            $monitoring -> monitoring_counter        = 3 ; //kali ketiga turun site
            $complaint  -> complaint_status_id       = 3 ; // aduan selesai
        }

        $monitoring -> monitoring_status_id      = $request-> monitoring_status ;  //if pemantauan semula // else selesai
        $monitoring -> action_by                 = loginId();
        $monitoring -> action_on                 = currentDate();
        $saved = $monitoring ->save();

        $complaint  -> officer_id               = loginId();
        $complaint  -> officer_respond_on       = currentDate();
        $saved = $complaint ->save();

        if($request->monitoring_file != null)
        {
            foreach($request->monitoring_file  as $key => $file)
            {
                $path = $file->store('documents/complaint_monitoring', 'assets-upload');

                $monitoringAttachment = new ComplaintMonitoringAttachment;

                $monitoringAttachment->complaint_monitoring_id      = $monitoring->id;
                $monitoringAttachment->path_document                = $path;
                $monitoringAttachment-> monitoring_counter          = $monitoring->monitoring_counter;
                $monitoringAttachment->data_status                  = 1;
                $monitoringAttachment->action_by                    = loginId();
                $monitoringAttachment->action_on                    = currentDate();
                $saved = $monitoringAttachment->save();
            }
        }

        if(!$saved)
        {
            return redirect()->route('complaintMonitoring.index')->with('error', 'Maklumat Pemantauan Aduan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('complaintMonitoring.index')->with('success', 'Maklumat Pemantauan Aduan berjaya dikemaskini!');
        }
    }

    public function ajaxGetComplaintInventoryAttachmentList(Request $request)
    {
        $complaint_inventory_id = $request->cid;
        $flag_page = $request->flag;

        $complaintInventoryAttachment = ComplaintInventoryAttachment::select('complaint_inventory_attachment.path_document', 'complaint_inventory_attachment.complaint_inventory_id', 'complaint_inventory_attachment.id')
                ->where([
                    ['complaint_inventory_attachment.data_status', 1],
                    ['complaint_inventory_attachment.complaint_inventory_id', '=', $complaint_inventory_id]
                ])
                ->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.modal-complaint-inventory-attachment')
                ->with(compact('complaintInventoryAttachment','flag_page'))
                ->render();

        return response()->json(['success' => true, 'html' => $data]);
    }

    public function ajaxGetComplaintOthersAttachmentList(Request $request)
    {
        $complaint_others_id = $request->cod;
        $flag_page = $request->flag;

        $complaintAttachment = ComplaintAttachment::select('complaint_attachment.path_document', 'complaint_attachment.complaint_others_id', 'complaint_attachment.complaint_id', 'complaint_attachment.id')
                ->where([
                    ['complaint_attachment.data_status', 1],
                    ['complaint_attachment.complaint_others_id', '=', $complaint_others_id],
                ])
                ->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.modal-complaint-others-attachment')
                ->with(compact('complaintAttachment','flag_page'))
                ->render();

        return response()->json(['success' => true, 'html' => $data]);
    }
}
