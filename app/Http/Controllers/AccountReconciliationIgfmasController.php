<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IncomeAccountCode;
use App\Models\Tenant;
use App\Models\TenantsPaymentNotice;
use App\Models\PaymentNoticeTransaction;
use App\Models\TenantsPaymentTransaction;
use App\Models\TenantsPaymentTransactionVotList;
use App\Models\ReconciliationTransaction;
use App\Models\ReconciliationTransactionIgfmas;
use App\Models\ReconciliationTransactionIgfmasItem;
use App\Models\FinanceDepartment;
use App\Models\PaymentMethod;
use App\Excel\ExcelData;
use App\Models\PaymentCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Excel;

class AccountReconciliationIgfmasController extends Controller
{
    //igfmas
    private $payment_category_id = 1; 

    public function listYearMonth()
    {
        $yearMonthAll = PaymentNoticeTransaction::get_year_month($this->payment_category_id);
        
        return view(getFolderPath().'.listYearMonth', compact('yearMonthAll'));
    }

    public function listTransaction(Request $request)
    {
        $year = ($request->year) ? $request->year : "";
        $month = ($request->month) ? $request->month : "";

        $accountReconciliationAll = ReconciliationTransactionIgfmas::reconciliation_transaction_list($year, $month, $this->payment_category_id);
        $accountReconciliationAll = $accountReconciliationAll->get();
        $accountReconciliation = $accountReconciliationAll->first();
        $payment_status = $accountReconciliation?->data_status;
       
        return view(getFolderPath().'.listTransaction', compact('year','month','accountReconciliationAll','payment_status'));
    }

    public function create(Request $request)
    {
        $paymentMethod = PaymentMethod::getPaymentMethod($this->payment_category_id);
        $salaryDeductionCodeAll = IncomeAccountCode::get_salary_deduction_code($this->payment_category_id);
        
        $year = ($request->year) ? $request->year : "";
        $month = ($request->month) ? $request->month : "";
        
        $salary_deduction_code = ($request->salary_deduction_code) ? $request->salary_deduction_code : "";
        $recon = ReconciliationTransactionIgfmas::reconciliation_transaction_by_year_month($year, $month);
        
        $accountReconciliationItem = null;
        
        $id = isset($request->id) ? $request->id : 0;

        if($id){
            $accountReconciliationItem = ReconciliationTransactionIgfmasItem::reconciliation_transaction_item_by_id($id);
        }
        
        if(checkPolicy("A")){
            return view(getFolderPath().'.create', compact('paymentMethod','salaryDeductionCodeAll','year','month','salary_deduction_code','accountReconciliationItem','recon')); 
        }else{
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function edit(Request $request)
    {

        $year = ($request->year) ? $request->year : "";
        $month = ($request->month) ? $request->month : "";

        $salaryDeductionCode = ReconciliationTransactionIgfmas::salary_deduction_sode_summary($year, $month);
        $salaryDeductionCodeSummary = $salaryDeductionCode->get();
        $id = $salaryDeductionCode->first()->pluck('id');
        
        if(checkPolicy("A")){
            return view(getFolderPath().'.edit', compact('id','year','month','salaryDeductionCodeSummary')); 
        }else{
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function view(Request $request)
    {
         
        $id = $request->id;
        $year = ($request->year) ? $request->year : "";
        $month = ($request->month) ? $request->month : "";
        
        $accountReconciliation = ReconciliationTransactionIgfmas::reconciliation_transaction_by_id($id);
        $accountReconciliationItem = ReconciliationTransactionIgfmasItem::reconciliation_transaction_item_by_id($id);
        $recon = ReconciliationTransactionIgfmas::reconciliation_transaction_by_year_month($year, $month);

        if(checkPolicy("A")){
            return view(getFolderPath().'.view', compact('year','month','accountReconciliation','accountReconciliationItem','recon')); 
        }else{
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function processFile(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $year = $request->year;
            $month = $request->month;
            $salary_deduction_code = $request->salary_deduction_code;
            $payment_method_id = $request->payment_method_id;
            $eft_no = $request->eft_no;
            $eft_date = convert_date_db($request->eft_date);
            $file_upload = $request->file_upload;
            $file_name = $file_upload->getClientOriginalName();

            $storage_folder = 'reconciliatian/igfmas';
            $path_file = $file_upload->storeAs($storage_folder, $file_name , 'assets-upload');

            $running_no   = $this->_getCurrentRunningNo($year, $month);
            $reconciliation_no = $this->_generateRefNo($year,$month,$running_no);

            if ($request->hasFile('file_upload')) {

                //Transaction Item 
                $excelData = Excel::toArray(new ExcelData, $request->file_upload)[0];

                if($excelData){
                    //Transaction
                    $transaction = new ReconciliationTransactionIgfmas;
                    $transaction->reconciliation_no = $reconciliation_no;
                    $transaction->salary_deduction_code = $salary_deduction_code;
                    $transaction->running_no = $running_no;
                    $transaction->file_name = $file_name;
                    $transaction->year = $year;
                    $transaction->month = $month;
                    $transaction->payment_method_id = $payment_method_id;
                    $transaction->eft_no = $eft_no;
                    $transaction->eft_date = $eft_date;
                    $transaction->action_by = loginId();
                    $transaction->action_on = currentDate();
                    $transaction->save(); 

                    $id = $transaction->id;

                    foreach ($excelData as $row){
                        $col_index=0;
                        $ic_no = removeWhiteSpace($row[$col_index]); $col_index++;
                        $name = $row[$col_index]; $col_index++;
                        $amount = ($row[$col_index]) ? removeWhiteSpace(removeComma($row[$col_index])) : 0;
                        if((strlen($ic_no)==12) ){
                            $transactionItem = new ReconciliationTransactionIgfmasItem;
                            $transactionItem->reconciliation_transaction_igfmas_id = $transaction->id;
                            $transactionItem->ic_no = $ic_no;
                            $transactionItem->name = $name;
                            $transactionItem->amount = $amount;
                            $transactionItem->save(); 
                        }
                    }

                    DB::commit();

                    return redirect()->route('accountReconciliationIgfmas.view', ['id'=>$id, 'year' => $year, 'month' => $month])->with('success', 'Fail excel telah diproses !!');
            
                }else{
                    
                    DB::rollback();
                    return redirect()->route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month])->with('success', 'Tiada Data yang telah diproses !!');
                
                }
            }
        } catch (\Exception $e) {
            
            DB::rollback();
            return redirect()->route('accountReconciliationIgfmas.create', ['year' => $year, 'month' => $month])->with('error', 'Fail excel tidak berjaya diproses !!' . ' ' . $e->getMessage());

        }
   
    }

    public function processPayment(Request $request)
    {
        try {
            DB::beginTransaction();

            $year = $request->year;
            $month = $request->month;
            $salary_deduction_code_arr = $request->salary_deduction_code;
            $payment_method_id = $request->payment_method_id[0];
            $reconciliation_transaction_id_arr = $request->reconciliation_transaction_id;
            $flag_pengesahan = 0;
            $reconciliationTransactionIgfmas = ReconciliationTransactionIgfmas::reconciliation_transaction($year, $month);
            $online_payment_refno = $reconciliationTransactionIgfmas?->eft_no ?? "";
            //dd($online_payment_refno);

            //MATCHING DATA - CHECKING PAID TENANTS
            $paidTenantsBySalaryDeductionCode = [];
            $tenantsPaymentNoticeVotList = [];
            foreach ($salary_deduction_code_arr as $salary_deduction_code){
                $tenantsPaymentNoticeVotList[] = TenantsPaymentNotice::tenants_payment_notice_vot($salary_deduction_code, $year, $month, $this->payment_category_id);
                $tenantsPaymentNoticeItem = TenantsPaymentNotice::tenants_payment_notice_item($salary_deduction_code, $year, $month, $this->payment_category_id);
                $reconciliationTransactionItem = ReconciliationTransactionIgfmas::reconciliation_transaction_item($salary_deduction_code, $year, $month);
                $paidTenantsBySalaryDeductionCode[$salary_deduction_code] = $tenantsPaymentNoticeItem->intersect($reconciliationTransactionItem);             
            }
            //dd($tenantsPaymentNoticeVotList);

            $paidTenantsIcArr = [];
            $total_payment = 0;
            if($paidTenantsBySalaryDeductionCode){
                    
                foreach($paidTenantsBySalaryDeductionCode as $data){
                    foreach($data as $i => $dataTenant){ 
                        $ic_no = $dataTenant['ic_no'];
                        if(!in_array($ic_no, $paidTenantsIcArr)) $paidTenantsIcArr[] = $dataTenant['ic_no']; 
                        $total_payment += $dataTenant['amount'];
                    }
                }
                //dd($paidTenantsIcArr);
                if(!empty($paidTenantsIcArr)){
 
                    //SAVE DATA PAYMENT
                    $payment_running_no   = $this->_getCurrentPaymentRunningNo($year, $month, $this->payment_category_id);
                    $payment_receipt_no = $this->_generatePaymentRefNo($year, $month, $payment_running_no);
                    $payment_description = "BAYARAN SEWA/DENDA/PENYELENGGARAAN KUARTERS - ".upperText(getMonthName($month))." ".$year ;//"BAYARAN PENGHUNI MENGIKUT POTONGAN GAJI (IGFMAS) BAGI BULAN ".$month." ".$year;
                    $notice_date = getEndDateOfMonth($year.'-'.$month.'-'.'01');
                    
                    $tenantsPaymentTransaction = new TenantsPaymentTransaction;
                    $tenantsPaymentTransaction->district_id = districtId();
                    $tenantsPaymentTransaction->notice_date = $notice_date;
                    $tenantsPaymentTransaction->reconciliation_transaction_id = ArrayToString($reconciliation_transaction_id_arr);
                    $tenantsPaymentTransaction->payment_date = currentDate();
                    $tenantsPaymentTransaction->payment_category_id = $this->payment_category_id;
                    $tenantsPaymentTransaction->payment_method_id = $payment_method_id;
                    $tenantsPaymentTransaction->payment_running_no = $payment_running_no;
                    $tenantsPaymentTransaction->payment_receipt_no = $payment_receipt_no;
                    $tenantsPaymentTransaction->payment_description = $payment_description;
                    $tenantsPaymentTransaction->online_payment_refno = $online_payment_refno;
                    $tenantsPaymentTransaction->online_payment_status = 1;
                    $tenantsPaymentTransaction->total_payment = $total_payment;
                    $tenantsPaymentTransaction->action_by = loginId();
                    $tenantsPaymentTransaction->action_on = currentDate();
                    $tenantsPaymentTransaction->save();
                    $tenantsPaymentTransaction->refresh();

                    $tenants_payment_transaction_id = $tenantsPaymentTransaction->id;

                    foreach($tenantsPaymentNoticeVotList as $dataVotList){
                        foreach($dataVotList as $data){
                            $tenantsPaymentTransactionVotList = new TenantsPaymentTransactionVotList;
                            $tenantsPaymentTransactionVotList->tenants_payment_transaction_id = $tenants_payment_transaction_id;
                            $tenantsPaymentTransactionVotList->income_account_code_id = $data->income_account_code_id;
                            $tenantsPaymentTransactionVotList->salary_deduction_code = $data->salary_deduction_code;
                            $tenantsPaymentTransactionVotList->ispeks_account_code = $data->ispeks_account_code;
                            $tenantsPaymentTransactionVotList->ispeks_account_description= $data->ispeks_account_description;
                            $tenantsPaymentTransactionVotList->income_code = $data->income_code;
                            $tenantsPaymentTransactionVotList->income_code_description = $data->income_code_description;
                            $tenantsPaymentTransactionVotList->account_type_id = $data->account_type_id;
                            $tenantsPaymentTransactionVotList->payment_category_id = $data->payment_category_id;
                            $tenantsPaymentTransactionVotList->flag_outstanding = $data->flag_outstanding; 
                            $tenantsPaymentTransactionVotList->services_status_id = $data->services_status_id;
                            $tenantsPaymentTransactionVotList->flag_ispeks = $data->flag_ispeks;
                            $tenantsPaymentTransactionVotList->amount = $data->amount;
                            $tenantsPaymentTransactionVotList->total_amount_after_adjustment = $data->amount;
                            $tenantsPaymentTransactionVotList->action_by = loginId();
                            $tenantsPaymentTransactionVotList->action_on = currentDate();
                            $tenantsPaymentTransactionVotList->save(); 
                        }
                    }

                    //UPDATE Tenants Payment Notice, payment_tatus = 2:Paid
                    TenantsPaymentNotice::whereIn('no_ic', $paidTenantsIcArr)->whereYear('notice_date', $year)->whereMonth('notice_date', $month)->update(['tenants_payment_transaction_id'=>$tenants_payment_transaction_id, 'payment_status' => 2, 'total_payment_amount'=> DB::raw('`total_amount_after_adjustment`')]);
                    
                    //UPDATE Reconciliation Transaction Igfmas, data_status = 1:Telah Proses
                    ReconciliationTransactionIgfmas::from('reconciliation_transaction_igfmas as rt')
                        ->join('reconciliation_transaction_igfmas_item as rti','rt.id','=','rti.reconciliation_transaction_igfmas_id')
                        ->where('year', $year)->where('month', $month)
                        ->whereIn('ic_no', $paidTenantsIcArr)
                        ->update(['rt.tenants_payment_transaction_id' => $tenants_payment_transaction_id, 'rt.data_status' => 1, 'rti.data_status' => 1]);    
                        
                    //SAVE Reconciliation Transaction - flag process
                    $reconciliationTransaction = new ReconciliationTransaction;
                    $reconciliationTransaction->payment_category_id = $this->payment_category_id;
                    $reconciliationTransaction->year = $year;
                    $reconciliationTransaction->month = $month;
                    $reconciliationTransaction->action_by = loginId();
                    $reconciliationTransaction->action_on = currentDate();
                    $reconciliationTransaction->save();

                    DB::commit();

                    $flag_pengesahan = 1;

                    return redirect()->route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month, 'id'=> $tenants_payment_transaction_id ])->with('success', 'Pengesahan data telah berjaya !!');

                }

            }
            
            if($flag_pengesahan == 0){

                return redirect()->route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month])->with('error', 'Tiada pengesahan data dilakukan !!');
          
            }

        } catch (\Exception $e) {
            
            DB::rollback();
            return redirect()->route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month])->with('error', 'Pengesahan data tidak berjaya !!' . ' ' . $e->getMessage());

        }
   
    }

    public function deleteByRow(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $kod_potongan = $request->kod_potongan;
        $id = $request->id_by_row;

        try {
            ReconciliationTransactionIgfmas::where('id', $id)
                    ->update([
                        'data_status' => 0,
                        'delete_by' => loginId(),
                        'delete_on' => currentDate()
                    ]);

            ReconciliationTransactionIgfmasItem::where('reconciliation_transaction_igfmas_id', $id)
                    ->update([
                        'data_status' => 0,
                        'delete_by' => loginId(),
                        'delete_on' => currentDate()
                    ]);

        } catch (\Exception $e) {
            return redirect()->route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month])->with('error', 'Transaksi potongan '.$kod_potongan.' tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month])->with('success', 'Transaksi potongan '.$kod_potongan.' berjaya dihapus!');
    }

    public function getPaymentReceipt(Request $request)
    {
        $year = ($request->year) ? $request->year : "";
        $month = ($request->month) ? $request->month : "";
        $tpt = TenantsPaymentTransaction::get_tenants_payment_transaction_by_month_year($year, $month, $this->payment_category_id);
        $recon = ReconciliationTransactionIgfmas::reconciliation_transaction_by_year_month($year, $month);
        $finance_dept = FinanceDepartment::finance_department_by_district(1);
        $payer = PaymentCategory::select('payer_name','payer_address')->where(['id'=>1, 'data_status'=>1])->first();

        $pdf = PDF::loadview(getFolderPath().'.cetak-resit-bayaran', compact('tpt', 'recon', 'finance_dept', 'payer'));
        $pdf->setPaper('A4');

        return $pdf->stream('resit-bayaran-'.$tpt->payment_date.'.pdf');
    }

    //-------------------------------------------------------------------------------------------------------------------------------------
    
    private function _getCurrentRunningNo($year, $month)
    {
        $latest_record = ReconciliationTransactionIgfmas::where(['year'=> $year, 'month'=> $month])->orderBy('id', 'desc')->first();
        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generateRefNo($year, $month, $running_no)
    {
        $ref_no = str_pad($running_no, 3, "0", STR_PAD_LEFT);
        $ref_no = 'PA/I/'.$year.$month. $ref_no;
        return $ref_no;
    }

    private function _getCurrentPaymentRunningNo($year, $month, $payment_category_id)
    {
        $latest_record = TenantsPaymentTransaction::whereYear('payment_date', $year)->whereMonth('payment_date', $month)->where('payment_category_id', $payment_category_id)->orderBy('id', 'desc')->first();
        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generatePaymentRefNo($year, $month, $running_no)
    {
        $ref_no = str_pad($running_no, 5, "0", STR_PAD_LEFT);
        //$ref_no = 'RS/IG/'.$year.$month. $ref_no;
        $ref_no = currentYearTwoDigit(). 'SKJTR05' . $ref_no;
        return $ref_no;
    }

}
