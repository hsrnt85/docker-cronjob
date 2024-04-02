<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\ComplaintAttachment;
use App\Http\Requests\RulesViolationComplaintApprovalRequest;
use App\Http\Requests\RulesViolationComplaintApprovalUpdateRequest;
use App\Notifications\PublicComplaintNotification;
use Illuminate\Support\Facades\DB;

class RulesViolationComplaintApprovalController extends Controller
{
    public function index()
    {
        // setSubmodule();

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $senaraiAduanAwam = Complaint::getPublicComplaintApprovalList(0, $district_id);

        //aduan yang telah diterima dan menunggu pengesahan.
        $pengesahanAduanAwam =  Complaint::getPublicComplaintApprovalList(1, $district_id);

        //aduan yang telah selesai atau ditolak.
        $senaraiAduanTerdahulu = Complaint::getPublicComplaintHistoryList($district_id);

        return view( getFolderPath().'.list',
        [
            'senaraiAduanAwam' => $senaraiAduanAwam,
            'pengesahanAduanAwam' => $pengesahanAduanAwam,
            'senaraiAduanTerdahulu' => $senaraiAduanTerdahulu,
        ]);
    }

    public function create(Request $request)
    {
        $id = $request->id;

        $new_complaint = Complaint::where('id', $id)->where('data_status' , 1)->where('complaint_status_id', 0)->first(); //aduan status baru

        if(!$new_complaint){

            //block if officer dah sahkan aduan
            return redirect()->route('rulesViolationComplaintApproval.index')->with('error', 'Pegawai telah membuat pengesahan aduan!');
        }

        $complaint = Complaint::where([['data_status', 1],['id', $id]])->first();

        $complaint_attachment = ComplaintAttachment::where('complaint_id', $complaint->id)->where('data_status', 1)->get();

        $complaintStatusAll = ComplaintStatus::where('status_data', 1)->where('flag_aduan_sop',1)->get();

        $tab = 'baru' ;

        // if(checkPolicy("U"))
        // {
            return view( getFolderPath().'.create',
            [
                'complaint' => $complaint,
                'complaint_attachment' => $complaint_attachment,
                'complaintStatusAll' => $complaintStatusAll,
                'tab' => $tab
            ]);
        // }
        // else
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }

    }

    public function store(RulesViolationComplaintApprovalRequest $request)
    {
        DB::beginTransaction();

        $id = $request->id;

        try {
            $complaintBefore = Complaint::where('id', $id)->where('data_status', 1)->first();
            $data_before = $complaintBefore->getRawOriginal();
            $data_before['item'] = is_array($complaintBefore->toArray()) ? $complaintBefore->toArray() : [];

            Complaint::where('id', $id)->where('data_status', 1)->update([
                'complaint_status_id' => $request->complaint_status,
                'remarks'             => $request->rejection_reason,
                'officer_id'          => loginId(),
                'officer_respond_on'  => currentDate(),
            ]);

            // User Activity - Set Data after changes
            $complaintAfter = Complaint::where('id', $id)->where('data_status', 1)->first();
            $data_after = $complaintAfter;
            $data_after['item'] = is_array($complaintAfter->toArray()) ? $complaintAfter->toArray() : [];

            // SEND NOTIFICATION TO PORTAL
            $complaint_ref_no = $complaintAfter->ref_no;
            $complaint_id = $complaintAfter->id;
            $complaint_status = $complaintAfter->complaint_status_id;

            $complaintAfter->user?->notify(new PublicComplaintNotification($complaint_ref_no, $complaint_id, $complaint_status));
            // User Activity - Save
            setUserActivity("P", "Pengesahan Aduan Awam ".$complaint_ref_no);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('rulesViolationComplaintApproval.index')->with('error', 'Pengesahan Aduan Awam tidak berjaya dihantar!' . ' ' . $e->getMessage());
        }

        return redirect()->route('rulesViolationComplaintApproval.index')->with('success', 'Pengesahan Aduan Awam berjaya dihantar!');
    }


    public function view(Request $request)
    {
        $id = $request->id;

        $complaint = Complaint::where('id', $id)->first();

        $complaint_attachment = ComplaintAttachment::where('complaint_id', $complaint->id)->where('data_status', 1)->get();

        $tab = ($complaint->complaint_status_id == 1) ? 'pengesahan' : 'terdahulu';

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'complaint' => $complaint,
                'complaint_attachment' => $complaint_attachment,
                'tab' =>$tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }



}
