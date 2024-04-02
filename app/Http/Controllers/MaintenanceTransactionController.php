<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintOthers;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintInventory;
use App\Models\ComplaintInventoryAttachment;
use App\Models\Officer;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceTransaction;
use App\Models\MaintenanceTransactionAttachment;
use App\Http\Requests\MaintenanceTransactionRequest;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class MaintenanceTransactionController extends Controller
{
    public function index()
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        //UNTUK TINDAKAN --------------------------------
        $complaintMaintenance = Complaint::getMaintenanceTransaction($district_id);

        $history = Complaint::from('complaint as c')->leftjoin('maintenance_transaction as mt', 'mt.complaint_id', '=', 'c.id')
        ->join('quarters','quarters.id','=','c.quarters_id')
        ->join('quarters_category','quarters_category.id','=','quarters.quarters_cat_id')
        ->select('c.ref_no', 'c.complaint_date', 'mt.monitoring_officer_id', 'mt.maintenance_status_id', 'c.id')
        ->where(['c.data_status' => 1, 'c.complaint_status_id' => 3, 'c.complaint_type' => 1, 'c.flag_maintenance' => 2, 'mt.maintenance_status_id' => 2]);

        //FILTER BY OFFICER DISTRICT ID
        if($district_id)
        {
            $history = $history->where('quarters_category.district_id', $district_id);
        }

        $maintenanceHistory = $history->orderBy('c.id', 'desc')->get();

        return view(getFolderPath().'.list',
        [
            'complaintMaintenance' => $complaintMaintenance,
            'maintenanceHistory' => $maintenanceHistory,
        ]);
    }

    public function edit(Request $request)
    {
        $complaintId = $request->id;

        $complaintOthers = ComplaintOthers::getComplaintOthersMaintanance($complaintId);
        $complaintInventory = ComplaintInventory::getComplaintInventoryMaintanance($complaintId);

        $maintenanceTransactionHistory = MaintenanceTransaction::from('maintenance_transaction as mt')
        ->join('complaint', 'complaint.id', '=', 'mt.complaint_id')
        ->select('mt.maintenance_date','mt.maintenance_status_id', 'mt.remarks', 'mt.id', 'mt.monitoring_officer_id')
        ->where(['mt.data_status' => 1, 'mt.complaint_id' => $complaintId])->get();

        $complaint = Complaint::where('id', $complaintId)->first();
        $officerPemantauAll = Officer::getPegawaiPemantauan();

        $maintenanceStatus = MaintenanceStatus::where('data_status', 1)->orderBy('id', 'DESC')->get();

        $tab = 'tindakan' ;

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
            [
                'complaintOthers' => $complaintOthers,
                'complaintInventory' => $complaintInventory,
                'complaint' => $complaint,
                'complaintId' => $complaintId,
                'maintenanceStatus' => $maintenanceStatus,
                'officerPemantauAll' => $officerPemantauAll,
                'maintenanceTransactionHistory' => $maintenanceTransactionHistory,
                'tab' => $tab

            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(MaintenanceTransactionRequest $request)
    {

        $complaintId = $request->id;
        $maintenance_file = $request->maintenance_file;
        $maintenance_date   = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->start_date));

        //INSERT INTO MAINTENANCE TRANSACTION  --------------------------------
        $maintenanceTransaction = new  MaintenanceTransaction;

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data before changes
        $data_before = $maintenanceTransaction->getRawOriginal();//dd($data_before);
        $data_before['item']= $maintenanceTransaction->toArray() ?? [];//dd($data_before);
        //------------------------------------------------------------------------------------------------------------------

        $maintenanceTransaction->complaint_id                           = $complaintId;
        $maintenanceTransaction->maintenance_date                       = $maintenance_date;
        $maintenanceTransaction->monitoring_officer_id                  = $request->monitoring_officer_id;
        $maintenanceTransaction->maintenance_status_id                  = $request->maintenance_status;
        $maintenanceTransaction->remarks                                = $request->remarks;
        $maintenanceTransaction->data_status                            = 1;
        $maintenanceTransaction->action_by                              = loginId();
        $maintenanceTransaction->action_on                              = currentDate();
        $saved = $maintenanceTransaction->save();


        //INSERT INTO MAINTENANCE TRANSACTION ATTACHMENT ----------------------------
        if($maintenance_file)
        {
            foreach($maintenance_file as $file)
            {
                $path = $file->store('maintenance', 'assets-upload');

                $attachment = new MaintenanceTransactionAttachment;

                $attachment->maintenance_transaction_id         = $maintenanceTransaction->id;
                $attachment->path_document                      = $path;
                $attachment->data_status                        = 1;
                $attachment->action_by                          = loginId();
                $attachment->action_on                          = currentDate();
                $saved = $attachment->save();
            }
        }


        //INSERT INTO COMPLAINT  ----------------------------------------------------
        if($maintenanceTransaction->maintenance_status_id == 1)  // belum selesai
        {
            Complaint::where('id', $complaintId)->update(['flag_maintenance' => 1]); // ongoing
        }
        else
        {
            Complaint::where('id', $complaintId)->update(['flag_maintenance' => 2, 'complaint_status_id' => 3]); // selesai
        }

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data after changes
        $data_after = $maintenanceTransaction;
        $data_after['item'] = $maintenanceTransaction->toArray() ?? [];

        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Save
        setUserActivity("U", "Transaksi Penyelenggaraan ".$maintenanceTransaction->complaint?->ref_no, $data_before_json, $data_after_json);
        //------------------------------------------------------------------------------------------------------------------


        //UPDATE INTO COMPLAINT INVENTORY / OTHERS  ---------------------------------
        ComplaintInventory::where(['data_status' => 1, 'complaint_id' => $complaintId])->update(['is_maintenance' => 1]);

        ComplaintOthers::where(['data_status' =>1 , 'complaint_id' => $complaintId])->update(['is_maintenance' => 1]);


        if(!$saved)
        {
             return redirect()->route('maintenanceTransaction.edit', ['id'=>$complaintId])->with('error', 'Transaksi Penyelenggaraan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('maintenanceTransaction.index')->with('success', 'Transaksi Penyelenggaraan berjaya dikemaskini!');
        }

    }

    public function view(Request $request)
    {

       $id = $request->id;

       $complaint = Complaint::where('id', $id)->first();

       $maintenanceTransaction = MaintenanceTransaction::where(['data_status' => 1, 'complaint_id'=> $id])->orderBy('id', 'desc')->first();

       $complaintOthers = ComplaintOthers::getComplaintOthersMaintanance($id);
       $complaintInventory = ComplaintInventory::getComplaintInventoryMaintanance($id);

       $maintenanceAttachment = MaintenanceTransactionAttachment::select('maintenance_transaction_attachment.path_document', 'maintenance_transaction_attachment.id' ,'mt.id as mt_id')
       ->join('maintenance_transaction as mt', 'mt.id', '=', 'maintenance_transaction_attachment.maintenance_transaction_id')
       ->where(['maintenance_transaction_attachment.data_status' => 1, 'mt.data_status' => 1 ,'maintenance_transaction_attachment.maintenance_transaction_id' => $maintenanceTransaction->id  ])->get();

        $maintenanceTransactionHistory = MaintenanceTransaction::from('maintenance_transaction as mt')
        ->join('complaint', 'complaint.id', '=', 'mt.complaint_id')
        ->select('mt.maintenance_date','mt.maintenance_status_id', 'mt.remarks', 'mt.id')
        ->where(['mt.data_status' => 1, 'mt.complaint_id' => $id])->get();

        $maintenanceTransactionHistory -> pop();

        $tab = 'terdahulu' ;

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'id' => $id,
                'complaint' => $complaint,
                'maintenanceTransaction' => $maintenanceTransaction,
                'complaintOthers' => $complaintOthers,
                'complaintInventory' => $complaintInventory,
                'maintenanceTransactionHistory' => $maintenanceTransactionHistory,
                'maintenanceAttachment' => $maintenanceAttachment,
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function ajaxGetMaintenanceTransactionAttachment(Request $request)
    {
        $transaction_id = $request->mt_id;

        $maintenanceTransactionAttachment = MaintenanceTransactionAttachment::where(['data_status' => 1, 'maintenance_transaction_id' => $transaction_id ])->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.modal-maintenance-transaction-attachment')
                ->with(compact('maintenanceTransactionAttachment'))
                ->render();

        return response()->json(['success' => true, 'html' => $data]);
    }

    //GAMBAR BUTIRAN ADUAN KEROSAKAN INVENTORI / LAIN2
    public function ajaxGetComplaintInventoryAttachmentList(Request $request)
    {
        $complaint_inventory_id = $request->cid;

        $complaintInventoryAttachment = ComplaintInventoryAttachment::select('complaint_inventory_attachment.path_document', 'complaint_inventory_attachment.complaint_inventory_id', 'complaint_inventory_attachment.id')
                ->where([
                    ['complaint_inventory_attachment.data_status', 1],
                    ['complaint_inventory_attachment.complaint_inventory_id', '=', $complaint_inventory_id]
                ])
                ->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.modal-complaint-inventory-attachment')
                ->with(compact('complaintInventoryAttachment'))
                ->render();

        return response()->json(['success' => true, 'html' => $data]);
    }

    public function ajaxGetComplaintOthersAttachmentList(Request $request)
    {
        $complaint_others_id = $request->cod;

        $complaintAttachment = ComplaintAttachment::select('complaint_attachment.path_document', 'complaint_attachment.complaint_others_id', 'complaint_attachment.complaint_id', 'complaint_attachment.id')
                ->where([
                    ['complaint_attachment.data_status', 1],
                    ['complaint_attachment.complaint_others_id', '=', $complaint_others_id],
                ])
                ->get();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.modal-complaint-others-attachment')
                ->with(compact('complaintAttachment'))
                ->render();

        return response()->json(['success' => true, 'html' => $data]);
    }
}
