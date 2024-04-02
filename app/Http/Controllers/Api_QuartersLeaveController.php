<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Api\Api_Tenant;
use App\Models\Api\Api_TenantsLeaveAttachment;
use App\Models\Api\Api_TenantsOptionAttachment;
use App\Models\Api\Api_TenantsQuartersInventory;
use App\Models\Api\Api_LeaveOption;
use \Carbon\Carbon;

class Api_QuartersLeaveController extends Controller
{
    public function getVacantForms(Request $request)
    {
        $tenantId = $request->tenants_id;

        $tenantLeave = Api_Tenant::where(['id'=> $tenantId , 'data_status' => 1])
                        ->where(function($query) {
                            $query->where('is_draft_leave', '=', 1)
                                ->orWhere('leave_status_id', '=', 1);
                            })
                        // ->with('leave_option:id,description')
                        ->first();

        if($tenantLeave)
        {
            $tenantsLeaveAttachment = Api_TenantsLeaveAttachment::select('id', 'document_path')->where(['tenants_id' => $tenantId, 'data_status' => 1])->get();

            $tenantsOptionAttachment = Api_TenantsOptionAttachment::select('t.id', 't.leave_option_id', 't.document_path', 'leave.description as document_name')->from('tenants_options_attachment as t')
            ->where(['t.tenants_id' => $tenantId, 't.data_status' => 1])
            ->join('leave_option as leave' , 'leave.id', '=', 't.leave_option_id')->get();

            $tenantsQuartersInventory = Api_TenantsQuartersInventory::where(['tenants_id'=> $tenantId, 'data_status' => 1])->first();

            $leave_id_arr = stringToArray($tenantLeave->leave_option_id, ',');
            $leaveReason= Api_LeaveOption::select('description')->where('data_status' , 1)->whereIn('id', $leave_id_arr )->get();

                return response()->json([
                    'vacantDetails' => $tenantLeave,
                    'leaveReason' => $leaveReason,
                    'tenantsLeaveAttachment' =>$tenantsLeaveAttachment,
                    'tenantsOptionAttachment' => $tenantsOptionAttachment,
                    'tenantsQuartersInventory' => $tenantsQuartersInventory,

                ], 200);

        }else{
            return response()->json([
                'vacantDetails' => 'null',
            ], 200);
        }

    }

    public function submitVacantForms(Request $request)
    {

        $tenantActive = Api_Tenant::checkTenant();

        if($tenantActive)
        {
            $tenants = Api_Tenant::where('user_id', loginId())->orderBy('id', 'desc')->first();
            $leaveDate  = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->date_leave));

            //maklumat permohonan-------------------------------------------------------------

            $tenants->leave_date            = $leaveDate;
            $tenants->other_address_1       = $request->other_address_1;
            $tenants->other_address_2       = $request->other_address_2;
            $tenants->other_address_3       = $request->other_address_3;
            $tenants->other_district_id     = $request->other_district;
            $tenants->other_postcode        = $request->other_postcode;
            $tenants->residence_type_id     = $request->residence_type;
            $tenants->is_draft_leave  = 0;
            $tenants->leave_status_id  = 1; //berjaya keluar kuarters

            //maklumat sebab keluar kuarters--------------------------------------------------

            $leave_option_id                = $request->input('leave_option_id') != null ? implode(',', $request->input('leave_option_id')) : "";
            $tenants->leave_option_id       = $leave_option_id;
            $tenants->other_leave_reason    = $request->other_reason;
            $tenants->action_by             = loginId();
            $tenants->action_on             = currentDate();
            $saved = $tenants->save();

            $leave_document = isset($request->leave_document) ? $request->leave_document : "";

            if ($leave_document)
            {
                $leave_document_path   = $leave_document->store('documents/tenants_documents', 'assets-upload');

                $tenantsLeaveAttachment                   = new Api_TenantsLeaveAttachment;
                $tenantsLeaveAttachment->tenants_id       = $tenants->id;
                $tenantsLeaveAttachment->document_path    = $leave_document_path;
                $tenantsLeaveAttachment->action_by        = loginId();
                $tenantsLeaveAttachment->action_on        = currentDate();
                $tenantsLeaveAttachment->save();
            }

            //maklumat inventori--------------------------------------------------------------------

            $inventoryIdArr = $request->inventory_id;
            foreach($inventoryIdArr as $i=> $inventory_id)
            {
                $inventory_status = isset($request->inventory_status_out[$i]) ? $request->inventory_status_out[$i] : 0;
                $quantity_out = isset($request->quantity_out[$i]) ? $request->quantity_out[$i] : 0;
                $remarks = isset($request->remarks_out[$i]) ? $request->remarks_out[$i] : '';

                Api_TenantsQuartersInventory::where(['tenants_id' => $tenants->id])->where(['inventory_id' => $inventory_id])
                ->update([
                        'inventory_status_id_out' => $inventory_status,
                        'quantity_out' => $quantity_out,
                        'remarks_out' => $remarks,
                        'action_by' => loginId(),
                        'action_on' => currentDate()
                ]);
            }

            //dokumen sokongan---------------------------------------------------------------------------

            $documentIdArr = $request->document_id;
            foreach($documentIdArr as $v=> $document_id)
            {
                $supporting_doc = isset($request->supporting_doc[$v]) ? $request->supporting_doc[$v] : "";

                if ($supporting_doc)
                {
                    $supporting_document_path   = $supporting_doc->store('documents/tenants_documents', 'assets-upload');

                    $tenantsDocument = new Api_TenantsOptionAttachment;
                    $tenantsDocument->tenants_id       = $tenants->id;
                    $tenantsDocument->leave_option_id  = $document_id;
                    $tenantsDocument->document_path    = $supporting_document_path;
                    $tenantsDocument->action_by        = loginId();
                    $tenantsDocument->action_on        = currentDate();
                    $tenantsDocument->save();
                }
            }

            if($saved)
            {
                $leave_id_arr = stringToArray($tenants->leave_option_id, ',');
                $leaveReason= Api_LeaveOption::select('description')->where('data_status' , 1)->whereIn('id', $leave_id_arr )->get();
                $tenantsQuartersInventory = Api_TenantsQuartersInventory::where(['tenants_id'=> $tenants->id, 'data_status' => 1])->get();

                return response()->json([
                    'status' => true,
                    'message' => "Permohonan Keluar Kuarters berjaya dihantar",
                    'vacantDetails' => $tenants,
                    'leaveReason' => $leaveReason,
                    'tenantsQuartersInventory' => $tenantsQuartersInventory,

                ], 200);

            }
            else
            {
                return response()->json([
                    'status'    => false,
                    'message'   => "Permohonan Keluar Kuarters tidak berjaya dihantar",
                ], 500);
            }
        }else{
            return response()->json([
                'status'    => false,
                'vacantDetails' => 'Penghuni Tidak Aktif / Telah Keluar dari Kuarters',
            ], 200);
        }
    }
}
