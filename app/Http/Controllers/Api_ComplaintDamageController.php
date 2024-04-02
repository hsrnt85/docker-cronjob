<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Api\Api_Complaint;
use App\Models\Api\Api_ComplaintAppointment;
use App\Models\Api\Api_ComplaintAttachment;
use App\Models\Api\Api_ComplaintInventory;
use App\Models\Api\Api_ComplaintInventoryAttachment;
use App\Models\Api\Api_ComplaintOthers;
use App\Models\Api\Api_QuartersInventory;
use App\Models\Api\Api_ComplaintType;
use App\Models\Api\Api_Tenant;
use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use Illuminate\Http\Request;
use App\Notifications\ComplaintNotification;
use App\Notifications\AppointmentApprovalNotification;
use App\Notifications\ComplaintAppointmentNotification;

class Api_ComplaintDamageController extends Controller
{

    public function getInventoryByUser(Request $request)
    {
        $user = auth('sanctum')->user();

        $tenantData = Api_Tenant::getLatestActiveTenantByUserId($user->id);

        $inventoryData = Api_QuartersInventory::getInventoryByQuarters($tenantData?->quarters_id);

        $filteredInventoryData = $this->_filterInventoryDataForView($inventoryData);

        return response()->json([
            'inventoryData' => $filteredInventoryData
        ], 200);
    }

    private function _filterInventoryDataForView($inventories)
    {
        $filteredData = [];

        $inventories->each(function($inventory, $index) use (&$filteredData){
            $item['id']         = $inventory->inventory->id;
            $item['name']       = $inventory->inventory->name;
            $item['quantity']   = $inventory->quantity;
            $item['quarters_id']   = $inventory->q_id;

            array_push($filteredData, $item);
        });

        return $filteredData;
    }

    //------------------------------------COMPLAINT --------------------------------------------

    public function countNewComplaintDamage()
    {
        $count = Api_Complaint::select('id', 'ref_no')->where(['complaint_status_id' => 0, 'data_status' => 1, 'complaint_type' => 1])->count();

        return response()->json([
            'count' => $count,
        ], 200);
    }


    public function getPendingComplaintDamageDetailsById(Request $request) // done s
    {
        $complaintId = $request->complaint_id;
        $pendingComplaint = Api_Complaint::getComplaintDamageDetailsById($complaintId);
        $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaintId, 'get');
        $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaintId, 'get');

        $complaintInventory->each(function($comp_inventory, $key){

            $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
            $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
        });

        $complaintOthers->each(function($comp_others, $key) use($complaintId){

            $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaintId, 'get');
            $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;

        });

        if($pendingComplaint)
        {
            return response()->json([
                'complaint_details_model' => $pendingComplaint,
                'complaint_inventory' => $complaintInventory,
                'complaint_others' => $complaintOthers,
                'complaint_status' => $pendingComplaint->status->complaint_status ?? "",

            ], 200);
        }
        else
        {
            return response()->json([
                'complaint_details_model' => $pendingComplaint,
            ], 200);
        }
    }

    public function getActiveComplaintDamageDetailsById(Request $request) // done s
    {
        $complaintId = $request->complaint_id;
        $activeComplaint = Api_Complaint::getComplaintAppointmentDetailsById($complaintId);
        $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaintId, 'get');
        $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaintId, 'get');

        $complaintInventory->each(function($comp_inventory, $key){

            $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
            $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
        });

        $complaintOthers->each(function($comp_others, $key) use($complaintId){

            $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaintId, 'get');
            $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;

        });

        if($activeComplaint){
            return response()->json([
                'complaint_appointment_details_model' => $activeComplaint,
                'complaint_inventory' => $complaintInventory,
                'complaint_others' => $complaintOthers,
                'complaint_status' => $activeComplaint->status->complaint_status,
            ], 200);
        }
        else{
            return response()->json([
                'complaint_appointment_details_model' => $activeComplaint,
            ], 200);
        }
    }


    public function getCompletedComplaintDamageDetailsById(Request $request) // done s
    {
        $id = $request->complaint_id;
        $completedComplaint = Api_Complaint::getCompletedComplaintDamageInspectionById($id);
        $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($id, 'get');
        $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($id, 'get');

        $complaintInventory->each(function($comp_inventory, $key){

            $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
            $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
        });

        $complaintOthers->each(function($comp_others, $key) use($id){

            $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $id, 'get');
            $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;

        });

        if($completedComplaint){
            return response()->json([
                'history_complaint_details_model' => $completedComplaint,
                'complaint_inventory' => $complaintInventory,
                'complaint_others' => $complaintOthers,
                'complaint_status' => $completedComplaint->status?->complaint_status,
            ], 200);
        }else{
            return response()->json([
                'history_complaint_details_model' => $completedComplaint,
            ], 200);
        }

    }

    // ADUAN > ADUAN BARU
    public function getPendingComplaintDamageList(Request $request)  //xx
    {
        $start  = $request->startDate;
        $end    = $request->endDate;

        $userId = auth('sanctum')->user()->id;

        $allData = [];

        $pendingDamageList   = Api_Complaint::getPendingComplaintDamageList($userId, $start, $end, 'get');

        $pendingDamageList->each(function($complaint, $key) use (&$allData){

            $pendingById = Api_Complaint::getComplaintDamageDetailsById($complaint->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $complaint->id,'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId( $complaint->id,'get');

            $complaintInventory->each(function($comp_inventory, $key){

                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaint){

                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

        $item = [];
        $item['complaint']              = ($pendingById) ? $pendingById:[];
        $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : [];
        $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : [];
        $item['complaint_status']       = $pendingById->status->complaint_status;

        array_push( $allData, $item);

        });

        return response()->json([
            'history_complaint_details_model' => $allData,
        ], 200);
    }

    // public function getActiveComplaintDamageList(Request $request) //done s
    // {
    //     $start  = $request->startDate;
    //     $end    = $request->endDate;

    //     $allData = [];

    //     $activeDamageList   = Api_Complaint::getActiveComplaintDamageList($start, $end, 'get');

    //     $activeDamageList->each(function($complaint, $key) use (&$allData){

    //         $activeComplaint = Api_Complaint::getComplaintAppointmentDetailsById($complaint->id);
    //         $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $complaint->id,'get');
    //         $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId( $complaint->id,'get');

    //         $complaintInventory->each(function($comp_inventory, $key){

    //             $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
    //             $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
    //         });

    //         $complaintOthers->each(function($comp_others, $key) use($complaint){

    //             $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
    //             $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
    //         });

    //         //--------------------------------------------------------------------------------------------------------------------
    //         $item = [];
    //         $item['complaint']              = ($activeComplaint) ? $activeComplaint:[];
    //         $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : [];
    //         $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : [];
    //         $item['complaint_status']       = $activeComplaint->status?->complaint_status;

    //         array_push( $allData, $item);
    //     });

    //     return response()->json([
    //         'history_complaint_details_model' => $allData,
    //     ], 200);
    // }

    // public function getCompletedComplaintDamageList(Request $request)
    // {
    //     $start  = $request->startDate;
    //     $end    = $request->endDate;
    //     $userId = auth('sanctum')->user()->id;

    //     $allData = [];

    //     $completedDamageList   = Api_Complaint::getCompletedComplaintDamageList($userId, $start, $end, 'get');

    //     $completedDamageList->each(function($complaint, $key) use (&$allData){

    //         $completedDamageById = Api_Complaint::getCompletedComplaintDamageInspectionById($complaint->id);
    //         $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $complaint->id,'get');
    //         $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId( $complaint->id,'get');

    //         $complaintInventory->each(function($comp_inventory, $key){

    //             $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
    //             $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
    //         });

    //         $complaintOthers->each(function($comp_others, $key) use($complaint){

    //             $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
    //             $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
    //         });

    //         //--------------------------------------------------------------------------------------------------------------------
    //         $item = [];
    //         $item['complaint']              = $completedDamageById;
    //         $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
    //         $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";
    //         $item['complaint_status']       = $completedDamageById->status->complaint_status;

    //         array_push( $allData, $item);
    //     });

    //     return response()->json([
    //         'history_complaint_details_model' => $allData,
    //     ], 200);
    // }

    public function submitComplaintDamageForm(Request $request)
    {
        // DB::beginTransaction();
        $tenants = Api_Tenant::checkTenant();

        if($tenants == null)
        {
            return response()->json([
                'status' => false,
                'message' => "Penghuni Tidak Aktif / Tidak Wujud!",
            ], 200);
        }
        else
        {
            try{

                $complaint_type = 1;//1-Aduan Kerosakan
                $complaint_running_no = Api_Complaint::select('complaint.running_no', 'complaint_type.ref_no')
                ->join('complaint_type', 'complaint.complaint_type', '=', 'complaint_type.id')
                ->where('complaint_type', $complaint_type)
                ->orderBy('running_no', 'DESC')->first();

                $district_code = GetData::Api_District(districtId())->district_code;

                if($complaint_running_no == null){
                    $running_no_new = str_pad(1, 6, "0", STR_PAD_LEFT);
                    $complaint_running_no_new = Api_ComplaintType::where([['complaint_type.id', $complaint_type],['data_status', 1]])->first();
                    $ref_no_new = $complaint_running_no_new->ref_no.$district_code.date('y').date('m').$running_no_new;
                }
                else{
                    $running_no_new = str_pad($complaint_running_no->running_no+1, 6, "0", STR_PAD_LEFT);
                    $ref_no_new = $complaint_running_no->ref_no.$district_code.date('y').date('m').$running_no_new;
                }

                $complaint                          = new Api_Complaint;
                $complaint->ref_no                  = $ref_no_new;
                $complaint->running_no              = $running_no_new;
                $complaint->users_id                = loginId();
                $complaint->complaint_date          = currentDateDb();
                $complaint->district_id             = districtId();
                $complaint->quarters_id             = $tenants?->quarters_id;
                $complaint->complaint_type          = $complaint_type; //1-Aduan Kerosakan
                $complaint->complaint_status_id     = 0;
                $complaint->action_by               = loginId();
                $complaint->action_on               = currentDate();
                $saved = $complaint->save();

                // Complaint Inventory  ---------------------
                $inventoryChecked =  isset($request->inventoryCheck) ? $request->inventoryCheck: "";

                if ($inventoryChecked)
                {
                    foreach($inventoryChecked as $i => $inventory)
                    {
                        if($inventory != null || $inventory !=  "" )
                        {
                            $ComplaintInventory                   = new Api_ComplaintInventory;
                            $ComplaintInventory->complaint_id     = $complaint->id;
                            $ComplaintInventory->inventory_id     = $request->inventory_id[$i];
                            $ComplaintInventory->description      = $request->inventory_damage[$i];
                            $ComplaintInventory->action_by        = loginId();
                            $ComplaintInventory->action_on        = currentDate();
                            $ComplaintInventory->save();

                            $inventory_file = isset($request->inventory_file[$i]) ? $request->inventory_file[$i] : "";

                            if($inventory_file)
                            {
                                foreach($inventory_file as $file)
                                {
                                    $path = $file->store('documents/complaint/damage_inventory', 'assets-upload');

                                    $ComplaintInventoryAttachment                          = new Api_ComplaintInventoryAttachment;
                                    $ComplaintInventoryAttachment->complaint_inventory_id  = $ComplaintInventory->id;
                                    $ComplaintInventoryAttachment->path_document           = $path;
                                    $ComplaintInventoryAttachment->action_by               = loginId();
                                    $ComplaintInventoryAttachment->action_on               = currentDate();
                                    $ComplaintInventoryAttachment->save();
                                }
                            }
                        }
                    }
                }

                //Other Complaint--------------------
                $other_complaints =  isset($request->complaint_description) ? $request->complaint_description: "";

                if($other_complaints)
                {
                    foreach($other_complaints as $k => $description){

                        if($description != null || $description !=  "" )
                        {
                            $ComplaintOthers = new Api_ComplaintOthers;
                            $ComplaintOthers->complaint_id      = $complaint->id;
                            $ComplaintOthers->description       = $description;
                            $ComplaintOthers->action_by         = loginId();
                            $ComplaintOthers->action_on         = currentDate();
                            $ComplaintOthers->save();

                            $complaint_attachment = isset($request->complaint_attachment[$k]) ? $request->complaint_attachment[$k]: "";

                            if($complaint_attachment  && is_array($complaint_attachment))
                            {
                                foreach($complaint_attachment as $attachment) {

                                    $path = $attachment->store('documents/complaint/damage_others', 'assets-upload');

                                    $ComplaintAttachment                        = new Api_ComplaintAttachment;
                                    $ComplaintAttachment->complaint_id          = $complaint->id;
                                    $ComplaintAttachment->complaint_others_id   = $ComplaintOthers->id;
                                    $ComplaintAttachment->path_document         = $path;
                                    $ComplaintAttachment->action_by             = loginId();
                                    $ComplaintAttachment->action_on             = currentDate();
                                    $ComplaintAttachment->save();
                                }
                            }
                        }
                    }
                }
                // DB::commit();

                //SEND NOTIFICATION TO ADMIN
                $complaint_ref_no = $ref_no_new;
                $complaint_id = $complaint->id;
                $officerPemantau = ListData::Officer(districtId(),4);
                $officerPemantau ->each(function($officer, $key) use($complaint_type, $complaint_ref_no, $complaint_id){
                    $officer->user?->notify(new ComplaintNotification($complaint_type, $complaint_ref_no, $complaint_id));
                });

                return response()->json([
                    'status' => true,
                    'message' => "Aduan Kerosakan Berjaya Ditambah!",
                    'complaint' => $complaint,
                ], 200);


            } catch (\Exception $e) {
                // DB::rollback();

                // something went wrong
                return response()->json([
                    'status'    => false,
                    'message'   => "Aduan Kerosakan Tidak Berjaya Ditambah!" . ' on line ' . $e->getLine(). ' ' . $e->getMessage(),
                ], 500);
            }

        }

    }

    // TEMUJANJI > PENGESAHAN TEMUJANJI !
    public function confirmComplaintAppointment(Request $request)
    {

        $complaintAppointment = Api_ComplaintAppointment::select('id','complaint_id', 'appointment_status_id', 'data_status')->where(['id' => $request->appointment_id, 'data_status' => 1])->first();
        $complaint = Complaint::select('id', 'ref_no', 'officer_id', 'data_status')->where('id', $complaintAppointment->complaint_id)->first();

            if( $complaintAppointment && ($request->appointment_status != null))
            {
                    $complaintAppointment->appointment_status_id      = $request->appointment_status;
                    $complaintAppointment->tenants_cancel_remarks_id  = ($request->appointment_status == 2) ? $request->tenants_cancel_remarks_id : null;
                    $complaintAppointment->tenant_respond_on          = currentDate();
                    $complaintAppointment->save();

                    $flag_proses = 1; //update
                    $complaint->officer_Api->notify(new AppointmentApprovalNotification($complaint->ref_no, $complaintAppointment->complaint_id, $complaintAppointment->appointment_status_id, $flag_proses, $complaint->id));

                    return response()->json([
                        'status' => true,
                        'message' => "Temujanji Aduan Berjaya Disimpan!",
                    ], 200);
            }
            else
            {
                return response()->json([
                    'status'    => false,
                    'message'   => "Temujanji Aduan Tidak Berjaya Disimpan!",
                ], 500);
            }

    }

    //penguatkuasa
    public function getComplaintAppointmentList(Request $request)
    {
        $start  = $request->startDate;
        $end    = $request->endDate;
        $allData = [];

        // $listAppointment = Api_ComplaintAppointment::leftJoin('complaint','complaint.id', '=', 'complaint_appointment.complaint_id')->with('complaint')->whereDate('complaint_appointment.appointment_date', '>=', $start)
        // ->whereDate('complaint_appointment.appointment_date', '<=', $end)->get();

        $listAppointment = Api_ComplaintAppointment::whereDate('appointment_date', '>=', $start)
        ->whereDate('appointment_date', '<=', $end)->get();

        $listAppointment->each(function($appointment, $key) use (&$allData){

            $appointmentBy      = Api_ComplaintAppointment::getAllAppointmentById($appointment->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            $appointmentStatus ="";

            if ($appointment->appointment_datastatus == 1) {
                if($appointment->appointment_status_id){
                    $appointmentStatus =  ($appointment->appointment_status_id == 1) ? "Setuju" : "Tidak Setuju";
                }else{
                        $appointmentStatus = "Baru";
                    }
            } else if ($appointment->appointment_datastatus == 2){
                $appointmentStatus = "Batal";
            }

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }
            $item = [];
            $item['appointment']            = $appointmentBy;
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";
            $item['appointment_status']     = ($appointmentStatus) ? $appointmentStatus : "";

            array_push( $allData, $item);
        });

        return response()->json([
            'list_appointment'   =>  $allData,
        ], 200);

    }

    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Temujanji > Temujanji Baru By ID & List
    //-------------------------------------------------------------------------------------------------------------
    public function getPendingAppointmentListByTenants()
    {
        $userId = auth('sanctum')->user()->id;
        $allData = [];

        $listAppointment = Api_ComplaintAppointment::getPendingAppointmentByTenants($userId, 'get');

        $listAppointment->each(function($appointment, $key) use (&$allData, $userId){

            $appointment        = Api_ComplaintAppointment::getPendingAppointmentById( $userId, $appointment->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }

            $item = [];
            $item['appointment']            = $appointment;
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";

            array_push( $allData, $item);
        });

        return response()->json([
            'list_appointment'   =>  $allData,
        ], 200);
    }

    public function getPendingAppointmentListById(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $appmnt_id = $request->appointmentId;

        $appointment = Api_ComplaintAppointment::getPendingAppointmentById($userId, $appmnt_id);

        if($appointment){

            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }
            return response()->json([
                'pending_appointment_by_id'   =>  $appointment,
                'complaint_inventory'   =>  $complaintInventory,
                'complaint_others'   =>  $complaintOthers,
            ], 200);

        }else{

            return response()->json([
                'pending_appointment_by_id' =>  null,
            ], 200);
        }
    }

    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Temujanji > Senarai Temujanji By ID & List
    //-------------------------------------------------------------------------------------------------------------
    public function getComplaintAppointmentListByTenants()
    {
        $userId = auth('sanctum')->user()->id;
        $allData = [];

        $listAppointment = Api_ComplaintAppointment::getLatestAppointmentByTenants($userId);

        $listAppointment->each(function($appointment, $key) use (&$allData){

            $appointmentById        = Api_ComplaintAppointment::getAppointmentById($appointment->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }

            if($appointmentById->appointment_ds == 2){
                $status = 'BATAL - Menunggu Temujanji Baru daripada Pegawai';
            }else{
                if($appointment->complaint_status_id == 0){
                    $status = $appointmentById->appointment_status;
                }else{
                    $status = $appointment->complaint?->status?->complaint_status;
                }
            }

            $item = [];
            $item['appointment']            = $appointmentById;
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";
            $item['complaint_status']       = ($status) ? $status : "";

            array_push( $allData, $item);
        });

        return response()->json([
            'list_appointment'   =>  $allData,
        ], 200);
    }

    public function getComplaintAppointmentListById(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $appmnt_id = $request->appointmentId;

        $appointment = Api_ComplaintAppointment::getLatestAppointmentById($userId, $appmnt_id);

        if($appointment){
            // $appointmentId       = Api_ComplaintAppointment::getAppointmentById($appointment->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }

            $cancel_reason = "";  $cancel_by = ""; $appointment_status ="";
            if($appointment->appointment_ds == 2) {
                $cancel_reason = ( $appointment->tenants_remarks?->remarks) ?  ($appointment->tenants_remarks?->remarks) : $appointment->cancel_remarks;
                $cancel_by = ($appointment->delete_by) ? $appointment->delete_name?->name : '-';
                $appointment_status = 'BATAL - Menunggu Temujanji Baru daripada Pegawai';
            }else{
                if($appointment->appointment_status_id == 2){
                    $cancel_reason = $appointment->tenants_remarks?->remarks;
                    $cancel_by = ($appointment->delete_by) ? $appointment->delete_name?->name : '-';
                    $appointment_status = $appointment->status_appointment?->appointment_status;
                }else  if($appointment->appointment_status_id == 1){
                    $appointment_status = $appointment->status_appointment?->appointment_status;
                    $cancel_by = ($appointment->delete_by) ? $appointment->delete_name?->name : '-';
                }
            }


            return response()->json([
                'list_appointment_by_id'   =>  $appointment,
                'complaint_inventory'    =>  $complaintInventory,
                'complaint_others'     =>  $complaintOthers,
                'appointment_status' => $appointment_status,
                'cancel_reason'       =>  $cancel_reason,
                'cancel_by'          =>  $cancel_by,

            ], 200);
        }else{

            return response()->json([
                'list_appointment_by_id'   =>  null,
            ], 200);
        }
    }

    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Temujanji > Senarai Batal Temujanji By ID & List
    //-------------------------------------------------------------------------------------------------------------
    public function getCancelAppointmentListByTenants()
    {
        $userId = auth('sanctum')->user()->id;
        $allData = [];

        $listAppointment = Api_ComplaintAppointment::getCancelAppointmentList($userId);

        $listAppointment->each(function($appointment, $key) use (&$allData){

            $appointment        = Api_ComplaintAppointment::getCancelAppointmentById($appointment->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }

            $item = [];
            $item['appointment']            = $appointment;
            $item['appointment_status']     = 'BATAL';
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";

            array_push( $allData, $item);
        });

        return response()->json([
            'get_cancel_appointment'   =>  $allData,
        ], 200);
    }

    public function getCancelAppointmentListById(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $appmnt_id = $request->appointmentId;

        $appointment = Api_ComplaintAppointment::getCancelAppointmentListById($userId, $appmnt_id);

        if($appointment){

            // $appointment        = Api_ComplaintAppointment::getCancelAppointmentById($appointment->id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId( $appointment?->complaint_id,'get');
            $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId( $appointment?->complaint_id,'get');

            if($complaintInventory){
                $complaintInventory->each(function($comp_inventory, $key){
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
                });
            }

            if($complaintOthers){
                $complaintOthers->each(function($comp_others, $key) use($appointment){
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $appointment->complaint_id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
                });
            }

            $cancel_reason = "";  $cancel_by = "";
            if($appointment->data_status == 2) {
                $cancel_reason = ( $appointment->tenants_remarks?->remarks) ?  ($appointment->tenants_remarks?->remarks) : $appointment->cancel_remarks;
                $cancel_by = ($appointment->delete_by) ? $appointment->delete_name?->name : '-';
            }
            return response()->json([
                'get_cancel_appointment_by_id'  =>  $appointment,
                'appointment_status'            =>  'BATAL',
                'cancel_reason'                 =>  $cancel_reason,
                'cancel_by'                     =>  $cancel_by,
                'complaint_inventory'           =>  $complaintInventory,
                'complaint_others'              =>  $complaintOthers,
            ], 200);
        }

        return response()->json([
            'get_cancel_appointment_by_id'   =>  null,
        ], 200);
    }

    public function submitComplaintAppointment(Request $request)
    {
        $complaintId = $request->complaint_id;

        $appointment = new Api_ComplaintAppointment;
        $appointment->complaint_id                 = $complaintId;
        $appointment->appointment_date             = $request->appointment_date;
        $appointment->appointment_time             = $request->appointment_time;
        $appointment->data_status                  = 1;
        $appointment->action_by                    = loginId();
        $appointment->action_on                    = currentDate();

        $saved = $appointment->save();

        Api_Complaint::where('id', $complaintId)->where('data_status', 1)->update([ 'officer_id'   => loginId(), 'officer_respond_on' => currentDate() ]);

        $flag_proses = "store";
        $complaint = Complaint::select('id','ref_no')->where('id', $appointment->complaint_id)->where('data_status', 1)->first();

        $complaint->user?->notify(new ComplaintAppointmentNotification($complaint->ref_no, $flag_proses, $appointment->id));

        if($saved)
        {
            return response()->json([
                'status' => true,
                'message' => "Temujanji Aduan Berjaya Dihantar!",

            ], 200);

        }
        else
        {
            return response()->json([
                'status'    => false,
                'message'   => "Temujanji Aduan Tidak Berjaya Dihantar!",
            ], 500);
        }
    }
}
