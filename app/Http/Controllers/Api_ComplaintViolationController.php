<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api\Api_Complaint;
use App\Models\Api\Api_ComplaintAttachment;
use App\Models\Api\Api_ComplaintType;
use App\Models\Api\Api_Tenant;
use App\Http\Resources\GetData;
use App\Http\Resources\ListData;
use App\Notifications\ComplaintNotification;

class Api_ComplaintViolationController extends Controller
{

    public function getPendingComplaintViolationDetailsById(Request $request) // done sorting
    {
        $complaintId = $request->complaint_id;
        $pendingComplaint = Api_Complaint::getComplaintViolationDetailsById($complaintId);
        $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaintId ,'get');

        if($pendingComplaint){
            return response()->json([
                'complaint_details_model' => $pendingComplaint,
                'complaint_attachment' => $complaintAttach,
                'complaint_status' => $pendingComplaint->status->complaint_status ?? "",
            ], 200);
        }
        else{
            return response()->json([
                'complaint_details_model' => $pendingComplaint,
            ], 200);
        }
    }

    public function getActiveComplaintViolationDetailsById(Request $request) // done
    {
        $complaintId = $request->complaint_id;
        $activeComplaint = Api_Complaint::getActiveComplaintViolationDetailsById($complaintId);
        $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaintId ,'get');

        if($activeComplaint){
            return response()->json([
                'complaint_active_details_model' => $activeComplaint,
                'complaint_attachment' => $complaintAttach,
                'complaint_status' => $activeComplaint->status?->complaint_status,
            ], 200);
        }
        else{
            return response()->json([
                'complaint_active_details_model' => $activeComplaint,
            ], 200);
        }

    }

    public function getCompletedComplaintViolationDetailsById(Request $request) //done
    {
        $id = $request->complaint_id;

        $completedComplaint = Api_Complaint::getCompletedComplaintViolationInspectionById($id);
        $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($id ,'get');

        if($completedComplaint){
            return response()->json([
                'history_complaint_details_model' => $completedComplaint,
                'complaint_attachment' => $complaintAttach,
                'complaint_status' => $completedComplaint->status?->complaint_status,
            ], 200);
        }else{
            return response()->json([
                'history_complaint_details_model' => $completedComplaint,
            ], 200);
        }


    }

    public function getPendingComplaintViolationList(Request $request) //done
    {
        $userId = auth('sanctum')->user()->id;

        $start  = $request->startDate;
        $end    = $request->endDate;

        $allData = [];

        $pendingViolationList   = Api_Complaint::getPendingComplaintViolationList($userId, $start, $end, 'get');

        $pendingViolationList->each(function($complaint, $key) use (&$allData){

            $pendingById   = Api_Complaint::getComplaintViolationDetailsById($complaint->id);
            $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

            $item = [];
            $item['complaint']                          = ($pendingById) ? $pendingById : "";
            $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";
            $item['complaint_status']                   = $pendingById->status?->complaint_status;
            array_push($allData, $item);
        });

        return response()->json([
            'history_complaint_details_model' => $allData,
        ], 200);
    }

    // public function getActiveComplaintViolationList(Request $request) // done
    // {
    //     $start  = $request->startDate;
    //     $end    = $request->endDate;

    //     $allData = [];

    //     $activeViolationList   = Api_Complaint::getActiveComplaintViolationList($start, $end, 'get');

    //     $activeViolationList->each(function($complaint, $key) use (&$allData){

    //         $activeById   = Api_Complaint::getActiveComplaintViolationDetailsById($complaint->id);
    //         $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

    //         $item = [];
    //         $item['complaint']                          = ($activeById) ? $activeById : "";
    //         $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";
    //         $item['complaint_status']                   = $activeById->status?->complaint_status;

    //         array_push($allData, $item);
    //     });

    //     return response()->json([
    //         'history_complaint_details_model' => $allData,
    //     ], 200);
    // }

    // public function getCompletedComplaintViolationList(Request $request) // replace by api ByTenants
    // {
    //     $start  = $request->startDate;
    //     $end    = $request->endDate;

    //     $allData = [];

    //     $completedViolationList   = Api_Complaint::getCompletedComplaintViolationList($start, $end, 'get');

    //     $completedViolationList->each(function($complaint, $key) use (&$allData){

    //         $completedById   = Api_Complaint::getCompletedComplaintViolationInspectionById($complaint->id);
    //         $complaintAttach = Api_ComplaintAttachment::getViolationAttachmentbyComplaintId($complaint->id, 'get');

    //         $item = [];
    //         $item['complaint']                          = ($completedById) ? $completedById : "";
    //         $item['complaint_attachment']               = ($complaintAttach) ? $complaintAttach : "";
    //         $item['complaint_status']                   = $completedById->status?->complaint_status;

    //         array_push($allData, $item);
    //     });

    //     return response()->json([
    //         'history_complaint_details_model' => $allData,
    //     ], 200);
    // }

    public function submitComplaintViolationForm(Request $request)
    {
        // $complaint = Api_Complaint::create($request->all());
        $tenants = Api_Tenant::checkTenant();

        if($request->description == null){
            return response()->json([
                'status'    => false,
                'message'   => 'Aduan Awam Tidak Berjaya Ditambah! Maklumat Aduan Awam Tidak Lengkap! ',
            ], 500);
        }
        try{
            $complaint_type = 2;

            $complaint_running_no = Api_Complaint::select('complaint.running_no', 'complaint_type.ref_no')
            ->join('complaint_type', 'complaint.complaint_type', '=', 'complaint_type.id')
            ->where('complaint_type', $complaint_type)
            ->orderBy('running_no', 'DESC')->first();

            $district_code = GetData::Api_District(districtId())->district_code;

            if($complaint_running_no == null){
                $running_no_new = str_pad(1, 6, "0", STR_PAD_LEFT);
                $complaint_running_no_new = Api_ComplaintType::where([['id', $complaint_type],['data_status', 1]])->first();
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
            $complaint->complaint_type          = $complaint_type;
            $complaint->complaint_description   = $request->description;
            $complaint->complaint_status_id     = 0;
            $complaint->data_status             = 1;
            $complaint->action_by               = loginId();
            $complaint->action_on               = currentDate();
            $complaint->save();

            $complaint_attachment = isset($request->complaint_attachment) ? $request->complaint_attachment: "";

            if($complaint_attachment != null)
            {
                foreach($complaint_attachment as $key => $attachment)
                {
                    $path = $attachment->store('documents/complaint/rules_violation', 'assets-upload');

                    $ComplaintAttachment                   = new Api_ComplaintAttachment;
                    $ComplaintAttachment->complaint_id     = $complaint->id;
                    $ComplaintAttachment->path_document    = $path;
                    $ComplaintAttachment->action_by        = loginId();
                    $ComplaintAttachment->action_on        = currentDate();
                    $ComplaintAttachment->save();
                }
            }

                //SEND NOTIFICATION TO ADMIN
                $complaint_ref_no = $ref_no_new;
                $complaint_id = $complaint->id;
                $officerPemantau = ListData::Officer(districtId(),4);// Pegawai Pemantau

                $officerPemantau ->each(function($officer, $key) use($complaint_type, $complaint_ref_no, $complaint_id ){
                    $officer->user?->notify(new ComplaintNotification($complaint_type, $complaint_ref_no, $complaint_id ));
                });


            return response()->json([
                'status' => true,
                'message' => "Aduan Awam berjaya Ditambah!",
                'complaint' => $complaint
            ], 200);

            // DB::commit();
        } catch (\Exception $e) {
            // DB::rollback();

            // something went wrong
            return response()->json([
                'status'    => false,
                'message'   => 'Aduan Awam Tidak Berjaya Ditambah! on line ' . $e->getLine(). ' ' . $e->getMessage(),
            ], 500);
        }

    }
}
