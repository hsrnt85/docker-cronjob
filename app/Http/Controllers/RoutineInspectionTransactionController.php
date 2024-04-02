<?php

namespace App\Http\Controllers;

use App\Models\InspectionStatus;
use Illuminate\Http\Request;
use App\Models\RoutineInspection;
use App\Models\Officer;
use App\Models\RoutineInspectionTransaction;
use App\Models\RoutineInspectionTransactionAttachment;
use App\Notifications\RoutineInspectionApprovalNotification;
use Illuminate\Support\Facades\DB;

class RoutineInspectionTransactionController extends Controller
{
    public function index()
    {
        $seeAllDistrict = is_all_district();

        $routineInspectionAll = RoutineInspection::getAllInspectionByDistrict($seeAllDistrict);

        return view( getFolderPath().'.list',
        [
            'routineInspectionAll' => $routineInspectionAll,
        ]);
    }

    public function edit(RoutineInspection $inspection)
    {
        $statusAll = InspectionStatus::all();
        $pengesahAll = Officer::getPegawaiPengesahByDaerah(districtId());
        $attachmentAll = ($inspection->inspection_transaction) ? RoutineInspectionTransactionAttachment::getAttachmentAll($inspection->inspection_transaction->id) : collect();

        if (checkPolicy("U")) {
            return view(getFolderPath() . '.edit', [
                'inspection' => $inspection,
                'statusAll' => $statusAll,
                'pengesahAll' => $pengesahAll,
                'attachmentAll' => $attachmentAll,
            ]);
        } else {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }

    }


    public function update(Request $request)
    {
        DB::beginTransaction();

        $ri = RoutineInspection::find($request->inspection_id);

        $data_before = [];
        $data_before['ri'] = json_encode($ri->toArray());
        $data_before['rit'] = $ri->inspection_transaction ? json_encode($ri->inspection_transaction->toArray()) : null;
        $data_before['attachments'] = $ri->inspection_transaction_attachments ? json_encode($ri->inspection_transaction_attachments->toArray()) : [];

        try {
            if ($ri->inspection_transaction == null) {
                $rit = new RoutineInspectionTransaction();
                $rit->routine_inspection_id = $request->inspection_id;
                $rit->inspection_status_id  = $request->status;
                $rit->approval_officer_id   = $request->approval_officer;
                $rit->approval_status_id    = null;
                $rit->remarks               = $request->remarks;
                $rit->data_status           = 1;
                $rit->action_on             = currentDate();
                $rit->action_by             = loginId();
                $rit->save();
                $rit->refresh();
                
                if($request->status == 1){
                    $rit->officer->user->notify(new RoutineInspectionApprovalNotification($rit->routineInspection->ref_no, $rit->id));
                }

                $data_after = [];
                $data_after['ri'] = json_encode($ri->toArray());
                $data_after['rit'] = json_encode($rit->toArray());
                $data_after['attachments'] = [];

            } else {
                $updatedRit = tap(RoutineInspectionTransaction::where('id', $ri->inspection_transaction->id))
                    ->update([
                        'meeting_remarks' => $request->meeting_remarks,
                        'inspection_status_id' => 1,
                        'approval_officer_id' => $request->approval_officer,
                        'approval_status_id' => null,
                        'action_by' => loginId(),
                        'action_on' => currentDate(),
                    ])
                    ->first();

                $updatedRit->officer->user->notify(new RoutineInspectionApprovalNotification($updatedRit->routineInspection->ref_no, $updatedRit->id));


                $data_after = [];
                $data_after['ri'] = json_encode($ri->toArray());
                $data_after['rit'] = json_encode($ri->inspection_transaction->toArray());
                $data_after['attachments'] = $ri->inspection_transaction_attachments ? json_encode($ri->inspection_transaction_attachments->toArray()) : [];
            }

            if ($request->gambar != null) {
                foreach ($request->gambar as $key => $file) {
                    $path = $file->store('documents/routine_inspection_transaction', 'assets-upload');

                    $attachment = new RoutineInspectionTransactionAttachment();

                    $attachment->routine_inspection_transaction_id = (isset($rit)) ? $rit->id : $updatedRit->id;
                    $attachment->path_document  = $path;
                    $attachment->data_status    = 1;
                    $attachment->action_by      = loginId();
                    $attachment->action_on      = currentDate();

                    $saved = $attachment->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('routineInspectionTransaction.edit', ['inspection' => $request->inspection_id])->with('error', 'Transaksi pemantauan berkala tidak berjaya disimpan!' . ' ' . $e->getMessage());
        }

        setUserActivity("U", "Transaksi Pemantauan", json_encode($data_before), json_encode($data_after));

        return redirect()->route('routineInspectionTransaction.index')->with('success', 'Transaksi pemantauan berkala berjaya disimpan');
    }


    public function view(RoutineInspection $inspection)
    {
        $attachmentAll  = ($inspection->inspection_transaction) ? RoutineInspectionTransactionAttachment::getAttachmentAll($inspection->inspection_transaction->id) : collect();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'inspection' => $inspection,
                'attachmentAll' => $attachmentAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }
}
