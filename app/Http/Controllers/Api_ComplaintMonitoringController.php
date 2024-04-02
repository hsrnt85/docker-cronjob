<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Api\Api_Complaint;
use App\Models\Api\Api_ComplaintInventory;
use App\Models\Api\Api_ComplaintInventoryAttachment;
use App\Models\Api\Api_ComplaintOthers;
use App\Models\Api\Api_ComplaintAttachment;
use App\Models\Api\Api_ComplaintAppointmentAttachment;
use App\Models\Api\Api_ComplaintAppointment;
use App\Models\Api\Api_ComplaintMonitoring;
use App\Models\Api\Api_ComplaintMonitoringAttachment;
use App\Models\Api\Api_Inventory;
use App\Notifications\ComplaintMonitoringNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Api_ComplaintMonitoringController extends Controller
{
    public function countComplaintDamageInspectionCompletedByUserId(Request $request)
    {
        $userId  =  auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $countCompletedDamage = Api_Complaint::getComplaintDamageCompleteMonitor($userId, $start, $end, 'count');

        return response()->json([
            'count' => $countCompletedDamage,
        ], 200);
    }

    public function countComplaintViolationInspectionCompletedByUserId(Request $request)
    {
        $userId   =  auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $countCompletedViolation = Api_ComplaintMonitoring::getCompletedMonitoring($userId, $start, $end, 'count');

        return response()->json([
            'count' => $countCompletedViolation,
        ], 200);
    }

    public function countComplaintDamageInspectionPendingByUserId(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $countPendingDamage = Api_Complaint::getComplaintDamagePendingMonitor($userId, $start, $end, 'count');

        return response()->json([
            'count' => $countPendingDamage,
        ], 200);
    }

    public function countComplaintViolationInspectionPendingByUserId(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $countPendingViolation = Api_Complaint::getComplaintViolationPendingMonitor($userId, $start, $end, 'count');

        return response()->json([
            'count' => $countPendingViolation,
        ], 200);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Pemantauan > Aduan Baru !
    //--------------------------------------------------------------------------------------------------------------
    public function getComplaintDamageInspectionPendingMenuByUserId(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;
        $allData =[];
        $pemantauanPendingAll = Api_Complaint::getComplaintDamagePendingMonitor($userId, $start, $end, 'get');

        $pemantauanPendingAll->each(function($complaint, $key) use (&$allData, $userId){

            $pendingById   = Api_Complaint::getComplaintDamageById($complaint->id, $complaint->appointment_id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');

            $complaintInventory->each(function($comp_inventory, $key){
                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaint){
                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

            $item = [];
            $item['complaint']  = ($pendingById) ? $pendingById : "";
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";

            array_push($allData, $item);
        });

        return response()->json([
            'get_pemantauan_kerosakan_baru' => $allData,
        ], 200);
    }

    public function getComplaintViolationInspectionPendingMenuByUserId(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;
        $allData =[];
        $pemantauanPendingAll = Api_Complaint::getComplaintViolationPendingMonitor($userId, $start, $end, 'get');

        $pemantauanPendingAll->each(function($complaint, $key) use (&$allData, $userId){

            $pemantauanPendingbyId   = Api_Complaint::getComplaintById($complaint->id, $userId);
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

            $item = [];
            $item['complaint']                          = ($pemantauanPendingbyId) ? $pemantauanPendingbyId : "";
            $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";
            array_push($allData, $item);
        });

        return response()->json([
            'get_pemantauan_awam_baru' => $allData,
        ], 200);
    }

    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Selesai List
    //--------------------------------------------------------------------------------------------------------------
    public function getComplaintDamageInspectionCompletedMenuByUserId(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $allData = [];

        $pemantauanSelesaiAll   = Api_Complaint::getComplaintDamageCompleteMonitor($userId, $start, $end, 'get');

        $pemantauanSelesaiAll->each(function($complaint, $key) use (&$allData,  $userId){

            $pemantauanSelesaibyId   = Api_Complaint::getComplaintDamageCompleteMonitorById($complaint->id, $userId);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');
            $latestAppointment = Api_ComplaintAppointment::getlatestAppointmentByComplaintId($complaint->id);
            $complaintAppointmentAttach = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($latestAppointment->id, 'get');

            $complaintInventory->each(function($comp_inventory, $key){
                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaint){
                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

            $item = [];
            $item['complaint']  = ($pemantauanSelesaibyId) ? $pemantauanSelesaibyId : "";
            $item['complaint_appointment_attachment']  = ($complaintAppointmentAttach) ? $complaintAppointmentAttach : "";
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";

            array_push($allData, $item);

        });

        return response()->json([
            'get_pemantauan_kerosakan_selesai' => $allData,
        ], 200);
    }

    public function getComplaintViolationInspectionCompletedMenuByUserId(Request $request)
    {
        $userId   = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $allData = [];

        $pemantauanSelesaiAll   = Api_ComplaintMonitoring::getCompletedMonitoring($userId, $start, $end, 'get');

        $pemantauanSelesaiAll->each(function($pemantauan, $key) use (&$allData, $userId){

            $pemantauanSelesaibyId   = Api_ComplaintMonitoring::getCompletedMonitoringById($pemantauan->id, $userId);
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($pemantauan->complaint_id, 'get');
            $complaintMonitoringAttach = Api_ComplaintMonitoringAttachment::getMonitoringAttachmentbyMonitoringId($pemantauan->id);

            $item = [];
            $item['complaint_monitoring']               = ($pemantauanSelesaibyId) ? $pemantauanSelesaibyId : "";
            $item['complaint_monitoring_attachment']    = ($complaintMonitoringAttach) ? $complaintMonitoringAttach : "";
            $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";

            array_push($allData, $item);
        });

        return response()->json([
            'get_pemantauan_awam_selesai' => $allData,
        ], 200);
    }

    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Berulang List
    //--------------------------------------------------------------------------------------------------------------
    public function getComplaintViolationInspectionActiveMenuByUserId(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;
        $allData = [];

        $pemantauanActiveAll = Api_ComplaintMonitoring::getPemantauanBerulang($userId, $start, $end, 'get');

        $pemantauanActiveAll->each(function($monitor, $key) use (&$allData, $userId){

            $pemantauanActivebyId   = Api_ComplaintMonitoring::getActiveMonitoringById($monitor->id, $userId);
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($monitor->complaint_id, 'get');
            $complaintMonitoringAttach = Api_ComplaintMonitoringAttachment::getMonitoringAttachmentbyMonitoringId($monitor->id);

            $item = [];
            $item['complaint_monitoring']               = ($pemantauanActivebyId) ? $pemantauanActivebyId : "";
            $item['complaint_monitoring_attachment']    = ($complaintMonitoringAttach) ? $complaintMonitoringAttach : "";
            $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";


            array_push($allData, $item);
        });


        return response()->json([
            'get_pemantauan_awam_berulang' => $allData,
        ], 200);

    }

    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Berulang By Id
    //--------------------------------------------------------------------------------------------------------------
    public function getComplaintViolationInspectionActiveById(Request $request)
    {
        $userId = auth('sanctum')->user()->id;

        $monitoringId  = $request->inspectionId; // id table monitoring
        $date          = $request->date;
        $userId        = auth('sanctum')->user()->id;

        $activeMonitoring = ($date) ? Api_ComplaintMonitoring::getActiveMonitoringById($monitoringId, $userId, $date) : Api_ComplaintMonitoring::getActiveMonitoringById($monitoringId, $userId);
        if($activeMonitoring){
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($activeMonitoring->complaint_id, 'get');
            $complaintMonitoringAttach = Api_ComplaintMonitoringAttachment::getMonitoringAttachmentbyMonitoringId($activeMonitoring->id);

            return response()->json([
                'complaint_inspection_active_model' => $activeMonitoring,
                'complaint_monitoring_attachment' => $complaintMonitoringAttach,
                'complaint_attachment' => $complaintAttach,
            ], 200);
        }else{
            return response()->json([
                'complaint_inspection_active_model' => 'null',
            ], 200);
        }

    }


    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Dtolak List
    //--------------------------------------------------------------------------------------------------------------
    public function getComplaintDamageInspectionRejectedList(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;
        $allData = [];

        $getPemantauanDitolak = Api_Complaint::getAduanKerosakanDitolak($userId, $start, $end, 'get');

        $getPemantauanDitolak->each(function($complaint, $key) use (&$allData){

            $pemantauanActivebyId   = Api_Complaint::getComplaintDamageById($complaint->id, $complaint->appointment_id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');

            $complaintInventory->each(function($comp_inventory, $key){
                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaint){
                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

            $item = [];
            $item['complaint_monitoring']   = ($pemantauanActivebyId) ? $pemantauanActivebyId : "";
            $item['complaint_inventory']    = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']       = ($complaintOthers) ? $complaintOthers : "";

            array_push($allData, $item);
        });


        return response()->json([
            'get_pemantauan_kerosakan_ditolak' => $allData,
        ], 200);
    }

    public function getComplaintViolationInspectionRejectedList(Request $request)
    {
        $userId = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;
        $allData = [];

        $getPemantauanDitolak = Api_Complaint::getAduanAwamDitolak($userId, $start, $end, 'get');

        $getPemantauanDitolak->each(function($complaint, $key) use (&$allData){

            $pemantauanSelesaibyId   = Api_Complaint::getComplaintById($complaint->id);
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

            $item = [];
            $item['complaint_monitoring']               = ($pemantauanSelesaibyId) ? $pemantauanSelesaibyId : "";
            $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";

            array_push($allData, $item);
        });

        return response()->json([
            'get_pemantauan_awam_ditolak' => $allData,
        ], 200);

    }

    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Ditolak By Id
    //--------------------------------------------------------------------------------------------------------------

    public function getComplaintDamageInspectionRejectedById(Request $request)
    {
        $complaint_id  = $request->complaint_id;
        $aduanDitolak = Api_Complaint::getAduanKerosakanDitolakById($complaint_id);

        if($aduanDitolak)
        {
            $aduan   = Api_Complaint::getComplaintDamageById($aduanDitolak->id, $aduanDitolak->appointment_id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($aduanDitolak->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($aduanDitolak->id, 'get');

            $complaintInventory->each(function($comp_inventory, $key){
                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($aduanDitolak){
                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $aduanDitolak->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

            return response()->json([
                'get_pemantauan_kerosakan_ditolak' => $aduan,
                'complaintInventory' => $complaintInventory,
                'complaintOthers' => $complaintOthers,
            ], 200);
        }else{
            return response()->json([
                'get_pemantauan_kerosakan_ditolak' => ""
            ], 200);
        }
    }

    public function getComplaintViolationInspectionRejectedById(Request $request)
    {
        $complaint_id  = $request->complaint_id;

        $aduanDitolak = Api_Complaint::getAduanAwamDitolakById($complaint_id);

        if($aduanDitolak)
        {
            $aduan   = Api_Complaint::getComplaintById($complaint_id);
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint_id, 'get');

            return response()->json([
                'get_pemantauan_awam_ditolak' => $aduan,
                'complaint_attachment' => $complaintAttach,
            ], 200);

        }else{
            return response()->json([
                'get_pemantauan_awam_ditolak' => ""
            ], 200);
        }

    }

    //--------------------------------------------------------------------------------------------------------------
    //  Pemantauan > Aduan Selenggara List
    //--------------------------------------------------------------------------------------------------------------
    public function getComplaintDamageInspectionMaintenanceList(Request $request)
    {
        $usersId  = auth('sanctum')->user()->id;
        $start  = $request->startDate;
        $end    = $request->endDate;

        $allData = [];
        $getPemantauanSelenggara= Api_Complaint::getAduanDiselenggara($usersId, $start, $end, 'get');

        $getPemantauanSelenggara->each(function($complaint, $key) use (&$allData){

            $pemantauanActivebyId   = Api_Complaint::getComplaintDamageById($complaint->id, $complaint->appointment_id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($complaint->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($complaint->id, 'get');
            $complaintAppointmentAttach = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($complaint->appointment_id, 'get');

            $complaintInventory->each(function($comp_inventory, $key){
                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaint){
                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaint->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

            $item = [];
            $item['complaint_monitoring']               = ($pemantauanActivebyId) ? $pemantauanActivebyId : "";
            $item['complaint_monitoring_attachment']    = ($complaintAppointmentAttach) ? $complaintAppointmentAttach : "";
            $item['complaint_inventory']                = ($complaintInventory) ? $complaintInventory : "";
            $item['complaint_others']                   = ($complaintOthers) ? $complaintOthers : "";

            array_push($allData, $item);
        });

        return response()->json([
            'get_penyelenggaraan _aduan' => $allData,
        ], 200);
    }


    public function getComplaintDamageInspectionMaintenanceById(Request $request)
    {

        $complaint_id  = $request->complaint_id;
        $aduanDiselenggara= Api_Complaint::getAduanDiselenggaraById($complaint_id);

        if($aduanDiselenggara)
        {
            $complaint   = Api_Complaint::getComplaintDamageById($aduanDiselenggara->id, $aduanDiselenggara->appointment_id);
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($aduanDiselenggara->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($aduanDiselenggara->id, 'get');
            $complaintAppointmentAttach = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($aduanDiselenggara->appointment_id, 'get');

            $complaintInventory->each(function($comp_inventory, $key){
                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($aduanDiselenggara){
                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $aduanDiselenggara->id, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;
            });

            return response()->json([
                'get_penyelenggaraan _aduan' => $complaint,
                'complaint_monitoring_attachment' => $complaintAppointmentAttach,
                'complaint_inventory' => $complaintInventory,
                'complaint_others' => $complaintOthers,
            ], 200);

        }
        else{
            return response()->json([
                'get_penyelenggaraan _aduan' => "",
            ], 200);
        }
    }


    public function getComplaintDamageInspectionCompletedById(Request $request)
    {
        $appointmentId = $request->inspectionId; // id tbl appointment

        $userId =  auth('sanctum')->user()->id;

        $completedAppmt = Api_ComplaintAppointment::getCompletedAppointmentById($appointmentId, $userId);

        if($completedAppmt){
            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($completedAppmt->complaint_id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($completedAppmt->complaint_id, 'get');
            $complaintAppointmentAttach = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($appointmentId, 'get');
            $complaintId = $completedAppmt->complaint_id;

            $complaintInventory->each(function($comp_inventory, $key){

                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaintId){

                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaintId, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;

            });

            return response()->json([
                'complaint_inspection_completed_model' => $completedAppmt,
                'complaint_inventory' => $complaintInventory,
                'complaint_others' => $complaintOthers,
                'complaint_appointment_attachment' => $complaintAppointmentAttach,
            ], 200);
        }else{
            return response()->json([
                'complaint_inspection_completed_model' => 'null',
            ], 200);
        }

    }

    public function getComplaintViolationInspectionCompletedById(Request $request)
    {
        $monitoringId = $request->inspectionId; // id tbl monitoring
        $userId =  auth('sanctum')->user()->id;

        $completedMonitoring = Api_ComplaintMonitoring::getCompletedMonitoringById($monitoringId, $userId);

        if($completedMonitoring){

            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($completedMonitoring->complaint_id, 'get');
            $complaintMonitoringAttach = Api_ComplaintMonitoringAttachment::getMonitoringAttachmentbyMonitoringId($completedMonitoring->id);

            return response()->json([
                'complaint_inspection_completed_model' => $completedMonitoring,
                'complaint_monitoring_attachment' => $complaintMonitoringAttach,
                'complaint_attachment' => $complaintAttach,
            ], 200);
        }
        else{
            return response()->json([
                'complaint_inspection_completed_model' => 'null',
            ], 200);
        }

    }

     //--------------------------------------------------------------------------------------------------------------
    public function getComplaintDamageInspectionPendingById(Request $request)
    {
        $complaintId = $request->inspectionId; // id tbl complaint
        $userId =  auth('sanctum')->user()->id;

        $pendingAppmt = Api_Complaint::getComplaintDamagePendingMonitorById($complaintId, $userId);

        if($pendingAppmt){

            $complaintInventory = Api_ComplaintInventory::getInventoryByComplaintId($pendingAppmt->id, 'get');
            $complaintOthers = Api_ComplaintOthers::getComplaintOthersByComplaintId($pendingAppmt->id, 'get');
            $complaintAppointmentAttach = Api_ComplaintAppointmentAttachment::getAppointmentAttachmentbyAppointmentId($complaintId, 'get');

            $complaintInventory->each(function($comp_inventory, $key){

                $complaintInventoryAttach = Api_ComplaintInventoryAttachment::getInventoryAttachmentbyId($comp_inventory?->id, 'get');
                $comp_inventory['complaint_inventory_attachment'] = ($complaintInventoryAttach) ? $complaintInventoryAttach : "" ;
            });

            $complaintOthers->each(function($comp_others, $key) use($complaintId){

                $complaintOthersAttach = Api_ComplaintAttachment::getOthersAttachmentbyId($comp_others?->id, $complaintId, 'get');
                $comp_others['complaint_others_attachment'] = ($complaintOthersAttach) ? $complaintOthersAttach : "" ;

            });

            return response()->json([
                'complaint_inspection_pending_model' => $pendingAppmt,
                'complaint_inventory' => $complaintInventory,
                'complaint_others' => $complaintOthers,
                'complaint_appointment_attachment' => $complaintAppointmentAttach,
            ], 200);

        }else{
            return response()->json([
                'complaint_inspection_pending_model' => 'null',
            ], 200);
        }

    }

    public function getComplaintViolationInspectionPendingById(Request $request)
    {
        $complaintId = $request->inspectionId; // id tbl complaint

        $userId =  auth('sanctum')->user()->id;

        $pendingMonitoring = Api_Complaint::getPendingMonitoringById($complaintId, $userId);

        if($pendingMonitoring){

            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaintId, 'get');
            return response()->json([
                'complaint_inspection_pending_model' => $pendingMonitoring,
                'complaint_monitoring' => [], //pending = belum monitor
                'complaint_attachment' => $complaintAttach,
            ], 200);
        }else{
            return response()->json([
                'complaint_inspection_pending_model' => 'null',
            ], 200);
        }



    }


    //------------------------------------------------------POST----------------------------------------------------------

    public function submitComplaintDamageInspection(Request $request)
    {
        $allData =[];
        $inventoryData = [];

        $complaint = Api_Complaint::where('id', $request->id)->first();
        $inventoryCheck =  isset($request->inventory_check) ? $request->inventory_check: "";
        $complaintCheck =  isset($request->complaint_check) ? $request->complaint_check: ""; //value=1 (checkbox)

            if( $inventoryCheck){

                foreach($inventoryCheck as $i=>$inventory_id)
                {

                    $complaintInventory = Api_ComplaintInventory::where(['complaint_id'=> $complaint->id, 'inventory_id' => $inventory_id, 'data_status' => 1])
                        ->update([
                            'flag_action'         => 1,
                            'action_by'           => loginId(),
                            'action_on'           => currentDate(),
                            ]);

                    $complaintInventory = Api_ComplaintInventory::where(['complaint_id'=> $complaint->id, 'inventory_id' => $inventory_id, 'data_status' => 1])->first();

                    $inventory = Api_Inventory::where('id', $inventory_id)->first();
                    array_push($allData, $complaintInventory);
                    array_push($inventoryData, $inventory);
                }
            }

            if($complaintCheck){

                foreach($complaintCheck as $i=>$check)
                {
                    if($check != null || $check !=  "" )
                    {
                        $complaintOthers = Api_ComplaintOthers::where(['id'=> $check, 'data_status' => 1])
                        ->update([
                            'flag_action'         => 1,
                            'action_by'           => loginId(),
                            'action_on'           => currentDate(),
                            ]);
                    }

                }
            }
            // update_monitoring_remarks
            $comp_appointment = Api_ComplaintAppointment::where('complaint_id', $complaint->id) ->where('data_status', 1)->orderBy('id','desc')->first();

            $comp_appointment->monitoring_remarks = $request->remarks;
            $comp_appointment->action_by = auth('sanctum')->user()->id;
            $comp_appointment->action_on = currentDate();
            $comp_appointment->save();

             // save monitoring file
            if($request->monitoring_file != null)
            {
                foreach($request->monitoring_file  as $key => $file)
                {
                    $path = $file->store('documents/complaint_monitoring', 'assets-upload');

                    $monitoring = new Api_ComplaintAppointmentAttachment;

                    $monitoring->complaint_appointment_id     = $comp_appointment->id;
                    $monitoring->path_document                = $path;
                    $monitoring->data_status                  = 1;
                    $monitoring->action_by                    = auth('sanctum')->user()->id;
                    $monitoring->action_on                    = currentDate();

                    $saved = $monitoring->save();
                }
            }
            // update_complaint_status
            //if selesai = selenggara

            $complaint->complaint_status_id      = ($request->complaint_status == 3) ? 5 : 2; //if selesai = penyelenggaraan else ditolak
            $complaint->remarks                  =  $request->rejected_reason ?? '' ;
            $saved = $complaint->save();

            //SEND NOTIFICATION TO PORTAL
            $complaintNoti = Complaint::where('id', $complaint->id)->first();
            $complaintNoti->user?->notify(new ComplaintMonitoringNotification($complaint->ref_no, $complaint->id, 1));

            if($saved){
                    return response()->json([
                        'status' => true,
                        'message' => "Pemantauan Aduan berjaya ditambah!",
                        'complaint_inspection_active_model' => $allData,
                        'inventori' =>  $inventoryData,
                    ], 200);
            }
            else{
                return response()->json([
                    'status'    => false,
                    'message'   => "Pemantauan Aduan tidak  berjaya ditambah!",
                ], 500);
            }
    }

    public function submitComplaintViolationInspection(Request $request) // ADUAN AWAM
    {
        DB::beginTransaction();

        try {
                $monitoring = new Api_ComplaintMonitoring;

                $monitoring->complaint_id                 =  $request->complaint_id;
                $monitoring->monitoring_remarks           =  $request->remarks;
                $monitoring->monitoring_status_id         =  $request->monitoring_status_id;
                $monitoring->monitoring_counter           = 1; //kali pertama turun site
                $monitoring->data_status                  = 1;
                $monitoring->action_by                    = auth('sanctum')->user()->id;
                $monitoring->action_on                    = currentDate();
                $monitoring->save();

                if($request->monitoring_file != null)
                {
                    foreach($request->monitoring_file  as $key => $file)
                    {
                        $path = $file->store('documents/complaint_monitoring', 'assets-upload');

                        $monitoringAttachment = new Api_ComplaintMonitoringAttachment;

                        $monitoringAttachment->complaint_monitoring_id      = $monitoring->id;
                        $monitoringAttachment->path_document                = $path;
                        $monitoringAttachment->monitoring_counter           = $monitoring->monitoring_counter;
                        $monitoringAttachment->data_status                  = 1;
                        $monitoringAttachment->action_by                    = auth('sanctum')->user()->id;
                        $monitoringAttachment->action_on                    = currentDate();
                        $saved = $monitoringAttachment->save();
                    }
                }

                // update_complaint_status
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

                $complaint = Api_Complaint::where('id',  $request->complaint_id) ->first();
                $complaint->complaint_status_id  = $complaint_status;
                $complaint->officer_id           = auth('sanctum')->user()->id;
                $complaint->officer_respond_on   = currentDate();

                $save = $complaint->save();

                $complaintNoti = Complaint::where('id', $complaint->id)->first();
                $complaintNoti->user?->notify(new ComplaintMonitoringNotification($complaint->ref_no, $complaint->id, 2));

                DB::commit();

                if($save){
                    return response()->json([
                        'status' => true,
                        'message' => "Pemantauan Aduan berjaya ditambah!",
                    ], 200);
                }

            } catch (\Exception $e) {
                DB::rollback();

                // something went wrong
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
    }

    public function submitRepeatedComplaintViolationInspection(Request $request) // TAB PEMANTAUAN BERULANG
    {
        DB::beginTransaction();

        try{

            $complaint_id = $request->complaint_id;

            $monitoring = Api_ComplaintMonitoring::where('data_status', 1)->where('complaint_id', $complaint_id)->first();
            $complaint  = Api_Complaint::where('id', $complaint_id)->where('data_status', 1)->first();

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

            $monitoring -> monitoring_status_id      = $request-> monitoring_status;  //if pemantauan semula // else selesai
            $monitoring -> action_by                 = auth('sanctum')->user()->id;
            $monitoring -> action_on                 = currentDate();
            $monitoring ->save();

            $complaint  -> officer_id               = auth('sanctum')->user()->id;
            $complaint  -> officer_respond_on       = currentDate();
            $complaint ->save();

            if($request->monitoring_file != null)
            {
                foreach($request->monitoring_file  as $key => $file)
                {
                    $path = $file->store('documents/complaint_monitoring', 'assets-upload');

                    $monitoringAttachment = new Api_ComplaintMonitoringAttachment;

                    $monitoringAttachment->complaint_monitoring_id      = $monitoring->id;
                    $monitoringAttachment->path_document                = $path;
                    $monitoringAttachment-> monitoring_counter          = $monitoring->monitoring_counter;
                    $monitoringAttachment->data_status                  = 1;
                    $monitoringAttachment->action_by                    = auth('sanctum')->user()->id;
                    $monitoringAttachment->action_on                    = currentDate();
                    $monitoringAttachment->save();
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Maklumat Pemantauan Berulang Berjaya Dikemaskini!",
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
