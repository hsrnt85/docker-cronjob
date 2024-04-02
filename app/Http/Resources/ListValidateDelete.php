<?php

namespace App\Http\Resources;

use App\Models\Application;
use App\Models\ApplicationReview;
use App\Models\BankAccount;
use App\Models\CollectorStatement;
use App\Models\Complaint;
use App\Models\InternalJournal;
use App\Models\Journal;
use App\Models\Maintenance;
use App\Models\MaintenanceInventory;
use App\Models\MaintenanceTransaction;
use App\Models\Meeting;
use App\Models\QuartersCategory;
use App\Models\Quarters;
use App\Models\QuartersInventory;
use App\Models\RoutineInspection;
use App\Models\RoutineInspectionTransaction;
use App\Models\User;
use App\Models\Officer;
use App\Models\FinanceOfficer;
use App\Models\MeetingPanel;

class ListValidateDelete
{
    
    public static function validateQuartersClass($id){
       
        $data = QuartersCategory::from('quarters_category as qc')->join('quarters_cat_class as qcc','qc.id','=','qcc.q_cat_id')
            ->select('qc.id')->where(['qc.data_status'=> 1, 'qcc.q_class_id'=>$id])->first();
        
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;
    }

    public static function validateQuartersCategory($id){
       
        $data = Quarters::select('id')->where(['data_status'=> 1, 'quarters_cat_id'=>$id])->first();
    
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;
    }

    public static function validateOfficer($id){
       
        $dataApplicationReview = ApplicationReview::select('id')->where('officer_id', $id)->where('data_status', 1);
        $dataMeeting = Meeting::select('id')->where('officer_id', $id)->where('data_status', 1);
        $dataComplaint = Complaint::select('id')->where('officer_id', $id)->where('data_status', 1);
        $dataMaintenance = Maintenance::select('id')->where('monitoring_officer_id', $id)->where('data_status', 1);
        $dataMaintenanceTransaction = MaintenanceTransaction::select('id')->where('monitoring_officer_id', $id)->where('data_status', 1);
        $dataRoutineInspection = RoutineInspection::select('id')->where('monitoring_officer_id', $id)->where('data_status', 1);
        $dataRoutineInspectionTransaction = RoutineInspectionTransaction::select('id')->where('approval_officer_id', $id)->where('data_status', 1);
                        
        $data = $dataApplicationReview->unionAll($dataMeeting)->unionAll($dataComplaint)->unionAll($dataMaintenance)->unionAll($dataMaintenanceTransaction)->unionAll($dataRoutineInspection)->unionAll($dataRoutineInspectionTransaction)->first();
        $dataCount = ($data) ? 1 : 0;
    
        return $dataCount;
    }

    public static function validateFinanceOfficer($id){
       
        $dataCollectorStatement = CollectorStatement::select('id')->where('preparer_id', $id)->orWhere('checker_id', $id)->orWhere('approver_id', $id)->where('data_status', 1);
        $dataJournal = Journal::select('id')->where('preparer_id', $id)->orWhere('checker_id', $id)->orWhere('approver_id', $id)->where('data_status', 1);
        $dataInternalJournal = InternalJournal::select('id')->where('preparer_id', $id)->orWhere('checker_id', $id)->orWhere('approver_id', $id)->where('data_status', 1);

        $data = $dataCollectorStatement->unionAll($dataJournal)->unionAll($dataInternalJournal)->first();
        $dataCount = ($data) ? 1 : 0;
    
        return $dataCount;
    }

    public static function validatePaymentMethod($id){

        $data = BankAccount::select('id')->where(['data_status'=> 1, 'payment_method_id'=>$id])->first();
        
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;
    }

    public static function validateUserPolicy($id){

        $data = User::select('id')->where(['data_status'=> 1, 'roles_id'=>$id])->first();
        
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;

    }

    public static function validateIncomeAccountCode($id){
       
        $dataCollectorStatement = CollectorStatement::select('cs.id')->from('collector_statement as cs')->join('collector_statement_vot_list as csvl','cs.id','=','csvl.collector_statement_id')
                                    ->where('csvl.income_account_code_id', $id)->where(['cs.data_status'=> 1, 'csvl.data_status'=> 1]);
        $dataJournal = Journal::select('j.id')->from('journal as j')->join('journal_vot_list as jvl', 'jvl.journal_id','=','j.id')
                                    ->where('jvl.income_account_code_id', $id)->where('j.data_status', 1);
        $dataInternalJournal = InternalJournal::select('ij.id')->from('internal_journal as ij')->join('tenants_payment_notice_vot_list AS tpnvl', 'ij.id', '=', 'tpnvl.internal_journal_id')
                                    ->where('tpnvl.income_account_code_id', $id)->where(['ij.data_status'=> 1, 'tpnvl.data_status'=> 1]);

        $data = $dataCollectorStatement->unionAll($dataJournal)->unionAll($dataInternalJournal)->first();
        $dataCount = ($data) ? 1 : 0;
    
        return $dataCount;
    }

    public static function validateInventoryResponsibility($id){

        $data = QuartersInventory::select('id')->where(['data_status' => 1, 'm_inventory_id' => $id])->first();
    
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;

    }

    public static function validateInventory($id){

        $data = QuartersCategory::from('quarters_category as qc')->join('quarters_category_inventory as qci', 'qc.id', '=', 'qci.quarters_category_id')
                            ->select('qc.id')->where(['qc.data_status' => 1, 'qci.data_status' => 1, 'qci.inventory_id' => $id]) 
                            ->first();
        
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;

    }
  
    public static function validateDocument($id){
        $data = Application::select('a.id')->join('application_attachment as at', 'a.id','=','at.a_id')->where(['a.data_status'=> 1, 'at.data_status'=> 1, 'at.d_id'=>$id])->first();
        
        $dataCount = ($data) ? 1 : 0;

        return $dataCount;

    }

    public static function validateUser($id){
       
        $dataApplication = Application::select('id')->where('user_id', $id)->where('data_status', 1);
        $dataOfficer = Officer::select('id')->where('users_id', $id)->where('data_status', 1);
        $dataFinanceOfficer = FinanceOfficer::select('id')->where('users_id', $id)->where('data_status', 1);
        $dataComplaint = Complaint::select('id')->where('users_id', $id)->where('data_status', 1);
        $dataMeetingPanel = MeetingPanel::select('id')->where('users_id', $id)->where('data_status', 1);
              
        $data = $dataApplication->unionAll($dataOfficer)->unionAll($dataFinanceOfficer)->unionAll($dataComplaint)->unionAll($dataMeetingPanel)->first();
        $dataCount = ($data) ? 1 : 0;
    
        return $dataCount;
    }

    public static function validateInvitationPanel($id){
       
        $data = MeetingPanel::select('id')->where('invitation_panel_id', $id)->where('data_status', 1);
              
        $dataCount = ($data) ? 1 : 0;
    
        return $dataCount;
    }

}
