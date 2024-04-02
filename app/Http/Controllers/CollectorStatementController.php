<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CollectorStatementRequest;
use App\Http\Resources\ListData;
use App\Models\BankAccount;
use App\Models\CollectorStatement;
use App\Models\CollectorStatementLog;
use App\Models\CollectorStatementVotList;
use App\Models\District;
use App\Models\FinanceDepartment;
use App\Models\FinanceOfficer;
use App\Models\FinanceOfficerCategory;
use App\Models\PaymentMethod;
use App\Models\TenantsPaymentTransaction;
use App\Models\TransactionStatus;
use Illuminate\Support\Facades\DB;
use App\Notifications\CollectorStatementNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class CollectorStatementController extends Controller
{

    public function index(Request $request)
    {

        $search_ref_no = ($request->ref_no) ? $request->ref_no : null;
        $search_date   = ($request->search_date) ? convertDatepickerDb($request->search_date) : null;

        //Show button rekod baru to peg. penyedia
        $is_preparer_officer =  FinanceOfficer::current_fin_officer_by_category(1, loginId());
        $current_fin_officer = FinanceOfficer::current_fin_officer(loginId());

        $collector_statement_list = CollectorStatement::get_collector_statement_active($search_ref_no, $search_date, $current_fin_officer?->id);
        $collector_statement_history = CollectorStatement::get_collector_statement_history($search_ref_no, $search_date, $current_fin_officer?->id);

        return view(getFolderPath().'.list',
        [
            'collector_statement_list' => $collector_statement_list,
            'collector_statement_history' => $collector_statement_history,
            'is_preparer_officer' => $is_preparer_officer,
            'current_fin_officer' => $current_fin_officer,
            'search_ref_no' => $search_ref_no,
            'search_date' => convertDateSys($search_date),
        ]);
    }

    public function create()
    {
        $pegawaiPenyediaList = ListData::get_finance_officer_by_district(districtId(), 1);
        $pegawaiPenyemakList = ListData::get_finance_officer_by_district(districtId(), 2);
        $finance_department = FinanceDepartment::finance_department_by_district(districtId());
        $currentOfficer = FinanceOfficer::current_fin_officer(loginId());
        $getPaymentMethod = PaymentMethod::getIspeksPaymentMethod();

        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create',
            [
                'pegawaiPenyediaList' => $pegawaiPenyediaList,
                'pegawaiPenyemakList' => $pegawaiPenyemakList,
                'finance_department'  => $finance_department,
                'currentOfficer' => $currentOfficer,
                'getPaymentMethod' => $getPaymentMethod,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(CollectorStatementRequest $request)
    {
        try { 
                $transaction_status     = 1; // simpan
                $district           = District::where('id', districtId())->first();
                $pegawaiPenyedia    = FinanceOfficer::current_fin_officer_by_category(1, loginId());

                //-------------------------------------------------------------------------------------------------------------------------------
                // CHUNK THE ARRAY (TENANTS PAYMENT NOTICE) INTO SMALLER CHUNKS OF 200
                //-------------------------------------------------------------------------------------------------------------------------------
                $tptIds = $request->tpt_id;
                $chunks = array_chunk($tptIds, 200);

                // Process each chunk
                foreach ($chunks as $i => $chunk) {
                    //-----------------------------------------------------------------------------------------------------------
                    // SAVE COLLECTOR STATEMENT
                    //-----------------------------------------------------------------------------------------------------------
                    $curr_running_no    = $this->_getCurrentRunningNo();
                    $ref_no             = $this->_generateRefNo($curr_running_no, $district);

                    $collectorStatement = new CollectorStatement;
                    $collectorStatement->district_id                   = districtId();
                    $collectorStatement->ispeks_integration_id         = 0;
                    $collectorStatement->collector_statement_no        = $ref_no;
                    $collectorStatement->running_no                    = $curr_running_no;
                    $collectorStatement->collector_statement_date      = currentDateDb();
                    $collectorStatement->collector_statement_date_from = convertDatepickerDb($request->date_from);
                    $collectorStatement->collector_statement_date_to   = convertDatepickerDb($request->date_to);
                    $collectorStatement->bank_slip_date                = convertDatepickerDb($request->bank_slip_date);
                    $collectorStatement->payment_method_id             = $request->payment_method;
                    $collectorStatement->bank_slip_no                  = $request->bank_slip;
                    $collectorStatement->transit_bank_id               = $request->bank_name;
                    $collectorStatement->description                   = $request->purpose;
                    $collectorStatement->preparer_id                   = $request->preparer;
                    $collectorStatement->checker_id                    = $request->checker;
                    $collectorStatement->transaction_status_id         = $transaction_status; // simpan
                    $collectorStatement->data_status                   = 1;
                    $collectorStatement->action_by                     = loginId();
                    $collectorStatement->action_on                     = currentDate();

                    $saved = $collectorStatement->save();

                    setUserActivity("A", "Penyata Pemungut: ".$collectorStatement->collector_statement_no);


                    if($saved)
                    {
                        //-------------------------------------------------------------------------------------------------------
                        // UPDATE COLLECTOR STATEMENT ID IN TENANTS PAYMENT TRANSACTION
                        //-------------------------------------------------------------------------------------------------------
                        TenantsPaymentTransaction::whereIn('id', $chunk)->update(['collector_statement_id' => $collectorStatement->id]);

                        //-------------------------------------------------------------------------------------------------------
                        // GET TOTAL AMOUNT VOT HASIL / SENARAI KUTIPAN BY EACH CHUNK
                        //-------------------------------------------------------------------------------------------------------
                        $total_amount_each_chunk = TenantsPaymentTransaction::get_total_payment_by_collector_statement_id($collectorStatement->id);

                        // UPDATE TOTAL AMOUNT
                        CollectorStatement::where('id', $collectorStatement->id)->update(['collection_amount' => $total_amount_each_chunk?->total_payment]);

                        //-------------------------------------------------------------------------------------------------------
                        // SAVE COLLECTOR STATEMENT VOT LIST  BY EACH CHUNK
                        //-------------------------------------------------------------------------------------------------------
                        $vot_hasil = TenantsPaymentTransaction::get_payment_by_vot_using_collector_statement_id($collectorStatement->id);

                        if($vot_hasil){

                            foreach($vot_hasil as $i => $vot){
                                    $amaun  = 0;
                                    $amaun  = removeComma($vot->total_amount);
                                    $id_vot = $vot->income_account_code_id;

                                if($amaun>0 && $id_vot>0){

                                    $collectorStatement_VotList = new CollectorStatementVotList;
                                    $collectorStatement_VotList->collector_statement_id        = $vot->collector_statement_id;
                                    $collectorStatement_VotList->income_account_code_id        = $id_vot;
                                    $collectorStatement_VotList->total_amount                  = $amaun;
                                    $collectorStatement_VotList->data_status                   = 1;
                                    $collectorStatement_VotList->action_by                     = loginId();
                                    $collectorStatement_VotList->action_on                     = currentDate();

                                    $collectorStatement_VotList->save();
                                }
                            }
                        }

                        //-----------------------------------------------------------------------------------------------------------
                        // SAVE COLLECTOR STATEMENT LOG
                        //-----------------------------------------------------------------------------------------------------------
                        $collectorStatement_Log = new CollectorStatementLog;
                        $collectorStatement_Log->collector_statement_id        = $collectorStatement->id;
                        $collectorStatement_Log->transaction_status_id         = $transaction_status; // simpan
                        $collectorStatement_Log->date                          = currentDate();
                        $collectorStatement_Log->finance_officer_category_id   = 1; //1:pegawai penyedia
                        $collectorStatement_Log->finance_officer_category_name = $pegawaiPenyedia->finance_category->category_name;
                        $collectorStatement_Log->finance_officer_id            = $pegawaiPenyedia->id;
                        $collectorStatement_Log->finance_officer_name          = $pegawaiPenyedia->name;
                        $collectorStatement_Log->position_name                 = $pegawaiPenyedia->position->position_name;
                        $collectorStatement_Log->data_status                   = 1;
                        $collectorStatement_Log->action_by                     = loginId();
                        $collectorStatement_Log->action_on                     = currentDate();

                        $saveLog = $collectorStatement_Log->save();
                    }
            }
            if($saveLog) return redirect()->route('collectorStatement.index')->with('success', 'Penyata Pemungut berjaya ditambah! ');
        } catch (\Exception $e) {

            // something went wrong
            return redirect()->route('collectorStatement.create')->with('error', 'Penyata Pemungut tidak berjaya ditambah!' . ' ' .  $e->getLine().$e->getMessage());
        }
    }

    public function edit(Request $request)
    {

        $collector_statement_id = $request->id;
        $collectorStatement = CollectorStatement::collector_statement_by_id($collector_statement_id);
        $log_kuiri = CollectorStatementLog::get_collector_statement_log_kuiri($collector_statement_id); // TABLE LOG KUIRI
        $collectorStatementLog = CollectorStatementLog::get_collector_statement_log($collector_statement_id);
        $current_log = CollectorStatementLog::current_collector_statement_log($collector_statement_id);
        $finance_department = FinanceDepartment::finance_department_by_district($collectorStatement->district_id);

        $checkingList = TransactionStatus::checking_list(); // RADIO SEMAKAN
        $approvalList = TransactionStatus::approval_list(); // RADIO KELULUSAN
        $pegawaiPenyemakList = ListData::get_finance_officer_by_district(districtId(), 2);
        $pegawaiPelulusList = ListData::get_finance_officer_by_district(districtId(), 3);

        $current_status = CollectorStatement::current_transaction_status($collector_statement_id);
        $currentOfficer = FinanceOfficer::current_fin_officer(loginId());
        $login_officer = $currentOfficer?->id;

        $collectorStatementVotList  = CollectorStatementVotList::get_collector_statement_vot_list($collector_statement_id); // TABLE VOT HASIL
        $tenantsPaymentNotice = TenantsPaymentTransaction::get_tenants_payment_transaction($collector_statement_id); // TABLE SENARAI KUTIPAN

        //------------------------------------------------------------------------------------------------------------------------
        //CHECKING FINANCE OFFICER ID BY LATEST TRANSACTION/PHASE/PROCESS (SEDIA->SEMAK->LULUS)
        //CHECKING FROM PHASE PELULUS -> PENYEDIA (DESCENDING)
        //------------------------------------------------------------------------------------------------------------------------
        $approver =  $collectorStatement->approver_id;
        $checker  =  $collectorStatement->checker_id;
        $preparer =  $collectorStatement->preparer_id;
        $proses_simpan = ($current_status == 1) && ($login_officer == $checker) ? true : false;

        if($collectorStatement->approver_id>0) // PROSES LULUS
        {
            if($login_officer == $approver){
               $officer_on_duty = $approver;   $finance_cat_id = 3;
            }
            else{ // PEG. PENYEMAK CAN EDIT
               $officer_on_duty = $checker;    $finance_cat_id = 2;
            }
        }
        elseif($collectorStatement->checker_id>0) // PROSES SEMAK
        {
            if($proses_simpan == true){ // MASIH DALAM PROSES SIMPAN (PENYEDIA BELUM HANTAR KE PENYEMAK)
                $officer_on_duty = $preparer; $finance_cat_id = 1;
            }else{
                if($login_officer == $checker){
                    $officer_on_duty = $checker;    $finance_cat_id = 2;
                }
                else{  // PEG. PENYEDIA CAN EDIT
                    $officer_on_duty = $preparer;   $finance_cat_id = 1;
                }
            }
        }
        elseif($collectorStatement->preparer_id>0){  // PROSES SEDIA
            $officer_on_duty = $preparer; $finance_cat_id = 1;  // PEG. PENYEDIA CAN EDIT
        }

        //-----------------------------------------------------------------------------------------------------------------------
        //CHECKING IF LOGIN OFFICER IS OFFICER ON DUTY DURING THAT PHASE
        //-----------------------------------------------------------------------------------------------------------------------
        $officer_on_duty_sedia = (($login_officer == $officer_on_duty && $finance_cat_id == 1 )) ? true : false;
        $officer_on_duty_semak = (($login_officer == $officer_on_duty && $finance_cat_id == 2 )) ? true : false;
        $officer_on_duty_lulus = (($login_officer == $officer_on_duty && $finance_cat_id == 3 )) ? true : false;

        //-----------------------------------------------------------------------------------------------------------------------
        //IF TRUE, LOGIN OFFICER CAN EDIT PAGE
        //TO DISABLE AND ENABLE INPUT BOX FOR LOGIN OFFICER WHO WAS ON DUTY
        //-----------------------------------------------------------------------------------------------------------------------
        $proses_sedia = ((($current_status == 1 || $current_status == 5) && $officer_on_duty_sedia) || ($current_status == 2 && (!$officer_on_duty_semak && !$officer_on_duty_lulus)));
        $proses_semak = ((($current_status == 2 || $current_status == 3) && $officer_on_duty_semak) );
        $proses_lulus = ($current_status == 3 && ($officer_on_duty_lulus) );

        //-----------------------------------------------------------------------------------------------------------------------
        // BACK TO CURRENT TAB WHEN CLICK BUTTON 'KEMBALI
        //-----------------------------------------------------------------------------------------------------------------------
        $tab_id = $request->tab;
        if($request->tab == 1){ $tab = 'tindakan'; } else { $tab = 'terdahulu'; }

        // if(checkPolicy("U"))
        // {
            return view(getFolderPath().'.edit',
            [
                'collectorStatement'    => $collectorStatement,
                'collectorStatementLog' => $collectorStatementLog,
                'log_kuiri' => $log_kuiri,
                'login_officer' => $login_officer,
                'checkingList'  => $checkingList,
                'approvalList'  => $approvalList,
                'pegawaiPelulusList'  => $pegawaiPelulusList,
                'pegawaiPenyemakList' => $pegawaiPenyemakList,
                'current_status' => $current_status,
                'current_log'  => $current_log,
                'proses_sedia' => $proses_sedia,
                'proses_semak' => $proses_semak,
                'proses_lulus' => $proses_lulus,
                'totalItems' => count($log_kuiri),
                'currentKey' => 0,
                'collectorStatementVotList' => $collectorStatementVotList,
                'tenantsPaymentNotice' => $tenantsPaymentNotice,
                'finance_department'  => $finance_department,
                'tab' => $tab,
                'tab_id' => $tab_id,
            ]);
        // }
        // else
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }
    }

    public function update(Request $request)
    {
        try {
                $btnType = $request->btn_type;
                $tab_id  = $request->tab_id;
                $collector_statement_id = $request->id;

                $ref_numb               = CollectorStatement::collector_statement_ref_no($collector_statement_id);
                $current_status         = CollectorStatement::current_transaction_status($collector_statement_id);
                $collectorStatement     = CollectorStatement::collector_statement_by_id($collector_statement_id);

                $pegawai_penyedia = FinanceOfficer::current_fin_officer_by_category(1, loginId());
                $pegawai_penyemak = FinanceOfficer::current_fin_officer_by_category(2, loginId());
                $pegawai_pelulus  = FinanceOfficer::current_fin_officer_by_category(3, loginId());

                $kategori_pegawai_penyedia =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
                $kategori_pegawai_penyemak =  FinanceOfficerCategory::FinanceOfficerByCategory(2);
                $kategori_pegawai_pelulus  =  FinanceOfficerCategory::FinanceOfficerByCategory(3);

                $checking_status      = $request->checking_status;
                $approval_status      = $request->approval_status;
                $kuiri_remarks        = ($request->kuiri_remarks) ? $request->kuiri_remarks : null;
                // $jumlah_keseluruhan   = ($request->jumlah_keseluruhan != null) ? $request->jumlah_keseluruhan : ""; //from js

                $old_checker       = $collectorStatement->checker_id;
                $new_checker       = $request->checker;
                $old_approver      = $collectorStatement->approver_id;
                $new_approver      = $request->approver;
                $preparer          = $collectorStatement->preparer_id;
                $is_same_checker   = ($old_checker == $new_checker) ? true : false;
                $is_same_approver  = ($old_approver == $new_approver) ? true : false;
                $noti_kuiri        = FinanceOfficer::finance_officer_by_id($preparer);

                //-------------------------------------------------------------------------------------------------------------------------------------------------
                // PROSES PEGAWAI PENYEDIA = CURRENT_STATUS [1,2,5]                                                                                              //
                //-------------------------------------------------------------------------------------------------------------------------------------------------

                if($request->proses_sedia == 1 || $current_status == 1)
                {
                    //-----------------------------------------------------------------------------------------------------------
                    // UPDATE COLLECTOR STATEMENT
                    //-----------------------------------------------------------------------------------------------------------
                    // $collectorStatement->collector_statement_date_from    = convertDatepickerDb($request->date_from);
                    // $collectorStatement->collector_statement_date_to      = convertDatepickerDb($request->date_to);
                    $collectorStatement->description                      = $request->purpose;
                    // $collectorStatement->collection_amount                = $jumlah_keseluruhan;
                    $collectorStatement->checker_id                       = $request->checker;
                    $collectorStatement->transaction_status_id            = ($btnType == "simpan") ?  1 : 2;
                    $collectorStatement->data_status                      = 1;
                    $collectorStatement->action_by                        = loginId();
                    $collectorStatement->action_on                        = currentDate();

                    $saved = $collectorStatement->save();

                    if($saved)
                    {
                        //--------------------------------------------------------------------------------------------------------
                        // UPDATE COLLECTOR STATEMENT VOT LIST // FLOW CHANGES (TAKBOLEH EDIT TARIKH -> SHOULD REMOVE) 28/6
                        //--------------------------------------------------------------------------------------------------------
                        // if($request->id_vot!=null){ //append from js

                        //     foreach($request->id_vot as $i => $id_vot)
                        //     {
                        //         $amaun_by_vot = 0;
                        //         $amaun_by_vot = removeComma($request->jumlah_amaun[$i]); //append from js

                        //         $current_vot_list_by_id_vot = CollectorStatementVotList::where(['collector_statement_id'=>$collector_statement_id, 'income_account_code_id'=>$id_vot])->first();

                        //         if($amaun_by_vot>0 && $id_vot>0)
                        //         {
                        //             //------------------------------------------------------------------------
                        //             // Update or Insert New Votlist
                        //             //------------------------------------------------------------------------
                        //             if($current_vot_list_by_id_vot)
                        //             {
                        //                 $updateVot = CollectorStatementVotList::updateOrInsert(
                        //                     //values used to filter a record
                        //                     ['collector_statement_id'=>$collector_statement_id, 'income_account_code_id'=>$id_vot],
                        //                     //values to be updated or inserted
                        //                     ['collector_statement_id'=>$collector_statement_id, 'income_account_code_id'=>$id_vot, 'total_amount'=>$amaun_by_vot, 'action_by'=>loginId(), 'action_on'=>currentDate()]
                        //                 );
                        //             }
                        //         }
                        //         else
                        //         {
                        //             //----------------------------------------------------------------------
                        //             // Delete Old Votlist that has New Amount = 0.00 By Vot
                        //             //----------------------------------------------------------------------
                        //             $deleteVot = CollectorStatementVotList::where(['collector_statement_id'=>$collector_statement_id, 'income_account_code_id'=>$id_vot])
                        //                             ->update(['data_status' => 0, 'delete_by' => loginId(), 'delete_on' => currentDate()]);
                        //         }
                        //     }
                        // }
                    }
                    //--------------------------------------------------------------------------------------------------------
                    // UPDATE COLLECTOR STATEMENT LOG
                    //--------------------------------------------------------------------------------------------------------

                    if($collectorStatement->transaction_status_id == 2) // STATUS SAH SIMPAN / KEMASKINI
                    {
                        $check_current_log = CollectorStatementLog::where(['collector_statement_id'=> $collector_statement_id, 'transaction_status_id' => 2])->first();

                        if($check_current_log){ // update
                            $check_current_log->action_by = loginId();
                            $check_current_log->action_on = currentDate();
                            $saveLog = $check_current_log->save();
                        }else{ // save new
                            $new_log = $this->_saveCollectorLog($collectorStatement, 2, currentDate(), $kuiri_remarks, $kategori_pegawai_penyedia, $pegawai_penyedia );
                            $saveLog  =  $new_log->save();
                        }
                        // $save_log   = $saved;
                        //----------------------------------------------------------------------
                        //SEND NOTIFICATION TO CHEKCER
                        //----------------------------------------------------------------------
                        if($saveLog) {

                            $noti_new_checker = FinanceOfficer::finance_officer_by_id($new_checker);
                            $noti_old_checker = FinanceOfficer::finance_officer_by_id($old_checker);
                            $action = "semak";

                            if($is_same_checker == false) {
                                //SEND NOTI TO NEW CHECKER
                                $noti_new_checker->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "baru", $action, $tab_id));
                                //SEND NOTI BATAL TO OLD CHECKER
                                //BLOCK PAGE NOTI FOR OLD CHECKER
                                if($old_checker) {
                                    $noti_old_checker->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "batal", $action, $tab_id));
                                }
                            }else{
                                //SEND NOTI 'KEMASKINI' TO CURRENT CHECKER
                                $noti_new_checker->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "kemaskini", $action, $tab_id));
                            }
                        }
                    }
                    else
                    {
                        //UPDATE CURRENT LOG && MASIH STATUS SIMPAN
                        $saveLog = CollectorStatementLog::where('collector_statement_id', $collector_statement_id)->orderBy('id', 'desc')
                                                                        ->update(['action_by' => loginId(), 'action_on' => currentDate()]);
                    }
                }

                //------------------------------------------------------------------------------------------------------------------------------------------------
                //PROSES PEGAWAI PENYEMAK = CURRENT_STATUS [2,3]                                                                                                //
                //------------------------------------------------------------------------------------------------------------------------------------------------

                elseif($request->proses_semak == 1)
                {
                    //-----------------------------------------------------------------------------------------------------------
                    // UPDATE COLLECTOR STATEMENT
                    //-----------------------------------------------------------------------------------------------------------
                    if($checking_status == 3){ // 3: Semak
                        $collectorStatement->approver_id  = $request->approver;
                        $updateCollectorStatement         = $this->_saveCollectorStatement($collectorStatement, $checking_status );
                    }
                    else if($checking_status == 5){ // 5: Kuiri
                        $updateCollectorStatement = $this->_resetKuiri($collectorStatement);
                    }

                    $updated = $updateCollectorStatement->save();

                    //--------------------------------------------------------------------------------------------------------
                    // INSERT COLLECTOR STATEMENT LOG
                    //--------------------------------------------------------------------------------------------------------
                    if($updated)
                    {
                        $collectorStatement_Log = $this->_saveCollectorLog($collectorStatement, $checking_status, currentDate(), $kuiri_remarks, $kategori_pegawai_penyemak, $pegawai_penyemak );

                        $saveLog = $collectorStatement_Log->save();

                        //----------------------------------------------------------------------
                        //SEND NOTIFICATION TO APPROVER
                        //----------------------------------------------------------------------
                        $noti_new_approver = FinanceOfficer::finance_officer_by_id($new_approver);
                        $noti_old_approver = FinanceOfficer::finance_officer_by_id($old_approver);

                        $action = "lulus";

                        if($checking_status == 3){
                            if($is_same_approver == false) {
                                //SEND NOTI TO NEW APPROVER
                                $noti_new_approver->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "baru", $action, $tab_id));
                                //SEND NOTI BATAL TO OLD APPROVER
                                //BLOCK PAGE NOTI FOR OLD CHECKER
                                if($old_approver) {
                                    $noti_old_approver->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "batal", $action, $tab_id));
                                }
                            }else{
                                //SEND NOTI TO CURRENT APPROVER
                                $noti_new_approver->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "baru", $action, $tab_id));
                            }
                        }else{
                            $noti_kuiri->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "kuiri", "", $tab_id));
                        }
                    }
                }

                //------------------------------------------------------------------------------------------------------------------------------------------------
                //PROSES PEGAWAI PELULUS = CURRENT_STATUS [3,4]                                                                                                 //
                //------------------------------------------------------------------------------------------------------------------------------------------------

                elseif($request->proses_lulus == 1)
                {
                    //-----------------------------------------------------------------------------------------------------------
                    // UPDATE COLLECTOR STATEMENT
                    //-----------------------------------------------------------------------------------------------------------
                    if($approval_status == 5){   // 5: Kuiri
                        $updateCollectorStatement = $this->_resetKuiri($collectorStatement);
                    } else {                     // 4: Lulus
                        $updateCollectorStatement = $this->_saveCollectorStatement($collectorStatement, $approval_status);
                    }

                    $updated = $updateCollectorStatement->save();

                    //--------------------------------------------------------------------------------------------------------
                    // INSERT COLLECTOR STATEMENT LOG
                    //--------------------------------------------------------------------------------------------------------
                    if($updated)
                    {
                        $collectorStatement_Log = $this->_saveCollectorLog($collectorStatement, $approval_status, currentDate(), $kuiri_remarks, $kategori_pegawai_pelulus, $pegawai_pelulus );
                        $saveLog = $collectorStatement_Log->save();

                        //----------------------------------------------------------------------
                        //SEND NOTIFICATION TO PREPARER (KUIRI)
                        //----------------------------------------------------------------------
                        if($approval_status == 5){
                            $noti_kuiri->user?->notify(new CollectorStatementNotification($collector_statement_id, $ref_numb, "kuiri", "", $tab_id));
                        }
                    }
                }

                if($saveLog)
                {
                    if($btnType == "simpan")
                        { return redirect()->route('collectorStatement.index')->with('success', 'Penyata Pemungut berjaya disimpan! '); }
                    else if($btnType == "hantar")
                        { return redirect()->route('collectorStatement.index')->with('success', 'Penyata Pemungut berjaya dihantar! ');}
                    else  // kemaskini
                        { return redirect()->route('collectorStatement.index')->with('success', 'Penyata Pemungut berjaya dikemaskini! ');}
                }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            if($btnType == "simpan")
                { return redirect()->route('collectorStatement.edit',['id'=>$collector_statement_id] )->with('error', 'Penyata Pemungut tidak berjaya disimpan!' . ' ' . $e->getMessage()); }
            else
                { return redirect()->route('collectorStatement.edit',['id'=>$collector_statement_id] )->with('error', 'Penyata Pemungut tidak berjaya dihantar!' . ' ' . $e->getMessage());}
        }

    }

     //Batal Penyata Pemungut
     public function cancel(Request $request)
     {
        $id = $request->id;
        $cancel_remarks = $request->cancel_remarks;
        $collector_statement = CollectorStatement::where('id', $id)->where('data_status', 1)->first();

        if($collector_statement->transaction_status_id == 1){

        $cancel_status = 6; // BATAL
        $finance_category =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
        $finance_officer  =  FinanceOfficer::current_fin_officer_by_category(1, loginId());

        }else if($collector_statement->transaction_status_id == 4){

        $cancel_status = 7; // BATAL SELEPAS LULUS
        $finance_category  =  FinanceOfficerCategory::FinanceOfficerByCategory(3);
        $finance_officer   =  FinanceOfficer::current_fin_officer_by_category(3, loginId());

        }else if($collector_statement->transaction_status_id == 5){

        $cancel_status = 8; // BATAL SELEPAS SAH SIMPAN
        $finance_category =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
        $finance_officer  =  FinanceOfficer::current_fin_officer_by_category(1, loginId());
        }

        // UPDATE COLLECTOR STATEMENT
        $collector_statement->transaction_status_id    =  $cancel_status;

        setUserActivity("C", "Penyata Pemungut: ".$collector_statement->collector_statement_no);

        $collector_statement->data_status              =  2;
        $collector_statement->delete_by                = loginId();
        $collector_statement->delete_on                = currentDate();
        $saved = $collector_statement->save();

        if($saved){
            // UPDATE TENANT PAYMENT TRANSACTION
            TenantsPaymentTransaction::where(['collector_statement_id'=> $id, 'data_status' => 1])->update(['collector_statement_id' => 0]);

            // SAVE LOG
            $saveLog = $this->_saveCollectorLog($collector_statement, $cancel_status, currentDate(), $cancel_remarks, $finance_category, $finance_officer);
            $saveLog->save();
        }

        if(!$saveLog) {
            return redirect()->route('collectorStatement.index')->with('error', 'Penyata Pemungut tidak berjaya dibatalkan!');
        } else {
            return redirect()->route('collectorStatement.index')->with('success', 'Penyata Pemungut berjaya dibatalkan!');
        }
     }

     public function generate_pdf(Request $request)
     {
        $id = $request->id;

        $collector_statement = CollectorStatement::collector_statement_by_id($id); 
        $preparer = CollectorStatementLog::get_collector_statement_log_by_status($id, 2);
        $checker  = CollectorStatementLog::get_collector_statement_log_by_status($id, 3);
        $approver = CollectorStatementLog::get_collector_statement_log_by_status($id, 4);
        $finance_department = FinanceDepartment::finance_department_by_district($collector_statement->district_id);

        $bank_account_transit = BankAccount::get_bank_account(0, $collector_statement->payment_method_id, 'first'); // 2:Akaun Transit
        $bank_account_main = BankAccount::get_bank_account(1, null, 'first'); // 1:Akaun Utama
        // $payment_date = TenantsPaymentTransaction::where('collector_statement_id', $id)->first()->payment_date;
        $collectorStatementVotList  = CollectorStatementVotList::get_collector_statement_vot_list($id); // TABLE VOT HASIL
        $tenantsPaymentNotice = TenantsPaymentTransaction::get_tenants_payment_transaction($id); // TABLE SENARAI KUTIPAN
        // dd($bank_account_main);
        //Pecah 15
        $chunks = collect($tenantsPaymentNotice)->chunk(10);
        $chunk_notice_by_10 = $chunks->toArray();
        $total_page = count($chunk_notice_by_10) + 1; // PAGE SLIP BANK + PAGE COLLECTOR STATEMENT

        $data = [
            'collector_statement' => $collector_statement,
            'finance_department'  => $finance_department,
            'preparer' => $preparer,
            'checker' => $checker,
            'approver' => $approver,
            'collectorStatementVotList' => $collectorStatementVotList,
            'tenantsPaymentNotice' => $tenantsPaymentNotice,
            'chunk_notice_by_10' => $chunk_notice_by_10,
            'total_page'  => $total_page,
            'bank_account_transit' => $bank_account_transit,
            'bank_account_main' => $bank_account_main,
            // 'payment_date' => convertDateSys($payment_date)
         ];

        $pdf = PDF::loadView('download-pdf.penyata-pemungut.main-page', $data)->setPaper('A4', 'potrait');
        return $pdf->stream($collector_statement->collector_statement_no.'.pdf');
     }

    // MAKLUMAT BANK
    public function get_maklumat_bank(Request $request){

        $kaedah_bayaran = "";

        if($request->has('pm')) {
            $kaedah_bayaran = $request->get('pm');
        }

        $bank_account_transit = BankAccount::get_bank_account(2, $kaedah_bayaran, 'get'); // 2:Akaun Transit

        return response()->json(['success' => true, 'bank_account_transit' => $bank_account_transit]);
    }
    // MAKLUMAT VOT HASIL - TAB 1
    public function get_kutipan_hasil_by_vot(Request $request){

        $tarikh_dari = "";
        $tarikh_hingga = "";
        $kaedah_bayaran = "";

        if( $request->has('df') && $request->has('dt') && $request->has('pm'))
        {
            $tarikh_dari = convert_date_db($request->get('df'));
            $tarikh_hingga = convert_date_db($request->get('dt'));
            $kaedah_bayaran = $request->get('pm');
        }

        $jumlah_amaun = 0; $jumlah_keseluruhan = 0;
        $bil=0;            $id_vot = 0;
        $kod_hasil = "";   $senaraiData = collect();

        $senaraiRekod = CollectorStatement::get_vot_hasil_by_tarikh_and_vot($tarikh_dari, $tarikh_hingga, districtId(), $kaedah_bayaran);

        if($senaraiRekod){
            foreach($senaraiRekod as $val){
                $bil++;
                $kod_hasil = $val->income_code.' - '.$val->income_code_description;
                $id_vot = $val->income_account_code_id;
                $jumlah_amaun = removeComma($val->total_amount);

                $jumlah_keseluruhan += $jumlah_amaun;

                $senaraiData->push([
                    $bil, $kod_hasil, numberFormatComma($jumlah_amaun), $id_vot , $jumlah_keseluruhan
                ]);
            }
        }



        return json_encode($senaraiData);
    }

    //SENARAI KUTIPAN - TAB 2
    public function get_senarai_kutipan_penyata_pemungut(Request $request){

        $tarikh_dari = "";
        $tarikh_hingga = "";
        $kaedah_bayaran = "";

        if($request->has('df') && $request->has('dt') && $request->has('pm')){
            $tarikh_dari = convert_date_db($request->get('df'));
            $tarikh_hingga = convert_date_db($request->get('dt'));
            $kaedah_bayaran = $request->get('pm');
        }

        $data = CollectorStatement::get_senarai_rekod_terimaan($tarikh_dari, $tarikh_hingga, districtId(), $kaedah_bayaran);

        //-------------------------------------------------------------------------------------------------
        // CALCULATE TOTAL DATA IN COLLECTOR STATEMENT
        // MAXIMUM 200 DATA IN A COLLECTOR STATEMENT
        //-------------------------------------------------------------------------------------------------
        //CALCULATE LEFT DATA ( TO GENERATE ALERT )
        //----------------------------------------------------------------------------
        $total_data = count($data);  $total_left_data = 0;    $send_alert = "";

        if($total_data >= 200 ){
            $total_left_data = $total_data - 200;
            $send_alert = true;
        }
        else if($total_data > 0 && $total_data < 200){
            $total_left_data = 0; // DATA KURANG 200
            $send_alert = false;
        }
        //----------------------------------------------------------------------------
        $senaraiData = collect();
        $jumlah_keseluruhan = 0;
        $bil=0;
        for ($counter=0; $counter < $total_data; $counter++) {

            $bil++;
            $id               = $data[$counter]->id;
            $no_notis_bayaran = $data[$counter]->payment_notice_no ?? '-';
            $tarikh_bayaran   = $data[$counter]->payment_date;
            $butiran          = $data[$counter]->payment_description;
            $no_resit         = $data[$counter]->payment_receipt_no;
            $amaun            = $data[$counter]->total_payment;

            $jumlah_keseluruhan += removeComma($amaun);

            $senaraiData->push([
                $bil,  $id, $no_notis_bayaran, convertDateSys($tarikh_bayaran), $butiran, $no_resit, $amaun, $jumlah_keseluruhan, $total_left_data, $send_alert
            ]);
        }

        return json_encode($senaraiData);

    }

    // GENERATE REF NUMBER
    private function _getCurrentRunningNo()
    {
        $latest_record = CollectorStatement::orderBy('id', 'desc')->first();
        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generateRefNo($running_no, $district)
    {
        $ref_no = str_pad($running_no, 5, "0", STR_PAD_LEFT);
        $ref_no = currentYearTwoDigit(). $district->finance_district_code_pp . 'PP05' . $ref_no;

        return $ref_no;
    }


    //PROCESS
    private function _saveCollectorLog($collectorStatement, $transaction_status, $date, $kuiri_remarks, $finance_category, $finance_officer )
    {
        $collectorStatement_Log = new CollectorStatementLog;

        $collectorStatement_Log->collector_statement_id        = $collectorStatement->id;
        $collectorStatement_Log->transaction_status_id         = $transaction_status;
        $collectorStatement_Log->date                          = $date;
        $collectorStatement_Log->remarks                       = $kuiri_remarks;
        $collectorStatement_Log->finance_officer_category_id   = $finance_category->id;
        $collectorStatement_Log->finance_officer_category_name = $finance_category->category_name;
        $collectorStatement_Log->finance_officer_id            = $finance_officer->id;
        $collectorStatement_Log->finance_officer_name          = $finance_officer->name;
        $collectorStatement_Log->position_name                 = $finance_officer->position->position_name;
        $collectorStatement_Log->data_status                   = 1;
        $collectorStatement_Log->action_by                     = loginId();
        $collectorStatement_Log->action_on                     = currentDate();

        return $collectorStatement_Log;
    }

    private function _saveCollectorStatement($collectorStatement, $transaction_status)
    {
        $collectorStatement->transaction_status_id            = $transaction_status;
        $collectorStatement->data_status                      = 1;
        $collectorStatement->action_by                        = loginId();
        $collectorStatement->action_on                        = currentDate();

        return $collectorStatement;
    }

    private function _resetKuiri($collectorStatement)
    {
        $collectorStatement->checker_id                       = 0;
        $collectorStatement->approver_id                      = 0;
        $collectorStatement->transaction_status_id            = 5; //kuiri
        $collectorStatement->data_status                      = 1;
        $collectorStatement->action_by                        = loginId();
        $collectorStatement->action_on                        = currentDate();

        return $collectorStatement;
    }

}
