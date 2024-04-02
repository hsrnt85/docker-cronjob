<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectorStatement;
use App\Models\District;
use App\Models\FinanceOfficer;
use App\Models\FinanceOfficerCategory;
use App\Models\FinanceDepartment;
use App\Models\IncomeAccountCode;
use App\Models\Journal;
use App\Models\JournalLog;
use App\Models\JournalVotList;
use App\Models\JournalType;
use App\Models\TransactionStatus;
use App\Http\Resources\ListData;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\JournalAdjustmentRequest;
use App\Notifications\JournalAdjustmentNotification;

class JournalAdjustmentController extends Controller
{

    public function index(Request $request)
    {
        $search_jurnal_no = ($request->search_jurnal_no) ? $request->search_jurnal_no : null;
        $search_date      = ($request->search_date) ? convertDatepickerDb($request->search_date) : null;

        $is_a_preparer   = FinanceOfficer::current_fin_officer_by_category(1, loginId());
        $login_officer   = FinanceOfficer::current_fin_officer(loginId());
        $journal_list    = Journal::get_journal_active($search_jurnal_no, $search_date, $login_officer?->id);
        $journal_history = Journal::get_journal_history($search_jurnal_no, $search_date, $login_officer?->id);

        return view(getFolderPath().'.index',
        [
            'search_jurnal_no' => $search_jurnal_no,
            'search_date'      => convertDateSys($search_date),
            'is_a_preparer'    => $is_a_preparer,
            'login_officer'    => $login_officer,
            'journal_list'     => $journal_list,
            'journal_history'  => $journal_history
        ]);
    }

    public function create()
    {
        if (!checkPolicy("A")) { return redirect()->route('dashboard')->with('error-permission', 'access.denied'); }

        $get_pegawai_penyedia = ListData::get_finance_officer_by_district(districtId(), 1);
        $get_pegawai_penyemak = ListData::get_finance_officer_by_district(districtId(), 2);
        $get_collector_statement = CollectorStatement::get_passed_collector_statement();
        $get_income_code   = IncomeAccountCode::get_senarai_vot();
        $get_journal_type  = JournalType::get_journal_type();

        $login_officer = FinanceOfficer::current_fin_officer(loginId());
        $finance_department = FinanceDepartment::finance_department_by_district(districtId());

        return view(getFolderPath().'.create',
        [
            'get_pegawai_penyedia'    => $get_pegawai_penyedia,
            'get_pegawai_penyemak'    => $get_pegawai_penyemak,
            'get_collector_statement' => $get_collector_statement,
            'get_income_code' => $get_income_code,
            'login_officer'   => $login_officer,
            'journal_type_list'  => $get_journal_type,
            'finance_department' => $finance_department
        ]);
    }

    public function store(Request $request)
    {
        $district          = District::where('id', districtId())->first();
        $curr_running_no   = $this->_getCurrentRunningNo();
        $ref_no            = $this->_generateRefNo($curr_running_no, $district);
        $preparer          = FinanceOfficer::current_fin_officer_by_category(1, loginId());

        DB::beginTransaction();
        try {

            // SAVE JOURNAL
            $journal = new Journal;
            $journal->district_id                = districtId();
            $journal->ispeks_integration_id      = 0;
            $journal->collector_statement_id     = $request->collector_statement_no;
            $journal->journal_no                 = $ref_no;
            $journal->running_no                 = $curr_running_no;
            $journal->journal_type_id            = $request->journal_type;
            $journal->journal_date               = currentDateDb();
            $journal->description                = $request->description;
            $journal->preparer_id                = $request->preparer;
            $journal->checker_id                 = $request->checker;
            $journal->transaction_status_id      = 1; // simpan
            $journal->data_status                = 1;
            $journal->action_by                  = loginId();
            $journal->action_on                  = currentDate();
            $journal->save();

            // SAVE JOURNAL LOG
            $journalLog = new JournalLog;
            $journalLog->journal_id                    = $journal->id;
            $journalLog->transaction_status_id         = 1; // simpan
            $journalLog->date                          = currentDate();
            $journalLog->finance_officer_category_id   = 1; //1:pegawai penyedia
            $journalLog->finance_officer_category_name = $preparer->finance_category?->category_name;
            $journalLog->finance_officer_id            = $preparer->id;
            $journalLog->finance_officer_name          = $preparer->name;
            $journalLog->position_name                 = $preparer->position?->position_name;
            $journalLog->data_status                   = 1;
            $journalLog->action_by                     = loginId();
            $journalLog->action_on                     = currentDate();
            $journalLog->save();

            // SAVE JOURNAL VOT LIST
            foreach($request->income_code as $i => $income_code){
                $journalVotList = new JournalVotList;
                $journalVotList->journal_id                = $journal->id;
                $journalVotList->income_account_code_id    = $income_code;
                $journalVotList->debit_amount              = (isset($request->debit[$i])) ? $request->debit[$i] : 0;
                $journalVotList->credit_amount             = (isset($request->credit[$i])) ? $request->credit[$i] : 0;
                $journalVotList->data_status               = 1;
                $journalVotList->action_by                 = loginId();
                $journalVotList->action_on                 = currentDate();
                $journalVotList->save();
            }

            setUserActivity("A", " No Jurnal: ".$journal->journal_no);


            DB::commit();
            return redirect()->route('journalAdjustment.index')->with('success', 'Jurnal Pelarasan berjaya ditambah! ');

        } catch (\Exception $e) {

            // something went wrong
            DB::rollback();
            return redirect()->route('journalAdjustment.create')->with('error', 'Jurnal Pelarasan tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        //if (!checkPolicy("U")) { return redirect()->route('dashboard')->with('error-permission', 'access.denied');  }

        $id = $request->id;
        $journal     = Journal::get_journal_by_id($id);
        $journal_log = JournalLog::get_journal_log($id);
        $kuiri_list  = JournalLog::get_journal_kuiri($id); // TABLE LOG KUIRI
        $journal_vot_list  = JournalVotList::get_journal_vot_list($id);
        $journal_type_list = JournalType::get_journal_type();

        $checking_list = TransactionStatus::checking_list(); // RADIO SEMAKAN
        $approval_list = TransactionStatus::approval_list(); // RADIO KELULUSAN
        $checker_list  = ListData::get_finance_officer_by_district(districtId(), 2);
        $approver_list = ListData::get_finance_officer_by_district(districtId(), 3);
        $collector_no_list  = CollectorStatement::get_passed_collector_statement();

        $finance_department = FinanceDepartment::finance_department_by_district($journal->district_id);

        $current_status = Journal::current_transaction_status($id);
        $current_log    = JournalLog::curent_journal_log($id); //SEBAB BATAL
        $login_officer  = FinanceOfficer::current_fin_officer(loginId());
        $login_officer  = $login_officer?->id;

        $get_income_code   = IncomeAccountCode::get_senarai_vot();
        // $get_journal_type  = JournalType::get_journal_type();
        //------------------------------------------------------------------------------------------------------------------------
        //CHECKING FINANCE OFFICER ID BY LATEST TRANSACTION/PHASE/PROCESS (SEDIA->SEMAK->LULUS)
        //CHECKING FROM PHASE PELULUS -> PENYEDIA (DESCENDING)
        //------------------------------------------------------------------------------------------------------------------------
        $approver =  $journal->approver_id;
        $checker  =  $journal->checker_id;
        $preparer =  $journal->preparer_id;
        $proses_simpan = ($current_status == 1) && ($login_officer == $checker) ? true : false;

        if($journal->approver_id>0) // PROSES LULUS
        {
            if($login_officer == $approver){
               $officer_on_duty = $approver;   $finance_cat_id = 3;
            }
            else{ // PEG. PENYEMAK CAN EDIT
               $officer_on_duty = $checker;    $finance_cat_id = 2;
            }
        }
        elseif($journal->checker_id>0) // PROSES SEMAK
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
        elseif($journal->preparer_id>0){  // PROSES SEDIA
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
        // BACK TO CURRENT TAB WHEN CLICK BUTTON 'KEMBALI'
        //-----------------------------------------------------------------------------------------------------------------------
        if($request->tab == 1){ $tab = 'tindakan'; } else { $tab = 'terdahulu'; }
        $tab_id = $request->tab;

        return view(getFolderPath(). '.edit', [

            'journal'        => $journal,
            'journal_log'    => $journal_log,
            'kuiri_list'     => $kuiri_list,
            'checking_list'  => $checking_list,
            'approval_list'  => $approval_list,
            'checker_list'   => $checker_list,
            'approver_list'  => $approver_list,
            'current_status' => $current_status,
            'current_log'    => $current_log,
            'login_officer'  => $login_officer,
            'proses_sedia'   => $proses_sedia,
            'proses_semak'   => $proses_semak,
            'proses_lulus'   => $proses_lulus,
            'tab'            => $tab,
            'tab_id'         => $tab_id,
            'finance_department' => $finance_department,
            'journal_vot_list'   => $journal_vot_list,
            'journal_type_list'  => $journal_type_list,
            'collector_no_list'  => $collector_no_list,
            'get_income_code'    => $get_income_code
        ]);
    }

    public function update(Request $request)
    {
        // DB::beginTransaction();
        $id = $request->id;
        $tab = $request->tab_id;
        $journal    = Journal::get_journal_by_id($id);
        $journal_no = Journal::journal_no($id);
        $btnType    = $request->btn_type;
        $current_status = Journal::current_transaction_status($id);

        $pegawai_penyedia = FinanceOfficer::current_fin_officer_by_category(1, loginId());
        $pegawai_penyemak = FinanceOfficer::current_fin_officer_by_category(2, loginId());
        $pegawai_pelulus  = FinanceOfficer::current_fin_officer_by_category(3, loginId());

        $kategori_pegawai_penyedia =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
        $kategori_pegawai_penyemak =  FinanceOfficerCategory::FinanceOfficerByCategory(2);
        $kategori_pegawai_pelulus  =  FinanceOfficerCategory::FinanceOfficerByCategory(3);

        $checking_status      = $request->checking_status;
        $approval_status      = $request->approval_status;
        $kuiri_remarks        = ($request->kuiri_remarks) ? $request->kuiri_remarks : null;

        $old_checker       = $journal->checker_id;
        $new_checker       = $request->checker;
        $old_approver      = $journal->approver_id;
        $new_approver      = $request->approver;
        $preparer          = $journal->preparer_id;
        $is_same_checker   = ($old_checker == $new_checker) ? true : false;
        $is_same_approver  = ($old_approver == $new_approver) ? true : false;
        $noti_kuiri        = FinanceOfficer::finance_officer_by_id($preparer);

        try {

            //-------------------------------------------------------------------------------------------------------------------------------------------------
            // PROSES PEGAWAI PENYEDIA = CURRENT_STATUS [1,2,5]                                                                                              //
            //-------------------------------------------------------------------------------------------------------------------------------------------------

            if($request->proses_sedia == 1 || $current_status == 1)
            {
                // UPDATE JOURNAL
                $journal->transaction_status_id  = ($btnType == "simpan") ?  1 : 2;
                $journal->description            = $request->description;
                $journal->checker_id             = $request->checker;
                $journal->action_on              = currentDate();
                $journal->save();

                //SAVE/UPDATE MAKLUMAT VOT
                $journal_vot_list_id_arr = isset($request->votlist_id) ? $request->votlist_id : [];
                if($journal_vot_list_id_arr){
                    foreach($journal_vot_list_id_arr as $i => $journal_vot_list_id){
    
                        $income_code = isset($request->income_code[$i]) ? $request->income_code[$i] : 0;
                        $debit  = isset($request->debit[$i]) ? $request->debit[$i] : 0;
                        $credit = isset($request->credit[$i]) ? $request->credit[$i] : 0;
    
                        // SAVE JOURNAL VOT LIST
                        if($journal_vot_list_id == 0){
    
                            $journalVotList = new JournalVotList;
                            $journalVotList->journal_id                = $id;
                            $journalVotList->income_account_code_id    = $income_code;
                            $journalVotList->debit_amount              = $debit;
                            $journalVotList->credit_amount             = $credit;
                            $journalVotList->data_status               = 1;
                            $journalVotList->action_by                 = loginId();
                            $journalVotList->action_on                 = currentDate();
                            $journalVotList->save();
    
                        }else{
    
                            JournalVotList::where('id', $journal_vot_list_id)
                                ->update([
                                    'income_account_code_id' => $income_code,
                                    'debit_amount' => $debit,
                                    'credit_amount' => $credit,
                                    'action_by' => loginId(),
                                    'action_on' => currentDate()
                                ]);
                        }
                        
                    }
                }

                if($journal->transaction_status_id == 2) // STATUS SAH SIMPAN / KEMASKINI
                {
                    $check_current_log = JournalLog::where(['journal_id'=> $id, 'transaction_status_id' => 2])->first();

                    if($check_current_log){ // update
                        $check_current_log->action_by = loginId();
                        $check_current_log->action_on = currentDate();
                        $saveLog = $check_current_log->save();
                    }else{ // save new
                        $new_log = $this->_saveJournalLog($journal, 2, currentDate(), $kuiri_remarks, $kategori_pegawai_penyedia, $pegawai_penyedia );
                        $saveLog  =  $new_log->save();
                    }

                    //----------------------------------------------------------------------
                    //SEND NOTIFICATION TO CHEKCER
                    //----------------------------------------------------------------------
                    if($saveLog) {

                        $noti_new_checker = FinanceOfficer::finance_officer_by_id($new_checker);
                        $noti_old_checker = FinanceOfficer::finance_officer_by_id($old_checker);
                        $action = "semak";

                        if($is_same_checker == false) {
                            //SEND NOTI TO NEW CHECKER
                            $noti_new_checker->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "baru", $action, $tab));
                            //SEND NOTI BATAL TO OLD CHECKER
                            //BLOCK PAGE NOTI FOR OLD CHECKER
                            if($old_checker) {
                                $noti_old_checker->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "batal", $action, $tab));
                            }
                        }else{
                            //SEND NOTI 'KEMASKINI' TO CURRENT CHECKER
                            $noti_new_checker->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "kemaskini", $action, $tab));
                        }
                    }

                    setUserActivity("U", "Status Sah Simpan: ".$journal->journal_no);

                }
                else
                {
                    //UPDATE CURRENT LOG && MASIH STATUS SIMPAN
                    $saveLog = JournalLog::where('journal_id', $id)->orderBy('id', 'desc') ->update(['action_by' => loginId(), 'action_on' => currentDate()]);
                }
            }

            //------------------------------------------------------------------------------------------------------------------------------------------------
            //PROSES PEGAWAI PENYEMAK = CURRENT_STATUS [2,3]                                                                                                //
            //------------------------------------------------------------------------------------------------------------------------------------------------

            elseif($request->proses_semak == 1)
            {
                //-----------------------------------------------------------------------------------------------------------
                // UPDATE JOURNAL
                //-----------------------------------------------------------------------------------------------------------
                if($checking_status == 3){ // 3: Semak
                    $journal->approver_id  = $request->approver;
                    $updateJournal         = $this->_saveJournal($journal, $checking_status );
                }
                else if($checking_status == 5){ // 5: Kuiri
                    $updateJournal = $this->_resetKuiri($journal);
                }

                setUserActivity("P", "Pengesahan Semak: ".$journal->journal_no);

                $updated = $updateJournal->save();

                //--------------------------------------------------------------------------------------------------------
                // INSERT JOURNAL  LOG
                //--------------------------------------------------------------------------------------------------------
                if($updated)
                {
                    $journalLog = $this->_saveJournalLog($journal, $checking_status, currentDate(), $kuiri_remarks, $kategori_pegawai_penyemak, $pegawai_penyemak );

                    $saveLog = $journalLog->save();
                    setUserActivity("P", "Kelulusan No Jurnal: ".$journal->journal_no);


                    //----------------------------------------------------------------------
                    //SEND NOTIFICATION TO APPROVER
                    //----------------------------------------------------------------------
                    $noti_new_approver = FinanceOfficer::finance_officer_by_id($new_approver);
                    $noti_old_approver = FinanceOfficer::finance_officer_by_id($old_approver);

                    $action = "lulus";

                    if($checking_status == 3){
                        if($is_same_approver == false) {
                            //SEND NOTI TO NEW APPROVER
                            $noti_new_approver->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "baru", $action, $tab));
                            //SEND NOTI BATAL TO OLD APPROVER
                            //BLOCK PAGE NOTI FOR OLD CHECKER
                            if($old_approver) {
                                $noti_old_approver->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "batal", $action, $tab));
                            }
                        }else{
                            //SEND NOTI TO CURRENT APPROVER
                            $noti_new_approver->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "baru", $action, $tab));
                        }
                    }else{
                        $noti_kuiri->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "kuiri", "", $tab));
                    }
                }
            }

            //------------------------------------------------------------------------------------------------------------------------------------------------
            //PROSES PEGAWAI PELULUS = CURRENT_STATUS [3,4]                                                                                                 //
            //------------------------------------------------------------------------------------------------------------------------------------------------
            elseif($request->proses_lulus == 1)
            {
                //-----------------------------------------------------------------------------------------------------------
                // UPDATE JOURNAL
                //-----------------------------------------------------------------------------------------------------------
                if($approval_status == 5){   // 5: Kuiri
                    $updateJournal = $this->_resetKuiri($journal);
                } else {                     // 4: Lulus
                    $updateJournal = $this->_saveJournal($journal, $approval_status);
                }

                $updated = $updateJournal->save();

                //--------------------------------------------------------------------------------------------------------
                // INSERT JOURNAL LOG
                //--------------------------------------------------------------------------------------------------------
                if($updated)
                {
                    $journalLog = $this->_saveJournalLog($journal, $approval_status, currentDate(), $kuiri_remarks, $kategori_pegawai_pelulus, $pegawai_pelulus );
                    $saveLog = $journalLog->save();

                    //----------------------------------------------------------------------
                    //SEND NOTIFICATION TO PREPARER (KUIRI)
                    //----------------------------------------------------------------------
                    if($approval_status == 5){
                        $noti_kuiri->user?->notify(new JournalAdjustmentNotification($id, $journal_no, "kuiri", "", $tab));
                    }
                }
            }

            DB::commit();

            
            if($btnType == "simpan")
                { return redirect()->route('journalAdjustment.index')->with('success', 'Jurnal Pelarasan berjaya disimpan! '); }
            else if($btnType == "hantar")
                { return redirect()->route('journalAdjustment.index')->with('success', 'Jurnal Pelarasan berjaya dihantar! ');}
            else  // kemaskini
                { return redirect()->route('journalAdjustment.index')->with('success', 'Jurnal Pelarasan berjaya dikemaskini! ');}
                

        } catch (\Exception $e) {

            // something went wrong
            DB::rollback();
            return redirect()->route('journalAdjustment.edit',['id'=>$id, 'tab' => 1])->with('error', 'Jurnal Pelarasan tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }
    }

    //Batal Penyata Pemungut
    public function cancel(Request $request)
    {
        $id = $request->id;
        $cancel_remarks = $request->cancel_remarks;

        $journal = Journal::where('id', $id)->first();
        $transaction_status = $journal->transaction_status_id;

        if($transaction_status == 1){

            $cancel_status = 6; // BATAL
            $finance_category =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
            $finance_officer  =  FinanceOfficer::current_fin_officer_by_category(1, loginId());

        }else if($transaction_status == 4){

            $cancel_status = 7; // BATAL SELEPAS LULUS
            $finance_category  =  FinanceOfficerCategory::FinanceOfficerByCategory(3);
            $finance_officer   =  FinanceOfficer::current_fin_officer_by_category(3, loginId());

        }else if($transaction_status == 5){

            $cancel_status = 8; // BATAL SELEPAS SAH SIMPAN
            $finance_category =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
            $finance_officer  =  FinanceOfficer::current_fin_officer_by_category(1, loginId());
        }

        // UPDATE JOURNAL
        $journal->transaction_status_id    =  $cancel_status;

        setUserActivity("C", " No Jurnal: ".$journal->journal_no);

        $journal->data_status              =  2;
        $journal->delete_by                = loginId();
        $journal->delete_on                = currentDate();
        $saved = $journal->save();

        if($saved){

            // SAVE LOG
            $saveLog = $this->_saveJournalLog($journal, $cancel_status, currentDate(), $cancel_remarks, $finance_category, $finance_officer);
            $saveLog->save();
        }

        if(!$saveLog) {
            return redirect()->route('journalAdjustment.index')->with('error', 'Jurnal Pelarasan tidak berjaya dibatalkan!');
        } else {
            return redirect()->route('journalAdjustment.index')->with('success', 'Jurnal Pelarasan berjaya dibatalkan!');
        }
    }

    public function destroyByRow(Request $request)
    {
        $journal_id = $request->id;
        $tab_id = $request->tab_id;
        $vot_list_id = $request->id_by_row;

        DB::beginTransaction();

        try {

            JournalVotList::where('id', $vot_list_id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();

            return redirect()->route('journalAdjustment.edit', ['id'=>$journal_id, 'tab' => $tab_id])->with('success', 'Jurnal Pelarasan berjaya dihapus!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('journalAdjustment.edit', ['id'=>$journal_id, 'tab' => $tab_id])->with('error', 'Jurnal Pelarasan tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        
    }

    //GENERATE PDF ---------------------------------------------------------------------------------------------------------
    public function generate_pdf(Request $request)
    {
        $id = $request->id;
        $journal  = Journal::get_journal_by_id($id);
        $preparer = JournalLog::get_journal_log_by_status($id, 2);
        $checker  = JournalLog::get_journal_log_by_status($id, 3);
        $approver = JournalLog::get_journal_log_by_status($id, 4);
        $finance_department = FinanceDepartment::finance_department_by_district($journal->district_id);
        $journal_vot_list   = JournalVotList::get_journal_vot_list($id); // TABLE VOT AKAUN
        $total_amount       = JournalVotList::total_debit_and_credit($id);

        $data = [
            'journal'  => $journal,
            'preparer' => $preparer,
            'checker'  => $checker,
            'approver' => $approver,
            'journal_vot_list'   => $journal_vot_list,
            'finance_department' => $finance_department,
            'total_amount'       => $total_amount
        ];

        $pdf = PDF::loadView('download-pdf.jurnal-pelarasan.main-page', $data)->setPaper('A4', 'potrait');
        // return $pdf->download($journal->journal_no.'.pdf');
        return $pdf->stream($journal->journal_no.'.pdf');

    }

    //GENERATE REF NO ------------------------------------------------------------------------------------------------------
    private function _getCurrentRunningNo()
    {
        $latest_record = Journal::select('id', 'running_no')->orderBy('id', 'desc')->first();

        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generateRefNo($running_no, $district)
    {
        $ref_no = str_pad($running_no, 5, "0", STR_PAD_LEFT);
        $ref_no = currentYearTwoDigit(). $district->finance_district_code_jr . 'P07' . $ref_no;

        return $ref_no;
    }


    //PROCESS --------------------------------------------------------------------------------------------------------------
    private function _saveJournalLog($journal, $transaction_status, $date, $kuiri_remarks, $finance_category, $finance_officer )
    {
        $journalLog = new JournalLog;

        $journalLog->journal_id                    = $journal->id;
        $journalLog->transaction_status_id         = $transaction_status;
        $journalLog->date                          = $date;
        $journalLog->remarks                       = $kuiri_remarks;
        $journalLog->finance_officer_category_id   = $finance_category->id;
        $journalLog->finance_officer_category_name = $finance_category->category_name;
        $journalLog->finance_officer_id            = $finance_officer->id;
        $journalLog->finance_officer_name          = $finance_officer->name;
        $journalLog->position_name                 = $finance_officer->position->position_name;
        $journalLog->data_status                   = 1;
        $journalLog->action_by                     = loginId();
        $journalLog->action_on                     = currentDate();

        return $journalLog;
    }

    private function _saveJournal($journal, $transaction_status)
    {
        $journal->transaction_status_id            = $transaction_status;
        $journal->data_status                      = 1;
        $journal->action_by                        = loginId();
        $journal->action_on                        = currentDate();

        return $journal;
    }

    private function _resetKuiri($journal)
    {
        $journal->checker_id                       = 0;
        $journal->approver_id                      = 0;
        $journal->transaction_status_id            = 5; //kuiri
        $journal->data_status                      = 1;
        $journal->action_by                        = loginId();
        $journal->action_on                        = currentDate();

        return $journal;
    }

}
