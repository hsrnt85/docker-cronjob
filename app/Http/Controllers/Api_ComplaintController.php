<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Api\Api_Complaint;
use App\Models\Api\Api_ComplaintAppointment;
use App\Models\Api\Api_ComplaintAppointmentAttachment;
use App\Models\Api\Api_ComplaintAttachment;
use App\Models\Api\Api_ComplaintInventory;
use App\Models\Api\Api_ComplaintInventoryAttachment;
use App\Models\Api\Api_ComplaintMonitoring;
use App\Models\Api\Api_ComplaintOthers;
use App\Models\Api\Api_QuartersInventory;
use App\Models\Api\Api_ComplaintType;
use App\Models\Api\Api_Tenant;

class Api_ComplaintController extends Controller
{
    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Aduan > Aduan Selesai By ID & List 
    //-------------------------------------------------------------------------------------------------------------
    public function getCompletedComplaintListByTenants()
    {
        $userId = auth('sanctum')->user()->id;

        $allData = [];

        $complaintCompleteList = Api_Complaint::getCompletedComplaintList($userId, 'get');

        $complaintCompleteList->each(function ($complaint, $key) use (&$allData) {

            if ($complaint->complaint_type == 1) {

                $complaintDamage    = Api_Complaint::getComplaintById($complaint->id);
                $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
                $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');

                $complaintInventory->each(function ($comp_inventory, $key) {
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "";
                });

                $complaintOthers->each(function ($comp_others, $key) use ($complaint) {
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "";
                });

                $item = [];
                $item['complaint']              = $complaintDamage;
                $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : [];
                $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : [];
                $item['complaint_status']       = $complaintDamage->status->complaint_status;

            } else if ($complaint->complaint_type == 2) {

                $complaintViolation = Api_Complaint::getComplaintById($complaint->id);
                $attachment         = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

                $item = [];
                $item['complaint']                          = ($complaintViolation) ? $complaintViolation : "";
                $item['complaint_attachment']               = ($attachment) ? $attachment : "";
                $item['complaint_status']                   = $complaintViolation->status->complaint_status;
            }

            array_push($allData, $item);
        });

        return response()->json([
            'get_aduan_selesai' => $allData,
        ], 200);
    }

    public function getCompletedComplaintListById(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $complaintId = $request->complaintId;

        $aduanSelesai = Api_Complaint::getCompletedComplaintById($userId, $complaintId);

        if($aduanSelesai){

            if ($aduanSelesai->complaint_type == 1) {

                $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($aduanSelesai->id, 'get');
                $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId($aduanSelesai->id, 'get');

                $complaintInventory->each(function ($comp_inventory, $key) {
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "";
                });

                $complaintOthers->each(function ($comp_others, $key) use ($aduanSelesai) {
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $aduanSelesai->id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "";
                });

                return response()->json([
                    'get_aduan_selesai_by_id' => $aduanSelesai,
                    'complaint_inventory'     => $complaintInventory,
                    'complaint_others'        => $complaintOthers,
                    'complaint_status'        => $aduanSelesai->status?->complaint_status
                ], 200);


            } else if ($aduanSelesai->complaint_type == 2) {

                $attachment  = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($aduanSelesai->id, 'get');

                return response()->json([
                    'get_aduan_selesai_by_id' => $aduanSelesai,
                    'complaint_attachment'     => $attachment,
                    'complaint_status'        => $aduanSelesai->status?->complaint_status
                ], 200);
            }
        }

        return response()->json([
            'get_aduan_selesai_by_id' => null
        ], 200);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Aduan > Aduan Baru By ID & List 
    //-------------------------------------------------------------------------------------------------------------

    public function getActiveComplaintListByTenants()
    {
        $userId = auth('sanctum')->user()->id;

        $allData = [];

        $complaintActiveList   = Api_Complaint::getActiveComplaintList($userId);

        $complaintActiveList->each(function ($complaint, $key) use (&$allData) {

            if ($complaint->complaint_type == 1) {

                $complaintDamage = Api_Complaint::getComplaintById($complaint->id);
                $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
                $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');

                $complaintInventory->each(function ($comp_inventory, $key) {
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "";
                });

                $complaintOthers->each(function ($comp_others, $key) use ($complaint) {
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "";
                });

                $item = [];
                $item['complaint']              = ($complaintDamage) ? $complaintDamage : [];
                $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : [];
                $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : [];
                $item['complaint_status']       = $complaintDamage->status?->complaint_status;
            } else if ($complaint->complaint_type == 2) {

                $complaintViolation   = Api_Complaint::getComplaintById($complaint->id);
                $attachment           = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

                $item = [];
                $item['complaint']               = ($complaintViolation) ? $complaintViolation : "";
                $item['complaint_attachment']    = ($attachment) ? $attachment : "";
                $item['complaint_status']        = $complaintViolation->status?->complaint_status;
            }

            array_push($allData, $item);
        });

        return response()->json([
            'active_complaint_list' => $allData,
        ], 200);
    }

    public function getActiveComplaintListById(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $complaintId = $request->complaintId;

        $complaint   = Api_Complaint::getActiveComplaintById($userId , $complaintId);

        if($complaint){

            if ($complaint->complaint_type == 1) {

                $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
                $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');

                $complaintInventory->each(function ($comp_inventory, $key) {
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "";
                });

                $complaintOthers->each(function ($comp_others, $key) use ($complaint) {
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "";
                });

                return response()->json([
                    'active_complaint_list_by_id' => $complaint,
                    'complaint_inventory' => $complaintInventory,
                    'complaint_others' => $complaintOthers,
                    'complaint_status' => $complaint->status?->complaint_status
                ], 200);

            } else if ($complaint->complaint_type == 2) {

                $attachment           = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

                return response()->json([
                    'active_complaint_list_by_id' => $complaint,
                    'complaint_attachment' => $attachment,
                    'complaint_status' => $complaint->status?->complaint_status
                ], 200);
            }
        }else{
            return response()->json([
                'active_complaint_list_by_id' => null,
            ], 200);
        }

    }

    //--------------------------------------------------------------------------------------------------------------
    // Penghuni : Aduan > Aduan ditolak By ID & List 
    //-------------------------------------------------------------------------------------------------------------
    public function getRejectedComplaintListByTenants()
    {
        $userId = auth('sanctum')->user()->id;
        $allData = [];
        $complaintRejectedList = Api_Complaint::getRejectedComplaint($userId, 'get');

        $complaintRejectedList->each(function ($complaint, $key) use (&$allData) {

            if ($complaint->complaint_type == 1) {

                $inspectionAttachment = [];

                $rejectedById       = Api_Complaint::getComplaintById($complaint->id);
                $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
                $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');
                $inspection         =  Api_ComplaintAppointment::getlatestAppointmentByComplaintId($complaint->id); //Sebab pemantauan ditolak
                if($inspection){ $inspectionAttachment = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($inspection->id , 'get');}

                $complaintInventory->each(function ($comp_inventory, $key) {

                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "";
                });

                $complaintOthers->each(function ($comp_others, $key) use ($complaint) {

                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "";
                });

                $item = [];
                $item['complaint']              = ($rejectedById) ? $rejectedById : [];
                $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : [];
                $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : [];
                $item['complaint_status']       = $rejectedById->status->complaint_status;
                $item['inspection_remarks']     = ($inspection) ? $inspection->monitoring_remarks : "";
                $item['inspection_attachment']  = ($inspection) ? $inspectionAttachment : [];

            } else if ($complaint->complaint_type == 2) {

                $complaintViolation   = Api_Complaint::getComplaintById($complaint->id);
                $attachment           = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

                $item = [];
                $item['complaint']               = ($complaintViolation) ? $complaintViolation : "";
                $item['complaint_attachment']    = ($attachment) ? $attachment : "";
                $item['inspection_remarks']      = ($complaintViolation) ? $complaintViolation->remarks : "";
                $item['complaint_status']        = $complaintViolation->status?->complaint_status;
            }

            array_push($allData, $item);
        });

        return response()->json([
            'get_aduan_ditolak' => $allData,
        ], 200);
    }

    public function getRejectedComplaintListById(Request $request)
    {

        $userId = auth('sanctum')->user()->id;
        $complaintId = $request->complaintId;

        $complaint = Api_Complaint::getRejectedComplaintById($userId, $complaintId);

        if($complaint){

            if ($complaint->complaint_type == 1) {

                $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
                $complaintOthers    = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');
                $inspection         =  Api_ComplaintAppointment::getlatestAppointmentByComplaintId($complaint->id); //Sebab pemantauan ditolak
                if($inspection){ $inspectionAttachment = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($inspection->id , 'get');}

                $complaintInventory->each(function ($comp_inventory, $key) {
                    $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                    $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "";
                });

                $complaintOthers->each(function ($comp_others, $key) use ($complaint) {
                    $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                    $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "";
                });

                return response()->json([
                    'get_aduan_ditolak_by_id' => $complaint,
                    'complaint_inventory' => $complaintInventory,
                    'complaint_others' => $complaintOthers,
                    'complaint_status' => $complaint->status?->complaint_status,
                    'inspection_remarks' =>($inspection) ? $inspection->monitoring_remarks : "",
                    'inspection_attachment' => ($inspection) ? $inspectionAttachment : ""
                ], 200);

            } else if ($complaint->complaint_type == 2) {

                $attachment = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

                return response()->json([
                    'get_aduan_ditolak_by_id' => $complaint,
                    'complaint_attachment' => $attachment,
                    'complaint_status' => $complaint->status?->complaint_status,
                ], 200);
            }
        }

        return response()->json([
            'get_aduan_ditolak_by_id' => null,
        ], 200);
    }
}
