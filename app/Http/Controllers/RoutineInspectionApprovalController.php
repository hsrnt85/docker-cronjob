<?php

namespace App\Http\Controllers;

use App\Models\ApprovalStatus;
use App\Models\Officer;
use Illuminate\Http\Request;
use App\Models\QuartersCategory;
use App\Models\RoutineInspection;
use App\Models\RoutineInspectionTransaction;
use App\Models\Quarters;
use App\Models\RoutineInspectionTransactionAttachment;
use Illuminate\Support\Facades\DB;


class RoutineInspectionApprovalController extends Controller
{
    public function index()
    {
        $officer = auth()->user()->officer();

        $inspectionTransactionAll               = RoutineInspectionTransaction::getAllInspectionByApprovalOfficer($officer?->id);
        $inspectionTransactionWithStatusAll     = RoutineInspectionTransaction::getAllInspectionByApprovalOfficerWithStatus($officer?->id);

        return view( getFolderPath().'.index',
        [
            'inspectionTransactionAll' => $inspectionTransactionAll,
            'inspectionTransactionWithStatusAll' => $inspectionTransactionWithStatusAll,
        ]);
    }

    public function approval(RoutineInspectionTransaction $inspectionTransaction)
    {
        $attachmentAll  = RoutineInspectionTransactionAttachment::getAttachmentAll($inspectionTransaction->id);
        $statusAll      = ApprovalStatus::getAllStatus();

        return view( getFolderPath().'.approval',
        [
            'inspectionTransaction' => $inspectionTransaction,
            'attachmentAll' => $attachmentAll,
            'statusAll' => $statusAll,
        ]);
    }

    public function approvalUpdate(Request $request)
    {
        $officer = auth()->user()->officer();

        DB::beginTransaction();

        try {

            $inspectionTransaction = RoutineInspectionTransaction::find($request->inspection_transaction_id);
            $data_before = [];
            $data_before['inspection_transaction'] = $inspectionTransaction->toArray();
    
            $approveInspection = RoutineInspectionTransaction::where([
                'id' => $request->inspection_transaction_id,
                'data_status' => 1,
                'approval_officer_id' => $officer->id
            ])
            ->update([
                'approval_status_id' => $request->approval_status,
                'approval_remarks' => $request->approval_remarks,
            ]);

            $inspectionTransactionAfter = RoutineInspectionTransaction::find($request->inspection_transaction_id);
            $data_after = [];
            $data_after['inspection_transaction'] = $inspectionTransactionAfter->toArray();

            $approvalOfficer = Officer::find($inspectionTransaction->approval_officer_id);
            $officerName = $approvalOfficer ? $approvalOfficer->name : "Unknown Officer";

            // Create the activity message
            setUserActivity("P", $officerName, $data_before, $data_after);

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('routineInspectionApproval.edit', ['inspection' => $request->inspection_transaction_id])->with('error', 'Pengesahan pemantauan berkala tidak berjaya disimpan!' . ' ' . $e->getMessage());
        }

        return redirect()->route('routineInspectionApproval.index')->with('success', 'Pengesahan pemantauan berkala berjaya disimpan');
    }

    public function view(RoutineInspectionTransaction $inspectionTransaction)
    {
        $attachmentAll  = RoutineInspectionTransactionAttachment::getAttachmentAll($inspectionTransaction->id);
        $tab = ($inspectionTransaction->approval_status_id) ? 'terdahulu' : '';

        return view( getFolderPath().'.view',
        [
            'inspectionTransaction' => $inspectionTransaction,
            'attachmentAll' => $attachmentAll,
            'tab' => $tab,
        ]);
    }
}
