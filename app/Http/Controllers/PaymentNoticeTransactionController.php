<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Month;
use App\Models\PaymentCategory;
use App\Models\QuartersCategory;
use App\Models\Tenant;
use App\Models\IncomeAccountCode;
use App\Models\PaymentNoticeSchedule;
use App\Models\PaymentNoticeTransaction;
use App\Models\TenantsPenalty;
use App\Models\TenantsBlacklistPenalty;
use App\Models\TenantsPaymentNotice;
use App\Models\TenantsPaymentNoticeVotList;
use App\Http\Resources\ListData;
use App\Notifications\TenantsPaymentNoticeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class PaymentNoticeTransactionController extends Controller
{
    public function listYear()
    {
        $yearAll = ListData::Year();

        return view(getFolderPath().'.listYear', compact('yearAll'));
    }

    public function listPaymentNoticeSchedule(Request $request)
    {
        $year = $request->year;

        $paymentNoticeScheduleAll = PaymentNoticeSchedule::from('payment_notice_schedule as pns')
            ->select('pns.year', 'month.id AS month_id', 'month.name AS month_name', 'pns.month', 'pns.id AS payment_notice_schedule_id', 'pnt.flag_process',
                DB::raw('DATE_FORMAT(pns.payment_notice_date, "%d/%m/%Y") AS payment_notice_date'))
            ->join('month','pns.month','=','month.id')
            ->leftJoin('payment_notice_transaction AS pnt', function ($q) {
                $q->on('pns.year', '=', 'pnt.year')
                ->on('pns.month', '=', 'pnt.month');
            })
            ->where(['month.data_status'=>1, 'pns.data_status'=>1])
            ->where('pns.year', '=',  $year)
            ->where('pns.month', '<=',  currentMonthInYear($year))
            ->where('pns.payment_notice_date', '<=',  currentDateDb())
            ->groupBy('pns.year','pns.month')
            ->orderBy('pns.year','ASC')->orderBy('pns.month','ASC')
            ->get();
        
        if(checkPolicy("U") || checkPolicy("V")){
            return view(getFolderPath().'.listPaymentNoticeSchedule', compact('year', 'paymentNoticeScheduleAll')); 
        }else{
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function listTenant(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $paymentCategoryAll = PaymentCategory::where('data_status', 1)->get();
        $search_tenant_name = ($request->search_tenant_name) ? $request->search_tenant_name : "";
        $search_notice_no = ($request->search_notice_no) ? $request->search_notice_no : "";
        $search_payment_category = ($request->search_payment_category) ? $request->search_payment_category : "";

        $paymentNoticeTransaction = PaymentNoticeSchedule::from('payment_notice_schedule as pns')
            ->select('pnt.flag_process')
            ->join('month','pns.month','=','month.id')
            ->leftJoin('payment_notice_transaction AS pnt', function ($q) {
                $q->on('pns.year', '=', 'pnt.year')
                ->on('pns.month', '=', 'pnt.month');
            })
            ->where(['month.data_status'=>1, 'pns.data_status'=>1])
            ->where('pns.year', '=',  $year)
            ->where('pns.month', '=',  $month)
            ->first();

        //-------------------------------------------------------------------------------------------------------------------------------------------
        //TENANT LIST
        $notice_date = getEndDateOfMonth($year.'-'.$month.'-'.'01');
        $tenantPaymentNoticeAll = Tenant::from('tenants as t')->select('tpn.*','t.id AS tenants_id', 't.name', 't.new_ic', 't.services_type', 't.quarters_id');
        if ($search_notice_no || $search_payment_category) {
            $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->join('tenants_payment_notice AS tpn', function ($q) use ($year, $month, $search_notice_no, $search_payment_category) {
                $q->on('tpn.tenants_id', '=', 't.id')
                    ->where('tpn.data_status', 1)
                    ->where(DB::raw('YEAR(tpn.notice_date)'), '=',  $year)
                    ->where(DB::raw('MONTH(tpn.notice_date)'), '=',  $month);
                    if ($search_notice_no) $q->where('tpn.payment_notice_no', 'LIKE', '%' . $search_notice_no . '%');
                    if ($search_payment_category) $q->where('tpn.payment_category_id', $search_payment_category);
            });
        } else {
            $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->leftJoin('tenants_payment_notice AS tpn', function ($q) use ($year, $month) {
                $q->on('tpn.tenants_id', '=', 't.id')
                    ->where('tpn.data_status', 1)
                    ->where(DB::raw('YEAR(tpn.notice_date)'), '=',  $year)
                    ->where(DB::raw('MONTH(tpn.notice_date)'), '=',  $month);
            });
        }
        $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->where('t.data_status', 1)
            ->whereRaw('(t.leave_date is null OR t.leave_date >= "'.$notice_date.'")')
            ->where('t.quarters_acceptance_date','<=', $notice_date)
            ->orderBy('t.name', 'DESC');

        if ($search_tenant_name) $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->where('t.name', 'LIKE', '%' . $search_tenant_name . '%');
        
        $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->get();
        //-------------------------------------------------------------------------------------------------------------------------------------------

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.listTenant', 
                compact('year','month','paymentCategoryAll','tenantPaymentNoticeAll', 'paymentNoticeTransaction','search_tenant_name','search_notice_no','search_payment_category')
            ); 
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function process(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $tenants_id_arr = $request->tenants_id_arr;//[2];
        $services_status_arr = ListData::ServicesStatus()->pluck('id')->toArray(); //1:dalam perkhimatan, 2:pencen

        //SAVE/Update Payment Notice Transaction
        $paymentNoticeTransaction = PaymentNoticeTransaction::select('id','flag_process')->where(['data_status'=>1, 'year'=>$year, 'month'=> $month])->first();
        if($paymentNoticeTransaction){
            $paymentNoticeTransaction->flag_process = 1;
            $paymentNoticeTransaction->action_by = loginId();
            $paymentNoticeTransaction->action_on = currentDate();
            $paymentNoticeTransaction->save();
        }else{
            $paymentNoticeTransaction = new PaymentNoticeTransaction;
            $paymentNoticeTransaction->district_id = districtId();
            $paymentNoticeTransaction->year = $year;
            $paymentNoticeTransaction->month = $month;
            $paymentNoticeTransaction->action_by = loginId();
            $paymentNoticeTransaction->action_on = currentDate();
            $paymentNoticeTransaction->save();
        }
       
        if($paymentNoticeTransaction){
        
            $payment_notice_transaction_id = $paymentNoticeTransaction->id;
            
            if(isset($tenants_id_arr) && !empty($tenants_id_arr)) {
                
                $votIncomeAccountCode = [];

                //GET VOT 
                $dataIncomeAccountCode = IncomeAccountCode::from('income_account_code as iac')
                    ->select('iac.account_type_id', 'iac.flag_outstanding', 'iac.services_status_id', 'iac.flag_ispeks', 
                        'iac.id', 'pc.services_type_id', 'pc.id AS payment_category_id',
                        'iac.salary_deduction_code','iac.ispeks_account_code','iac.ispeks_account_description','iac.income_code','iac.income_code_description')
                    ->join("payment_category AS pc", 'pc.id','=','iac.payment_category_id')
                    ->where(['iac.data_status'=> 1,'pc.data_status'=> 1])
                    ->orderBy('iac.account_type_id')
                    ->get();

                //ACCUMULATE VOT/ACCOUNT TO ARRAY - FILTER BY ARRAY TO SPEED UP SEARCHING PROCESS 
                foreach($dataIncomeAccountCode as $data){
                    $services_type_id_arr = explode(',', $data->services_type_id);
                    foreach($services_type_id_arr as $services_type_id){
                        if($data->account_type_id && $services_type_id>0){
                            //format[account_type_id][services_type_id][flag_outstanding]
                            $votIncomeAccountCode[$data->account_type_id][$services_type_id][$data->flag_outstanding] = array(
                                "income_account_code_id" => $data->id,
                                "salary_deduction_code" => $data->salary_deduction_code,
                                "ispeks_account_code" => $data->ispeks_account_code,
                                "ispeks_account_description" => $data->ispeks_account_description,
                                "income_code" => $data->income_code,
                                "income_code_description" => $data->income_code_description,
                                "account_type_id" => $data->account_type_id,
                                "payment_category_id" => $data->payment_category_id,
                                "flag_outstanding" => $data->flag_outstanding,
                                "services_status_id" => $data->services_status_id,
                                "flag_ispeks" => $data->flag_ispeks
                            );
                        }
                    }
                }             
                //dd($votIncomeAccountCode);

                //GET TENANT DATA
                foreach($tenants_id_arr as $tenants_id){

                    $rental_amount = 0;
                    $outstanding_rental_amount = 0;
                    $total_rental = 0;
                    $damage_penalty_amount = 0;
                    $outstanding_damage_penalty_amount = 0;
                    $total_damage_penalty = 0;
                    $blacklist_penalty_amount = 0;
                    $outstanding_blacklist_penalty_amount = 0;
                    $total_blacklist_penalty = 0;
                    $total_penalty = 0;
                    $maintenance_fee_amount = 0;
                    $outstanding_maintenance_fee_amount = 0;
                    $total_maintenance_fee = 0;
                    $penalty_amount = 0;
                    $outstanding_penalty_amount = 0;
                    $total_amount_without_outstanding = 0;
                    $total_outstanding_amount = 0;
                    $total_amount = 0;
    
                    $dataTenant = Tenant::select('user_id','name','new_ic','organization_id','leave_date','services_type_id','rental_fee','maintenance_fee','user_id','quarters_category_id','quarters_id')->where('id', $tenants_id)->first();
                    
                    $address = '';
                    if($dataTenant){
                        $paymentCategory = PaymentCategory::select('id')->where('data_status',1)->whereRaw('FIND_IN_SET('. $dataTenant?->services_type_id .', services_type_id)')->first();
                        $payment_category_id = ($paymentCategory?->id) ? ($paymentCategory?->id) : 0;

                        $dataQuarters = $dataTenant?->quarters;
                        $address = $dataQuarters?->unit_no.', '.$dataQuarters?->address_1.' '.$dataQuarters?->address_2.' '.$dataQuarters?->address_3;

                        $services_type_id = $dataTenant->services_type_id;
                        $leave_date = $dataTenant->leave_date; 
                        $date_of_retirement = $dataTenant->user?->date_of_retirement ?? "";
                        $organization_id = $dataTenant->organization_id;

                        $is_pensioner = 0;
                        $currentDate = Carbon::now();
                        if($date_of_retirement){ $is_pensioner = ($date_of_retirement->lte($currentDate)) ?? 1; }
                        
                        $payment_notice_date = ($year==currentYear() && $month<currentMonthInYear($year) || $year<currentYear() && $month<=currentMonthInYear($year)) ? getEndDateOfMonth($year.'-'.$month.'-'.'01') : currentDateDb();
                        $month_outstanding = getPrevMonth($payment_notice_date, 1);//dd($month);
                        $year_outstanding = ($month_outstanding == 12) ? $year-1 : $year;

                        //GET OUTSTANDING - IF EXISTS 
                        //CHECK PREV DATA
                        $dataTenantOutstanding = TenantsPaymentNotice::select('total_rental', 'total_maintenance_fee')//'total_damage_penalty', 'total_blacklist_penalty', 
                            ->whereYear('notice_date', '=', $year_outstanding)->whereMonth('notice_date', '=', $month_outstanding)
                            ->where(['tenants_id'=> $tenants_id, 'payment_status'=> 0, 'data_status'=> 1])
                            ->first();
                        //dd($dataTenantOutstanding);
                        //GET RENTAL
                        $rental_amount = $dataTenant?->rental_fee ?? 0;
                        $outstanding_rental_amount = $dataTenantOutstanding?->total_rental ?? 0;
                        $total_rental = $outstanding_rental_amount + $rental_amount;

                        //NO OUTSTANDIG FOR PENALTY - IF PENALTY EXIST EVERY MONTH, REFER PENALTY RECORD
                        //CHECK DAMAGE PENALTY & OUTSTANDING IF EXISTS
                        $dataSumTenantsPenalty = TenantsPenalty::whereYear('penalty_date', '=', $year)->whereMonth('penalty_date', '=', $month)->where(['tenants_id'=> $tenants_id, 'data_status'=> 1])->sum('penalty_amount');
                        $damage_penalty_amount = $dataSumTenantsPenalty ?? 0;
                        //$outstanding_damage_penalty_amount = $dataTenantOutstanding?->total_damage_penalty ?? 0;
                        $total_damage_penalty = $damage_penalty_amount + $outstanding_damage_penalty_amount;

                        //CHECK BLACKLIST PENALTY & OUTSTANDING IF EXISTS
                        $dataSumTenantsBlacklistPenalty = TenantsBlacklistPenalty::whereYear('penalty_date', '=', $year)->whereMonth('penalty_date', '=', $month)->where(['tenants_id'=> $tenants_id, 'data_status'=> 1])->sum('penalty_amount');
                        $blacklist_penalty_amount = $dataSumTenantsBlacklistPenalty ?? 0;
                        //$outstanding_blacklist_penalty_amount = $dataTenantOutstanding?->total_blacklist_penalty ?? 0;
                        $total_blacklist_penalty = $blacklist_penalty_amount + $outstanding_blacklist_penalty_amount;
                     
                        //GET MAINTENANCE
                        $maintenance_fee_amount =  $dataTenant?->maintenance_fee ?? 0;
                        $outstanding_maintenance_fee_amount = $dataTenantOutstanding?->total_maintenance_fee ?? 0;
                        $total_maintenance_fee = $maintenance_fee_amount + $outstanding_maintenance_fee_amount;

                        //TOTAL
                        $penalty_amount = $damage_penalty_amount + $blacklist_penalty_amount;
                        $total_penalty = $total_damage_penalty + $total_blacklist_penalty;
                        $outstanding_penalty_amount = $outstanding_damage_penalty_amount + $outstanding_blacklist_penalty_amount;

                        $total_amount_without_outstanding = $rental_amount + $damage_penalty_amount + $blacklist_penalty_amount + $maintenance_fee_amount;
                        $total_outstanding_amount = $outstanding_rental_amount + $outstanding_maintenance_fee_amount;
                        $total_amount = $total_outstanding_amount + $total_amount_without_outstanding;

                        //DATA
                        $running_no = $this->_getCurrentRunningNo($year , $month);
                        $payment_notice_no = $this->_generateRefNo($running_no, district(districtId(), 'district_code'), $year , $month);
                        $notice_description = "NOTIS BAYARAN ".upperText(getMonthName($month))." ".$year ;
                        //SAVE TENANT PAYMENT NOTICE
                        $tenantsPaymentNotice = new TenantsPaymentNotice;
                        
                        $tenantsPaymentNotice->district_id = districtId();
                        $tenantsPaymentNotice->payment_notice_transaction_id = $payment_notice_transaction_id;
                        $tenantsPaymentNotice->payment_notice_no = $payment_notice_no;
                        $tenantsPaymentNotice->notice_description = $notice_description;
                        $tenantsPaymentNotice->running_no = $running_no;
                        $tenantsPaymentNotice->organization_id = $organization_id;
                        $tenantsPaymentNotice->tenants_id = $tenants_id;
                        $tenantsPaymentNotice->name = $dataTenant?->name;
                        $tenantsPaymentNotice->no_ic = $dataTenant?->new_ic;
                        $tenantsPaymentNotice->payment_category_id = $payment_category_id;
                        $tenantsPaymentNotice->services_type_id = $services_type_id;
                        $tenantsPaymentNotice->quarters_category_id = $dataTenant?->quarters_category_id;
                        $tenantsPaymentNotice->quarters_id = $dataTenant?->quarters_id;
                        $tenantsPaymentNotice->quarters_address = $address ?? "";
                        $tenantsPaymentNotice->notice_date = $payment_notice_date;
                        $tenantsPaymentNotice->rental_account_type_id = 1;
                        $tenantsPaymentNotice->rental_amount = $rental_amount;
                        $tenantsPaymentNotice->outstanding_rental_amount = $outstanding_rental_amount; 
                        $tenantsPaymentNotice->total_rental = $total_rental;
                        $tenantsPaymentNotice->penalty_account_type_id = 2;
                        $tenantsPaymentNotice->damage_penalty_amount = $damage_penalty_amount;
                        $tenantsPaymentNotice->outstanding_damage_penalty_amount = $outstanding_damage_penalty_amount;
                        $tenantsPaymentNotice->total_damage_penalty = $total_damage_penalty;
                        $tenantsPaymentNotice->blacklist_penalty_amount = $blacklist_penalty_amount;
                        $tenantsPaymentNotice->outstanding_blacklist_penalty_amount = $outstanding_blacklist_penalty_amount;
                        $tenantsPaymentNotice->total_blacklist_penalty = $total_blacklist_penalty;
                        $tenantsPaymentNotice->total_penalty = $total_penalty;
                        $tenantsPaymentNotice->maintenance_fee_account_type_id = 3;
                        $tenantsPaymentNotice->maintenance_fee_amount = $maintenance_fee_amount;
                        $tenantsPaymentNotice->outstanding_maintenance_fee_amount = $outstanding_maintenance_fee_amount;
                        $tenantsPaymentNotice->total_maintenance_fee = $total_maintenance_fee;
                        $tenantsPaymentNotice->total_amount_without_outstanding = $total_amount_without_outstanding;
                        $tenantsPaymentNotice->total_outstanding_amount = $total_outstanding_amount;
                        $tenantsPaymentNotice->total_amount = $total_amount;
                        $tenantsPaymentNotice->total_amount_after_adjustment = $total_amount;
                        $tenantsPaymentNotice->action_by = loginId();
                        $tenantsPaymentNotice->action_on = currentDate();
                        $tenantsPaymentNotice->save();
                        $tenantsPaymentNotice->refresh();
                        $tenants_payment_notice_id = $tenantsPaymentNotice->id;
                        
                        if($tenants_payment_notice_id>0){//dd($tenants_payment_notice_id);
                            $amount = 0;
                            //account_type_id -> 1:sewa, 2:denda, 3:penyelenggaraan
                            //flag_outstanding -> 1:normal, 2:Tunggakan:
                            //format[account_type_id][services_type_id][flag_outstanding][flag_pensioner]
                            //GET VOT 
                            $services_status_id = ($is_pensioner) ? 1 : 2;
                            for($account_type_id = 1; $account_type_id<=3; $account_type_id++){
                                //
                                $flag_outstanding = 1;
                                //dd($dataIncomeAccountCode[1]);
                                if(Arr::has($votIncomeAccountCode, $account_type_id.'.'.$services_type_id.'.'.$flag_outstanding) && in_array($services_status_id, $services_status_arr)){
                                    $dataIncomeAccountCode = $votIncomeAccountCode[$account_type_id][$services_type_id][$flag_outstanding];
                                    if($account_type_id==1) $amount = $rental_amount; 
                                    elseif($account_type_id==2) $amount = $penalty_amount;
                                    elseif($account_type_id==3) $amount = $maintenance_fee_amount;
                                    //SAVE TENANT PAYMENT NOTICE - VOT
                                    if($amount>0) $this->_saveTenantsPaymentNoticeVotList($tenants_payment_notice_id, $dataIncomeAccountCode, $amount);
                                }
                            
                                //OUTSTANDING
                                $flag_outstanding = 2;
                                if(Arr::has($votIncomeAccountCode, $account_type_id.'.'.$services_type_id.'.'.$flag_outstanding) && in_array($services_status_id, $services_status_arr)){
                                    $dataIncomeAccountCode = $votIncomeAccountCode[$account_type_id][$services_type_id][$flag_outstanding];
                                    if($account_type_id==1) $amount = $outstanding_rental_amount;
                                    elseif($account_type_id==2) $amount = $outstanding_penalty_amount;
                                    elseif($account_type_id==3) $amount = $outstanding_maintenance_fee_amount;
                                    //SAVE TENANT PAYMENT NOTICE - VOT
                                    if($amount>0) $this->_saveTenantsPaymentNoticeVotList($tenants_payment_notice_id, $dataIncomeAccountCode, $amount);
                                }
                                
                            }

                               
                            TenantsPenalty::whereYear('penalty_date', '=', $year)->whereMonth('penalty_date', '=', $month)->where(['tenants_id'=> $tenants_id, 'data_status'=> 1])
                                ->update([
                                    'tenants_payment_notice_id' => $tenants_payment_notice_id
                                ]);

                            TenantsBlacklistPenalty::whereYear('penalty_date', '=', $year)->whereMonth('penalty_date', '=', $month)->where(['tenants_id'=> $tenants_id, 'data_status'=> 1])
                                ->update([
                                    'tenants_payment_notice_id' => $tenants_payment_notice_id
                                ]);


                            //SEND NOTIFICATION TO USER
                            $tenantsPaymentNotice->tenant->user->notify(new TenantsPaymentNoticeNotification($year, $month, $tenantsPaymentNotice->tenants_payment_notice_id, $tenantsPaymentNotice->payment_notice_no));
                        }

                    }

                }
                
                return redirect()->route('paymentNoticeTransaction.listPaymentNoticeSchedule', ['year'=>$year, 'month'=>$month])->with('success', 'Notis bayaran '.$year.'/'.$month.' telah diproses!');
           
            }else{
                return redirect()->route('paymentNoticeTransaction.listTenant', ['id'=>$request->id, 'year'=>$year])->with('error', 'Notis bayaran '.$year.'/'.$month.' tidak berjaya diproses!');
            }
                
        }else{
            return redirect()->route('paymentNoticeTransaction.listTenant', ['id'=>$request->id, 'year'=>$year])->with('error', 'Notis bayaran '.$year.'/'.$month.' tidak berjaya diproses!');
        }
       
    }

    private function _saveTenantsPaymentNoticeVotList($tenants_payment_notice_id, $dataIncomeAccountCode, $amount)
    {   //dd($dataIncomeAccountCode);
        $income_account_code_id = $dataIncomeAccountCode['income_account_code_id'];
        $salary_deduction_code = $dataIncomeAccountCode['salary_deduction_code'];
        $ispeks_account_code = $dataIncomeAccountCode['ispeks_account_code'];
        $ispeks_account_description= $dataIncomeAccountCode['ispeks_account_description'];
        $income_code = $dataIncomeAccountCode['income_code'];
        $income_code_description = $dataIncomeAccountCode['income_code_description'];
        $account_type_id = $dataIncomeAccountCode['account_type_id'];
        $payment_category_id = $dataIncomeAccountCode['payment_category_id'];
        $flag_outstanding = $dataIncomeAccountCode['flag_outstanding'];
        $services_status_id = $dataIncomeAccountCode['services_status_id'];
        $flag_ispeks = $dataIncomeAccountCode['flag_ispeks'];

        if($amount > 0){
            $tenantsPaymentNoticeVotList = new TenantsPaymentNoticeVotList;
            $tenantsPaymentNoticeVotList->tenants_payment_notice_id = $tenants_payment_notice_id;
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
            $tenantsPaymentNoticeVotList->amount = $amount;
            $tenantsPaymentNoticeVotList->total_amount_after_adjustment = $amount;
            $tenantsPaymentNoticeVotList->action_by = loginId();
            $tenantsPaymentNoticeVotList->action_on = currentDate();
            $tenantsPaymentNoticeVotList->save(); 
        }
      
    }

    private function _getCurrentRunningNo($year , $month)
    {
        $latest_record = TenantsPaymentNotice::where('district_id',districtId())
            ->whereYear('notice_date', '=', $year)->whereMonth('notice_date', '=', $month)
            ->orderBy('id', 'desc')->first();
        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generateRefNo($running_no, $district_code, $year , $month)
    {
        $ref_no = str_pad($running_no, 6, "0", STR_PAD_LEFT);
        $year = substr($year, 2, 2);
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
        $ref_no = 'NB' . $district_code . $year . $month . $ref_no;
        return $ref_no;
    }

}
