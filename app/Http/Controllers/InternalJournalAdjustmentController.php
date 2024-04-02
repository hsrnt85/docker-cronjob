<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\FinanceOfficer;
use App\Models\FinanceDepartment;
use App\Models\FinanceOfficerCategory;
use App\Models\IncomeAccountCode;
use App\Models\InternalJournal;
use App\Models\InternalJournalLog;

use App\Models\TenantsPaymentNotice;
use App\Models\TenantsPaymentNoticeVotList;
use App\Models\Tenant;
use App\Models\TransactionStatus;
use App\Models\User;
use App\Http\Resources\ListData;
use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\InternalJournalAdjustmentNotification;

class InternalJournalAdjustmentController extends Controller
{

    public function index(Request $request)
    {

        $search_jurnal_no = ($request->search_jurnal_no) ? $request->search_jurnal_no : null;
        $search_date      = ($request->search_date) ? convertDatepickerDb($request->search_date) : null;
        $is_a_preparer    = FinanceOfficer::current_fin_officer_by_category(1, loginId());
        $login_officer    = FinanceOfficer::current_fin_officer(loginId());
        $journal_list     = InternalJournal::get_internal_journal_active($search_jurnal_no, $search_date, $login_officer?->id);
        $journal_history  = InternalJournal::get_internal_journal_history($search_jurnal_no, $search_date, $login_officer?->id);

        return view(getFolderPath().'.list',
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

        $get_pegawai_penyedia   = ListData::get_finance_officer_by_district(districtId(), 1);
        $get_pegawai_penyemak   = ListData::get_finance_officer_by_district(districtId(), 2);
        $get_income_code        = IncomeAccountCode::get_senarai_vot();
        $login_officer          = FinanceOfficer::current_fin_officer(loginId());
        $finance_department     = FinanceDepartment::finance_department_by_district(districtId());
        $tenants_payment_notice = TenantsPaymentNotice::get_latest_payment_notice_by_district(districtId());

        return view(getFolderPath().'.create',
        [
            'get_pegawai_penyedia'   => $get_pegawai_penyedia,
            'get_pegawai_penyemak'   => $get_pegawai_penyemak,
            'get_income_code'        => $get_income_code,
            'login_officer'          => $login_officer,
            'finance_department'     => $finance_department,
            'tenants_payment_notice' => $tenants_payment_notice
        ]);
    }

    public function store(Request $request)
    {
        $district          = District::where('id', districtId())->first();
        $curr_running_no   = $this->_getCurrentRunningNo();
        $ref_no            = $this->_generateRefNo($curr_running_no, $district); //generate reference no
        $preparer          = FinanceOfficer::current_fin_officer_by_category(1, loginId());
        $tenants_payment_notice_id = $request->notice_no;
        //dd($tenants_payment_notice_id);

        DB::beginTransaction();
        try {

            // SAVE JOURNAL
            $internalJournal = new InternalJournal;
            $internalJournal->district_id                = districtId();
            $internalJournal->tenants_payment_notice_id  = $request->notice_no;
            $internalJournal->payment_category_id        = $request->payment_category_id;
            $internalJournal->journal_no                 = $ref_no;
            $internalJournal->running_no                 = $curr_running_no;
            $internalJournal->journal_date               = currentDateDb();
            $internalJournal->description                = $request->description;
            $internalJournal->preparer_id                = $request->preparer;
            $internalJournal->checker_id                 = $request->checker;
            $internalJournal->transaction_status_id      = 1; // simpan
            $internalJournal->tenants_name               = $request->tenant_name;
            $internalJournal->payment_notice_amount      = $request->payment_notice_amount;
            $internalJournal->adjustment_amount          = $request->adjustment_amount;
            $internalJournal->total_amount               = $request->final_amount_hidden;
            $internalJournal->data_status                = 1;
            $internalJournal->action_by                  = loginId();
            $internalJournal->action_on                  = currentDate();
            $internalJournal->save();

            // SAVE JOURNAL LOG
            $internalJournalLog = new InternalJournalLog;
            $internalJournalLog->internal_journal_id           = $internalJournal->id;
            $internalJournalLog->transaction_status_id         = 1; // simpan
            $internalJournalLog->date                          = currentDate();
            $internalJournalLog->finance_officer_category_id   = 1; //1:pegawai penyedia
            $internalJournalLog->finance_officer_category_name = $preparer->finance_category?->category_name;
            $internalJournalLog->finance_officer_id            = $preparer->id;
            $internalJournalLog->finance_officer_name          = $preparer->name;
            $internalJournalLog->position_name                 = $preparer->position?->position_name;
            $internalJournalLog->data_status                   = 1;
            $internalJournalLog->action_by                     = loginId();
            $internalJournalLog->action_on                     = currentDate();
            $internalJournalLog->save();

            // SAVE JOURNAL VOT LIST

            $income_code_arr = $request->income_code;
            foreach($income_code_arr as $i => $income_code_id){
                $debit = (isset($request->debit[$i])) ? $request->debit[$i] : 0;
                $credit = (isset($request->credit[$i])) ? $request->credit[$i] : 0;

                $this->_saveTenantsPaymentNoticeVotList($tenants_payment_notice_id, $internalJournal->id, $income_code_id, $debit, $credit);
            }

            setUserActivity("A", "No Jurnal: ".$internalJournal->journal_no);

            DB::commit();
            return redirect()->route('internalJournalAdjustment.index')->with('success', 'Jurnal Pelarasan Dalaman berjaya ditambah! ');

        } catch (\Exception $e) {

            // something went wrong
            DB::rollback();
            return redirect()->route('internalJournalAdjustment.create')->with('error', 'Jurnal Pelarasan Dalaman tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }
    }


    public function edit(Request $request)
    {
        //if (!checkPolicy("U")) { return redirect()->route('dashboard')->with('error-permission', 'access.denied');  }

        $id = $request->id;
        $internal_journal     = InternalJournal::get_internal_journal_by_id($id);
        $internal_journal_log = InternalJournalLog::get_internal_journal_log($id);
        $kuiri_list  = InternalJournalLog::get_internal_journal_kuiri($id); // TABLE LOG KUIRI
        $internal_journal_vot_list  = TenantsPaymentNoticeVotList::get_tenants_payment_notice_vot_list($id);

        $checking_list = TransactionStatus::checking_list(); // RADIO SEMAKAN
        $approval_list = TransactionStatus::approval_list(); // RADIO KELULUSAN
        $checker_list  = ListData::get_finance_officer_by_district(districtId(), 2);
        $approver_list = ListData::get_finance_officer_by_district(districtId(), 3);

        $finance_department     = FinanceDepartment::finance_department_by_district($internal_journal->district_id);
        $tenants_payment_notice = TenantsPaymentNotice::get_latest_payment_notice_by_district(districtId());

        $current_status = InternalJournal::current_transaction_status($id);
        $current_log    = InternalJournalLog::current_internal_journal_log($id); //SEBAB BATAL
        $login_officer  = FinanceOfficer::current_fin_officer(loginId());
        $login_officer  = $login_officer->id ?? 0;

        $get_income_code   = IncomeAccountCode::get_senarai_vot();

        //------------------------------------------------------------------------------------------------------------------------
        //CHECKING FINANCE OFFICER ID BY LATEST TRANSACTION/PHASE/PROCESS (SEDIA->SEMAK->LULUS)
        //CHECKING FROM PHASE PELULUS -> PENYEDIA (DESCENDING)
        //------------------------------------------------------------------------------------------------------------------------
        $approver =  $internal_journal->approver_id;
        $checker  =  $internal_journal->checker_id;
        $preparer =  $internal_journal->preparer_id;
        $proses_simpan = ($current_status == 1) && ($login_officer == $checker) ? true : false;

        if($internal_journal->approver_id>0) // PROSES LULUS
        {
            if($login_officer == $approver){
               $officer_on_duty = $approver;   $finance_cat_id = 3;
            }
            else{ // PEG. PENYEMAK CAN EDIT
               $officer_on_duty = $checker;    $finance_cat_id = 2;
            }
        }
        elseif($internal_journal->checker_id>0) // PROSES SEMAK
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
        elseif($internal_journal->preparer_id>0){  // PROSES SEDIA
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
            'get_income_code'           => $get_income_code,
            'internal_journal'          => $internal_journal,
            'finance_department'        => $finance_department,
            'internal_journal_log'      => $internal_journal_log,
            'tenants_payment_notice'    => $tenants_payment_notice,
            'internal_journal_vot_list' => $internal_journal_vot_list,
        ]);
    }

    public function update(Request $request)
    {
        $id  = $request->id;

        $tab = $request->tab_id;
        $btnType  = $request->btn_type;
        $internal_journal    = InternalJournal::get_internal_journal_by_id($id);
        $internal_journal_no = InternalJournal::internal_journal_no($id);
        $current_status      = InternalJournal::current_transaction_status($id);

        $pegawai_penyedia = FinanceOfficer::current_fin_officer_by_category(1, loginId());
        $pegawai_penyemak = FinanceOfficer::current_fin_officer_by_category(2, loginId());
        $pegawai_pelulus  = FinanceOfficer::current_fin_officer_by_category(3, loginId());

        $kategori_pegawai_penyedia =  FinanceOfficerCategory::FinanceOfficerByCategory(1);
        $kategori_pegawai_penyemak =  FinanceOfficerCategory::FinanceOfficerByCategory(2);
        $kategori_pegawai_pelulus  =  FinanceOfficerCategory::FinanceOfficerByCategory(3);

        $checking_status   = $request->checking_status;
        $approval_status   = $request->approval_status;
        $kuiri_remarks     = ($request->kuiri_remarks) ? $request->kuiri_remarks : null;

        $old_checker       = $internal_journal->checker_id;
        $new_checker       = $request->checker;
        $old_approver      = $internal_journal->approver_id;
        $new_approver      = $request->approver;
        $preparer          = $internal_journal->preparer_id;
        $is_same_checker   = ($old_checker == $new_checker) ? true : false;
        $is_same_approver  = ($old_approver == $new_approver) ? true : false;
        $noti_kuiri        = FinanceOfficer::finance_officer_by_id($preparer);

        try {
            //-------------------------------------------------------------------------------------------------------------------------------------------------
            // PROSES PEGAWAI PENYEDIA = CURRENT_STATUS [1,2,5]                                                                                              //
            //-------------------------------------------------------------------------------------------------------------------------------------------------

            if($request->proses_sedia == 1 || $current_status == 1)
            {
                //UPDATE JOURNAL
                $internal_journal->transaction_status_id  = ($btnType == "simpan") ?  1 : 2;
                $internal_journal->description            = $request->description;
                $internal_journal->checker_id             = $request->checker;
                $internal_journal->tenants_name           = $request->tenant_name;
                $internal_journal->payment_notice_amount  = $request->payment_notice_amount;
                $internal_journal->adjustment_amount      = $request->adjustment_amount;
                $internal_journal->total_amount           = $request->final_amount_hidden;
                $internal_journal->action_on              = currentDate();
                $internal_journal->save();

                //TABLE VOT AKAUN --------------------------------------------------------------------------------------------------

                $income_code_arr = isset($request->income_code) ? $request->income_code : [];
                $get_vot_list    = TenantsPaymentNoticeVotList::where(['internal_journal_id' => $id, 'data_status' => 1])->get();
                $total_vot_list  = count($get_vot_list);

                foreach ($income_code_arr as $i => $income_code_id) {

                    $debit = (isset($request->debit[$i])) ? $request->debit[$i] : 0;
                    $credit = (isset($request->credit[$i])) ? $request->credit[$i] : 0;

                    if ($i < $total_vot_list) { //update
                        $vot_list = $get_vot_list[$i];

                        $this->_updateTenantsPaymentNoticeVotList($vot_list, $internal_journal->id, $income_code_id, $debit, $credit);

                    } else {

                        $this->_saveTenantsPaymentNoticeVotList($internal_journal->tenants_payment_notice_id, $internal_journal->id, $income_code_id, $debit, $credit);

                    }
                }

                if($internal_journal->transaction_status_id == 2) // STATUS SAH SIMPAN / KEMASKINI
                {
                    $check_current_log = InternalJournalLog::where(['internal_journal_id'=> $id, 'transaction_status_id' => 2])->first();

                    if($check_current_log){ // update
                        $check_current_log->action_by = loginId();
                        $check_current_log->action_on = currentDate();
                        $saveLog = $check_current_log->save();
                    }else{ // save new
                        $new_log = $this->_saveInternalJournalLog($internal_journal, 2, currentDate(), $kuiri_remarks, $kategori_pegawai_penyedia, $pegawai_penyedia );
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
                            $noti_new_checker->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "baru", $action, $tab));
                            //SEND NOTI BATAL TO OLD CHECKER
                            //BLOCK PAGE NOTI FOR OLD CHECKER
                            if($old_checker) {
                                $noti_old_checker->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "batal", $action, $tab));
                            }
                        }else{
                            //SEND NOTI 'KEMASKINI' TO CURRENT CHECKER
                            $noti_new_checker->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "kemaskini", $action, $tab));
                        }
                    }
                }
                else
                {
                    //UPDATE CURRENT LOG && MASIH STATUS SIMPAN
                    $saveLog = InternalJournalLog::where('internal_journal_id', $id)->orderBy('id', 'desc') ->update(['action_by' => loginId(), 'action_on' => currentDate()]);

                }

                setUserActivity("P", "Status Sah Simpan: ".$internal_journal->journal_no);

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
                    $internal_journal->approver_id  = $request->approver;
                    $updateJournal         = $this->_saveInternalJournal($internal_journal, $checking_status );
                }
                else if($checking_status == 5){ // 5: Kuiri
                    $updateJournal = $this->_resetKuiri($internal_journal);
                }

                $updated = $updateJournal->save();

                //--------------------------------------------------------------------------------------------------------
                // INSERT JOURNAL  LOG
                //--------------------------------------------------------------------------------------------------------
                if($updated)
                {
                    $internalJournalLog = $this->_saveInternalJournalLog($internal_journal, $checking_status, currentDate(), $kuiri_remarks, $kategori_pegawai_penyemak, $pegawai_penyemak );

                    $saveLog = $internalJournalLog->save();

                    //----------------------------------------------------------------------
                    //SEND NOTIFICATION TO APPROVER
                    //----------------------------------------------------------------------
                    $noti_new_approver = FinanceOfficer::finance_officer_by_id($new_approver);
                    $noti_old_approver = FinanceOfficer::finance_officer_by_id($old_approver);

                    $action = "lulus";

                    if($checking_status == 3){
                        if($is_same_approver == false) {
                            //SEND NOTI TO NEW APPROVER
                            $noti_new_approver->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "baru", $action, $tab));
                            //SEND NOTI BATAL TO OLD APPROVER
                            //BLOCK PAGE NOTI FOR OLD CHECKER
                            if($old_approver) {
                                $noti_old_approver->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "batal", $action, $tab));
                            }
                        }else{
                            //SEND NOTI TO CURRENT APPROVER
                            $noti_new_approver->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "baru", $action, $tab));
                        }
                    }else{
                        $noti_kuiri->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "kuiri", "", $tab));
                    }
                }

                setUserActivity("P", "Pengesahan Semak: ".$internal_journal->journal_no);

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
                    $updateJournal = $this->_resetKuiri($internal_journal);
                } else {                     // 4: Lulus
                    $updateJournal = $this->_saveInternalJournal($internal_journal, $approval_status);
                }

                $updated = $updateJournal->save();

                setUserActivity("P", "Kelulusan No Jurnal: ".$internal_journal->journal_no);

                //UPDATE TENANT NOTICE - adjustment
                TenantsPaymentNotice::where('id' , $internal_journal->tenants_payment_notice_id)
                    ->update(['adjustment_amount' => $internal_journal->adjustment_amount, 'total_amount_after_adjustment' => $internal_journal->total_amount]);

                //--------------------------------------------------------------------------------------------------------
                // INSERT JOURNAL LOG
                //--------------------------------------------------------------------------------------------------------
                if($updated)
                {
                    $internalJournalLog = $this->_saveInternalJournalLog($internal_journal, $approval_status, currentDate(), $kuiri_remarks, $kategori_pegawai_pelulus, $pegawai_pelulus );
                    $saveLog = $internalJournalLog->save();

                    //----------------------------------------------------------------------
                    //SEND NOTIFICATION TO PREPARER (KUIRI)
                    //----------------------------------------------------------------------
                    if($approval_status == 5){
                        $noti_kuiri->user?->notify(new InternalJournalAdjustmentNotification($id, $internal_journal_no, "kuiri", "", $tab));
                    }
                }
            }

            DB::commit();

            if($btnType == "simpan")
                { return redirect()->route('internalJournalAdjustment.index')->with('success', 'Jurnal Pelarasan Dalaman berjaya disimpan! '); }
            else if($btnType == "hantar")
                { return redirect()->route('internalJournalAdjustment.index')->with('success', 'Jurnal Pelarasan Dalaman berjaya dihantar! ');}
            else  // kemaskini
                { return redirect()->route('internalJournalAdjustment.index')->with('success', 'Jurnal Pelarasan Dalaman berjaya dikemaskini! ');}

        } catch (\Exception $e) {

            // something went wrong
            DB::rollback();
            return redirect()->route('internalJournalAdjustment.edit',['id'=>$id, 'tab' => $tab])->with('error', 'Jurnal Pelarasan Dalaman tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }
    }

    //Batal Penyata Pemungut
    public function cancel(Request $request)
    {
        $id = $request->id;
        $cancel_remarks = $request->cancel_remarks;

        $internal_journal = InternalJournal::where('id', $id)->where('data_status', 1)->first();
        $transaction_status = $internal_journal->transaction_status_id;

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
        $internal_journal->transaction_status_id    =  $cancel_status;

        setUserActivity("C", "No Jurnal: ".$internal_journal->journal_no);

        $internal_journal->data_status              =  2;
        $internal_journal->delete_by                = loginId();
        $internal_journal->delete_on                = currentDate();
        $saved = $internal_journal->save();

        //UPDATE STATUS TenantsPaymentNoticeVotList
        TenantsPaymentNoticeVotList::where('internal_journal_id', $id)
                ->update(['data_status' => 2,'action_by' => loginId(),'action_on' => currentDate()]);

        //UPDATE TENANT NOTICE - adjustment
        TenantsPaymentNotice::where('id' , $internal_journal->tenants_payment_notice_id)
                ->update(['adjustment_amount' => 0, 'total_amount_after_adjustment' => $internal_journal->payment_notice_amount]);

        if($saved){

            // SAVE LOG
            $saveLog = $this->_saveInternalJournalLog($internal_journal, $cancel_status, currentDate(), $cancel_remarks, $finance_category, $finance_officer);
            $saveLog->save();
        }

        if(!$saveLog) {
            return redirect()->route('internalJournalAdjustment.index')->with('error', 'Jurnal Pelarasan Dalaman tidak berjaya dibatalkan!');
        } else {
            return redirect()->route('internalJournalAdjustment.index')->with('success', 'Jurnal Pelarasan Dalaman berjaya dibatalkan!');
        }
    }

    public function destroyByRow(Request $request)
    {
        $journal_id = $request->id;
        $tab_id = $request->tab_id;
        $vot_list_id = $request->row_vot_list_id;

        DB::beginTransaction();

        try {

            TenantsPaymentNoticeVotList::where('id', $vot_list_id)
                                    ->update([
                                        'data_status' => 0,
                                        'action_by' => loginId(),
                                        'action_on' => currentDate()
                                    ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('internalJournalAdjustment.edit', ['id'=>$journal_id, 'tab' => $tab_id])->with('error', 'Jurnal Pelarasan Dalaman tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('internalJournalAdjustment.edit', ['id'=>$journal_id, 'tab' => $tab_id])->with('success', 'Jurnal Pelarasan Dalaman berjaya dihapus!');
    }


    //GENERATE REF NO ---------------------------------------------------------------------------------------------------------------
    private function _getCurrentRunningNo()
    {
        $latest_record = InternalJournal::select('id', 'running_no')->orderBy('id', 'desc')->first();

        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generateRefNo($running_no, $district)
    {
        $ref_no = str_pad($running_no, 5, "0", STR_PAD_LEFT);
        $ref_no = currentYearTwoDigit(). $district->finance_district_code_jr . 'D07' . $ref_no;

        return $ref_no;
    }

    //FUNCTION PROCESS --------------------------------------------------------------------------------------------------------------
    private function _saveInternalJournalLog($i_journal, $transaction_status, $date, $kuiri_remarks, $finance_category, $finance_officer )
    {
        $internalJournalLog = new InternalJournalLog;

        $internalJournalLog->internal_journal_id           = $i_journal->id;
        $internalJournalLog->transaction_status_id         = $transaction_status;
        $internalJournalLog->date                          = $date;
        $internalJournalLog->remarks                       = $kuiri_remarks;
        $internalJournalLog->finance_officer_category_id   = $finance_category->id;
        $internalJournalLog->finance_officer_category_name = $finance_category->category_name;
        $internalJournalLog->finance_officer_id            = $finance_officer?->id;
        $internalJournalLog->finance_officer_name          = $finance_officer?->name;
        $internalJournalLog->position_name                 = $finance_officer?->position->position_name;
        $internalJournalLog->data_status                   = 1;
        $internalJournalLog->action_by                     = loginId();
        $internalJournalLog->action_on                     = currentDate();

        return $internalJournalLog;
    }

    private function _saveInternalJournal($i_journal, $transaction_status)
    {
        $i_journal->transaction_status_id            = $transaction_status;
        $i_journal->data_status                      = 1;
        $i_journal->action_by                        = loginId();
        $i_journal->action_on                        = currentDate();

        return $i_journal;
    }

    private function _resetKuiri($i_journal)
    {
        $i_journal->checker_id                       = 0;
        $i_journal->approver_id                      = 0;
        $i_journal->transaction_status_id            = 5; //kuiri
        $i_journal->data_status                      = 1;
        $i_journal->action_by                        = loginId();
        $i_journal->action_on                        = currentDate();

        return $i_journal;
    }

    private function _saveTenantsPaymentNoticeVotList($tenants_payment_notice_id, $internal_journal_id, $income_account_code_id, $debit, $credit)
    {
        //GET VOT
        $dataIncomeAccountCode = $this->getIncomeAccountCode($income_account_code_id);

        $salary_deduction_code = $dataIncomeAccountCode->salary_deduction_code;
        $ispeks_account_code = $dataIncomeAccountCode->ispeks_account_code;
        $ispeks_account_description= $dataIncomeAccountCode->ispeks_account_description;
        $income_code = $dataIncomeAccountCode->income_code;
        $income_code_description = $dataIncomeAccountCode->income_code_description;
        $account_type_id = $dataIncomeAccountCode->account_type_id;
        $payment_category_id = $dataIncomeAccountCode->payment_category_id;
        $flag_outstanding = $dataIncomeAccountCode->flag_outstanding;
        $services_status_id = $dataIncomeAccountCode->services_status_id;
        $flag_ispeks = $dataIncomeAccountCode->flag_ispeks;

        $tenantsPaymentNoticeVotList = new TenantsPaymentNoticeVotList;
        $tenantsPaymentNoticeVotList->tenants_payment_notice_id = $tenants_payment_notice_id;
        $tenantsPaymentNoticeVotList->internal_journal_id = $internal_journal_id;
        $tenantsPaymentNoticeVotList->income_account_code_id = $income_account_code_id;
        $tenantsPaymentNoticeVotList->salary_deduction_code = $salary_deduction_code;
        $tenantsPaymentNoticeVotList->ispeks_account_code = $ispeks_account_code;
        $tenantsPaymentNoticeVotList->ispeks_account_description= $ispeks_account_description;
        $tenantsPaymentNoticeVotList->income_code = $income_code;
        $tenantsPaymentNoticeVotList->income_code_description = $income_code_description;
        $tenantsPaymentNoticeVotList->account_type_id = $account_type_id;
        $tenantsPaymentNoticeVotList->payment_category_id = $payment_category_id;
        $tenantsPaymentNoticeVotList->flag_outstanding = $flag_outstanding;
        $tenantsPaymentNoticeVotList->services_status_id = $services_status_id;
        $tenantsPaymentNoticeVotList->flag_ispeks = $flag_ispeks;
        $tenantsPaymentNoticeVotList->debit_amount = $debit;
        $tenantsPaymentNoticeVotList->credit_amount = $credit;
        $tenantsPaymentNoticeVotList->total_amount_after_adjustment = ($debit>0) ? $debit : $credit;
        $tenantsPaymentNoticeVotList->action_by = loginId();
        $tenantsPaymentNoticeVotList->action_on = currentDate();
        $tenantsPaymentNoticeVotList->save();

    }

    private function _updateTenantsPaymentNoticeVotList($tenantsPaymentNoticeVotList, $internal_journal_id, $income_account_code_id, $debit, $credit)
    {
        //GET VOT
        $dataIncomeAccountCode = $this->getIncomeAccountCode($income_account_code_id);

        $salary_deduction_code = $dataIncomeAccountCode->salary_deduction_code;
        $ispeks_account_code = $dataIncomeAccountCode->ispeks_account_code;
        $ispeks_account_description= $dataIncomeAccountCode->ispeks_account_description;
        $income_code = $dataIncomeAccountCode->income_code;
        $income_code_description = $dataIncomeAccountCode->income_code_description;
        $account_type_id = $dataIncomeAccountCode->account_type_id;
        $payment_category_id = $dataIncomeAccountCode->payment_category_id;
        $flag_outstanding = $dataIncomeAccountCode->flag_outstanding;
        $services_status_id = $dataIncomeAccountCode->services_status_id;
        $flag_ispeks = $dataIncomeAccountCode->flag_ispeks;

        $tenantsPaymentNoticeVotList->internal_journal_id = $internal_journal_id;
        $tenantsPaymentNoticeVotList->income_account_code_id = $income_account_code_id;
        $tenantsPaymentNoticeVotList->internal_journal_id = $internal_journal_id;
        $tenantsPaymentNoticeVotList->income_account_code_id = $income_account_code_id;
        $tenantsPaymentNoticeVotList->salary_deduction_code = $salary_deduction_code;
        $tenantsPaymentNoticeVotList->ispeks_account_code = $ispeks_account_code;
        $tenantsPaymentNoticeVotList->ispeks_account_description= $ispeks_account_description;
        $tenantsPaymentNoticeVotList->income_code = $income_code;
        $tenantsPaymentNoticeVotList->income_code_description = $income_code_description;
        $tenantsPaymentNoticeVotList->account_type_id = $account_type_id;
        $tenantsPaymentNoticeVotList->payment_category_id = $payment_category_id;
        $tenantsPaymentNoticeVotList->flag_outstanding = $flag_outstanding;
        $tenantsPaymentNoticeVotList->services_status_id = $services_status_id;
        $tenantsPaymentNoticeVotList->flag_ispeks = $flag_ispeks;
        $tenantsPaymentNoticeVotList->debit_amount = $debit;
        $tenantsPaymentNoticeVotList->credit_amount = $credit;
        $tenantsPaymentNoticeVotList->total_amount_after_adjustment = ($debit>0) ? $debit : $credit;
        $tenantsPaymentNoticeVotList->action_by = loginId();
        $tenantsPaymentNoticeVotList->action_on = currentDate();
        $tenantsPaymentNoticeVotList->save();

    }

    private function getIncomeAccountCode($income_account_code_id)
    {
        $dataIncomeAccountCode = IncomeAccountCode::from('income_account_code as iac')
            ->select('iac.account_type_id', 'iac.flag_outstanding', 'iac.services_status_id', 'iac.flag_ispeks',
                'iac.id', 'pc.services_type_id', 'pc.id AS payment_category_id',
                'iac.salary_deduction_code','iac.ispeks_account_code','iac.ispeks_account_description','iac.income_code','iac.income_code_description')
            ->join("payment_category AS pc", 'pc.id','=','iac.payment_category_id')
            ->where(['iac.data_status'=> 1,'pc.data_status'=> 1, 'iac.id'=>$income_account_code_id])
            ->orderBy('iac.account_type_id')
            ->first();


        return $dataIncomeAccountCode;
    }

    //AJAX -----------------------------------------------------------------------------------------------------------------------
    public function ajaxGetTenant(Request $request)
    {
        try {

            $notice_id = $request->id;

           $tpn = TenantsPaymentNotice::select('payment_notice_no', 'tenants_id', 'name as tenants_name', 'payment_category_id', 'total_amount_after_adjustment as total_amount')
               ->where('id', $notice_id)->first();
            // $tpn = TenantsPaymentNotice::where('id', $notice_id)->first();
            // $tenant       = Tenant::where('id', $tpn->tenants_id)->orderBy('id', 'desc')->first();
            // $payment_notice_amount = TenantsPaymentNotice::get_total_amount_by_tpn_id($notice_id);

            //return response()->json(['tenant' => $tenant, 'payment_notice_amount' => $payment_notice_amount], 200);
            return response()->json(['tenant_payment_notice' => $tpn], 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

}
