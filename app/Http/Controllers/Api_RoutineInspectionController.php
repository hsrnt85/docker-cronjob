<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_ApprovalStatus;
use App\Models\Api\Api_Officer;
use App\Models\Api\Api_RoutineInspection;
use App\Models\Api\Api_RoutineInspectionTransaction;
use App\Models\Api\Api_RoutineInspectionTransactionAttachment;
use App\Notifications\RoutineInspectionApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Api_RoutineInspectionController extends Controller
{
    public function countRoutineInspectionActiveByUserId(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $count      = Api_RoutineInspection::countRoutineInspectionActive($officer_id, $request->startDate, $request->endDate);

        return response()->json([
            'count' => $count,
        ], 200);
    }

    public function getRoutineInspectionActiveList(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $inspectionAll  = Api_RoutineInspection::getRoutineInspectionActiveAll($officer_id, $request->startDate, $request->endDate);

        return response()->json([
            'inspectionAll' => $inspectionAll,
        ], 200);
    }

    public function getRoutineInspectionActiveById(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $inspectionAll  = Api_RoutineInspection::getRoutineInspectionActiveById($officer_id, $request->id);

        return response()->json([
            'inspection' => $inspectionAll,
        ], 200);
    }

    public function countRoutineInspectionCompletedByUserId(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $count      = Api_RoutineInspection::countRoutineInspectionCompleted($officer->id, $request->startDate, $request->endDate);

        return response()->json([
            'count' => $count,
        ], 200);
    }

    public function countRoutineInspectionApproved(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $count      = Api_RoutineInspection::countRoutineInspectionApproved($officer->id);

        return response()->json([
            'count' => $count,
        ], 200);
    }

    public function getRoutineInspectionCompletedList(Request $request)
    {
        $user           = auth('sanctum')->user();
        $officer        = $user->officer();
        $officer_id     = ($officer) ? $officer->id : 0;
        $inspectionAll  = Api_RoutineInspection::getRoutineInspectionCompletedAll($officer_id, $request->startDate, $request->endDate);


        return response()->json([
            'inspectionAll' => $inspectionAll,
        ], 200);
    }

    public function getRoutineInspectionCompletedById(Request $request)
    {
        $user           = auth('sanctum')->user();
        $officer        = $user->officer();
        $officer_id     = ($officer) ? $officer->id : 0;
        $inspectionAll  = Api_RoutineInspection::getRoutineInspectionCompletedById($officer_id, $request->id);


        return response()->json([
            'inspection' => $inspectionAll,
        ], 200);
    }

    public function getRoutineInspectionInProgressList(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $inspectionAll  = Api_RoutineInspection::getRoutineInspectionInProgressAll($officer_id, $request->startDate, $request->endDate);

        return response()->json([
            'inspectionAll' => $inspectionAll,
        ], 200);
    }

    public function getRoutineInspectionInProgressById(Request $request)
    {
        $user       = auth('sanctum')->user();
        $officer    = $user->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $inspection  = Api_RoutineInspection::getRoutineInspectionInProgressById($officer_id, $request->id);

        return response()->json([
            'inspection' => $inspection,
        ], 200);
    }

    public function submitRoutineInspection(Request $request)
    {
        $user    = auth('sanctum')->user();
        $officer = $user->officer();

        DB::beginTransaction();

        try {
            $rit = new Api_RoutineInspectionTransaction();
            $rit->routine_inspection_id = $request->inspection_id;
            $rit->inspection_status_id  = $request->status;
            $rit->approval_officer_id   = $request->approval_officer ?? 0;
            $rit->approval_status_id    = null;
            $rit->remarks               = $request->remarks;
            $rit->data_status           = 1;
            $rit->action_on             = currentDate();
            $rit->action_by             = loginId();
            $rit->save();
            $rit->refresh();

            if ($request->gambar != null) {
                foreach ($request->gambar  as $key => $file) {
                    $path = $file->store('documents/routine_inspection_transaction', 'assets-upload');

                    $attachment = new Api_RoutineInspectionTransactionAttachment();

                    $attachment->routine_inspection_transaction_id = $rit->id;
                    $attachment->path_document  = $path;
                    $attachment->data_status    = 1;
                    $attachment->action_by      = loginId();
                    $attachment->action_on      = currentDate();

                    $saved = $attachment->save();
                }
            }

            if ($request->status == 1) {
                $rit->officer->user->notify(new RoutineInspectionApprovalNotification($rit->routineInspection->ref_no, $rit->id));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return response()->json([
                'status' => "Error",
                'detail' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => "Sukses",
            'detail' => "Transaksi pemantauan berkala berjaya disimpan"
        ], 200);
    }

    public function submitInProgressRoutineInspection(Request $request)
    {
        $user    = auth('sanctum')->user();
        $officer = $user->officer();

        DB::beginTransaction();

        try {
            $updated = tap(Api_RoutineInspectionTransaction::where('routine_inspection_id', $request->id))
                ->update([
                    'meeting_remarks' => $request->remarks,
                    'inspection_status_id' => 1,
                    'approval_officer_id' => $request->approval_officer,
                    'approval_status_id' => null,
                    'action_by' => loginId(),
                    'action_on' => currentDate(),
                ])
                ->first();

            if ($request->gambar != null) {
                foreach ($request->gambar as $key => $file) {
                    $path = $file->store('documents/routine_inspection_transaction', 'assets-upload');

                    $attachment = new Api_RoutineInspectionTransactionAttachment();

                    $attachment->routine_inspection_transaction_id = $updated->id;
                    $attachment->path_document  = $path;
                    $attachment->data_status    = 1;
                    $attachment->action_by      = loginId();
                    $attachment->action_on      = currentDate();

                    $saved = $attachment->save();
                }
            }

            $updated->officer->user->notify(new RoutineInspectionApprovalNotification($updated->routineInspection->ref_no, $updated->id));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return response()->json([
                'status' => "Error",
                'detail' => $e->getMessage(),
                'updated' => $updated->officer,
            ], 500);
        }

        return response()->json([
            'status' => "Sukses",
            'detail' => "Transaksi pemantauan berkala berjaya disimpan",
        ], 200);
    }

    public function getApprovalOfficer(Request $request)
    {
        $ri = Api_RoutineInspection::find($request->inspection_id);
        $district = $ri->quarters_category->district;

        $approvalOfficerAll = Api_Officer::getPegawaiPengesahByDaerah($district->id);

        $filteredApprovalOfficerAll = $this->_filterApprovalOfficerDataForView($approvalOfficerAll);

        return response()->json([
            'approvalOfficerAll' => $filteredApprovalOfficerAll,
        ], 200);
    }

    public function getApprovalStatus()
    {
        $statusAll = Api_ApprovalStatus::all(['id', 'status']);

        return response()->json([
            'statusAll' => $statusAll,
        ], 200);
    }


    private function _filterApprovalOfficerDataForView($approvalOfficerAll)
    {
        $filteredData = [];

        $approvalOfficerAll->each(function ($officer, $index) use (&$filteredData) {
            $item['userid']     = $officer->user->id;
            $item['officerid']  = $officer->id;
            $item['name']       = $officer->user->name;
            $item['new_ic']     = $officer->user->new_ic;

            array_push($filteredData, $item);
        });

        return $filteredData;
    }
}
