<?php

namespace App\Http\Controllers;
use App\Models\Api\Api_Complaint;
use App\Models\Api\Api_ComplaintAttachment;
use App\Models\Api\Api_MaintenanceTransaction;
use App\Models\Api\Api_ComplaintInventory;
use App\Models\Api\Api_ComplaintInventoryAttachment;
use App\Models\Api\Api_ComplaintOthers;
use App\Models\Api\Api_MaintenanceTransactionAttachment;

use Illuminate\Http\Request;

class Api_MaintenanceTransactionController extends Controller
{

    //--------------------------------------------------------------------------------------------------------------
    //  Penyelenggaraan > Transaksi Penyelenggaraan > Untuk Tindakan
    //--------------------------------------------------------------------------------------------------------------

    public function getMaintenanceTransactionList(Request $request)
    {
        $start_date = $request->startDate;  $end_date = $request->endDate;
        $officer       = auth('sanctum')->user()?->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $allData = [];

        $getMaintenanceList = Api_Complaint::getMaintenanceTransaction($officer_id , $start_date, $end_date );   // belum selesai

        $getMaintenanceList->each(function($complaint, $key) use (&$allData, $officer_id, $officer){

            $maintenanceTransaction   = Api_Complaint::getMaintenanceTransactionById($officer_id, $complaint->id);
            $maintenanceAttach = Api_MaintenanceTransactionAttachment::getMaintenanceAttachmentbyId($complaint?->maintenance_id);

            $item = [];
            $item['sejarah_transaksi']    = ($maintenanceTransaction) ? $maintenanceTransaction : "";
            $item['maintenance_status']   = "BELUM SELESAI";
            $item['maintenance_officer']  = $officer->user->name;
            $item['maintenanceAttach']    = ($maintenanceAttach) ? $maintenanceAttach : "";

            array_push($allData, $item);
        });

        return response()->json([
            'get_transaksi_penyelenggaraan' => $allData,
        ], 200);
    }

    public function getMaintenanceTransactionById(Request $request)
    {
        $complaint_id = $request->complaintId;
        $officer       = auth('sanctum')->user()?->officer();
        $officer_id = ($officer) ? $officer->id : 0;

        $maintenanceTransaction   = Api_Complaint::getMaintenanceTransactionById($officer_id, $complaint_id, 1, 5);

        if($maintenanceTransaction)
        {
            $complaint   = Api_Complaint::getMaintenanceTransactionDetailsById($maintenanceTransaction->id);
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

            $getAllTransactionByComplaintID = Api_MaintenanceTransaction::from('maintenance_transaction as mt')->leftJoin('maintenance_status as status', 'status.id', '=', 'mt.maintenance_status_id')
            ->select('mt.id', 'mt.maintenance_date', 'mt.complaint_id', 'mt.maintenance_status_id', 'mt.remarks', 'mt.monitoring_officer_id', 'status.status')->where(['mt.complaint_id' => $complaint_id, 'mt.data_status' => 1])->orderBy('mt.id', 'asc')->get();

            $getAllTransactionByComplaintID->each(function($mt, $key)use ($officer){
                $maintenanceAttach = Api_MaintenanceTransactionAttachment::getMaintenanceAttachmentbyId($mt->id);
                $mt['maintenance_officer']  = $officer->user?->name;
                $mt['transaksi_attachment'] = ($maintenanceAttach) ? $maintenanceAttach : "" ;
            });

            return response()->json([
                'complaint'             => $complaint,
                'complaint_inventory'   => $complaintInventory,
                'complaint_others'      => $complaintOthers,
                'sejarah_transaksi'     => $getAllTransactionByComplaintID,

            ], 200);
        }else{

            return response()->json([
                'complaint'    => "",
            ], 200);
        }
    }

    public function submitMaintenanceTransaction(Request $request)
    {
        $officerId   = auth('sanctum')->user()?->officer()->id;
        $complaintId = $request->complaintId;
        $maintenance_file = $request->maintenance_file;
        $maintenance_date = $request->maintenance_date;

        //INSERT INTO MAINTENANCE TRANSACTION  --------------------------------
        $maintenanceTransaction = new  Api_MaintenanceTransaction;

        $maintenanceTransaction->complaint_id                           = $complaintId;
        $maintenanceTransaction->maintenance_date                       = $maintenance_date;
        $maintenanceTransaction->monitoring_officer_id                  = $officerId;
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

                $attachment = new Api_MaintenanceTransactionAttachment;

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
            Api_Complaint::where('id', $complaintId)->update(['flag_maintenance' => 1]); // ongoing
        }
        else
        {
            Api_Complaint::where('id', $complaintId)->update(['flag_maintenance' => 2, 'complaint_status_id' => 3]); // selesai
        }

        //UPDATE INTO COMPLAINT INVENTORY / OTHERS  ---------------------------------
        Api_ComplaintInventory::where(['data_status' => 1, 'complaint_id' => $complaintId])->update(['is_maintenance' => 1]);

        Api_ComplaintOthers::where(['data_status' =>1 , 'complaint_id' => $complaintId])->update(['is_maintenance' => 1]);


            if($saved){
                    return response()->json([
                        'status' => true,
                        'message' => "Transaksi Penyelenggaran berjaya dikemaskini!",
                    ], 200);
            }
            else{
                return response()->json([
                    'status'    => false,
                    'message'   => "Transaksi Penyelenggaran tidak berjaya dikemaskini!",
                ], 500);
            }
    }

    //--------------------------------------------------------------------------------------------------------------
    //  Penyelenggaraan > Transaksi Penyelenggaraan > Senarai Terdahulu
    //--------------------------------------------------------------------------------------------------------------

    public function getMaintenanceTransactionHistoryList(Request $request)
    {
        $start_date = $request->startDate;  $end_date = $request->endDate;
        $officer       = auth('sanctum')->user()?->officer();
        $officer_id = ($officer) ? $officer->id : 0;
        $allData = [];

        $maintenanceStatus = 2; // selesai
        $complaintStatus = 3; // selesai
        $getMaintenanceList = Api_Complaint::getMaintenanceTransactionHistory($officer_id , $start_date, $end_date,  $maintenanceStatus, $complaintStatus);   // selesai

        $getMaintenanceList->each(function($complaint, $key) use (&$allData, $officer_id, $officer,$maintenanceStatus, $complaintStatus){

            $maintenanceTransaction   = Api_Complaint::getMaintenanceTransactionHistoryById($officer_id, $complaint->id, $maintenanceStatus, $complaintStatus);

            $item = [];
            $item['sejarah_transaksi']     = ($maintenanceTransaction) ? $maintenanceTransaction : "";
            $item['maintenance_status']    = "SELESAI";
            $item['maintenance_officer']   = $officer->user->name;

            array_push($allData, $item);
        });

        return response()->json([
            'get_sejarah_transaksi_penyelenggaraan' => $allData,
        ], 200);
    }

    public function getMaintenanceTransactionHistoryById(Request $request)
    {
        $complaint_id = $request->complaintId;
        $officer       = auth('sanctum')->user()?->officer();
        $officer_id = ($officer) ? $officer->id : 0;

        $maintenanceTransaction   = Api_Complaint::getMaintenanceTransactionHistoryById($officer_id, $complaint_id, 2, 3);

        if($maintenanceTransaction)
        {
            $complaint   = Api_Complaint::getMaintenanceTransactionDetailsById($maintenanceTransaction->id);
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

            $maintenanceAttach = Api_MaintenanceTransactionAttachment::getMaintenanceAttachmentbyId($maintenanceTransaction?->maintenance_id);

            return response()->json([
                'complaint'    => $complaint,
                'complaint_inventory'                    => $complaintInventory,
                'complaint_others'                       => $complaintOthers,
                'maintenance_date'                       => $maintenanceTransaction->maintenance_date,
                'maintenance_status'                     => "SELESAI",
                'maintenance_officer'                    => $officer->user?->name,
                'maintenance_attachment'                 => $maintenanceAttach,

            ], 200);
        }else{
            return response()->json([
                'complaint'  => "",
            ], 200);
        }
    }

}
