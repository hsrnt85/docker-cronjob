<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_TenantsPaymentNotice;
use App\Models\Api\Api_TenantsPaymentTransaction;
use App\Models\Api\Api_TenantsPaymentTransactionVotList;
use App\Models\PaymentMethod;
use App\Http\Requests\PaymentDetailRequest;
use App\Models\Api\Api_Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class Api_JohorPayController extends Controller
{

    public function getBill(Request $request)
    {
        try {
            $apiKey = $request->header('X-API-KEY');
            //if($apiKey == config('env.prod_johorpay_api_key')){
            if($apiKey == config('env.stag_johorpay_api_key')){
               
                $flag_pengguna_sistem = $request->flag_pengguna_sistem;//dd($flag_pengguna_sistem);
                $agency_code = $request->kod_agensi;
                $ic = $request->no_kp;
                $payment_notice_no = $request->no_notis_bayaran;

                if($flag_pengguna_sistem == 1) return $this->getTenantBill($ic, $payment_notice_no);
                if($flag_pengguna_sistem == 2) return $this->getTenantBillByAgency($agency_code);

            }else{
                return response()->json([
                    'message' => "Token Error",
                ], 401);
            }

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
            ], 200);

        }
    }

    public function getTenantBill($ic, $payment_notice_no)
    {
        $msg = [];
        $data = [];
        $i = 0;
        if($ic || $payment_notice_no){

            $currentPaymentNotice = Api_TenantsPaymentNotice::where('data_status',1)->where('no_ic', $ic)->orderBy('notice_date','DESC')->first();
            $notice_date = ($currentPaymentNotice) ? $currentPaymentNotice->notice_date : "";

            $tpn = Api_TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                ->leftJoin('tenants_payment_transaction as tpt', 'tpt.tenants_payment_notice_id', '=', 'tpn.id')
                ->leftJoin('payment_method_johorpay as pmj', 'pmj.id', '=', 'tpt.payment_method_johorpay_id')
                ->select('tpn.id', 'tpn.no_ic', 'tpn.payment_notice_no', 'tpn.payment_status', 'tpn.quarters_category_id', 'tpn.quarters_address', 'tpn.name','tpt.payment_receipt_no','pmj.payment_method','tpt.payment_description')
                ->where('tpn.data_status', 1)->whereNot('tpn.payment_status', 1)->where('tpn.notice_date', $notice_date);

            if($ic)  $tpn = $tpn->where('tpn.no_ic', $ic);
            if($payment_notice_no)  $tpn = $tpn->where('tpn.payment_notice_no', $payment_notice_no);
            $tpn = $tpn->orderBy('tpn.notice_date', 'DESC')->first();
            //dd($tpn);

            if($tpn){
                $tpnVot = Api_TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                    ->select('tpnvl.ispeks_account_code', 'tpnvl.ispeks_account_description', 'tpn.total_amount_after_adjustment as amount', 'tpnvl.total_amount_after_adjustment as vot_amount', 'tpnvl.account_type_id')
                    ->join('tenants_payment_notice_vot_list as tpnvl', 'tpn.id', '=','tpnvl.tenants_payment_notice_id')
                    ->where('tpn.data_status', 1)->where('tpn.payment_status', 0)->where('tpn.id', $tpn->id)
                    ->groupBy('tpnvl.ispeks_account_code')->orderBy('tpn.notice_date', 'DESC')->orderBy('tpnvl.account_type_id')->get();
                //dd($tpnVot);
                
                $data[$i]['nama_penghuni'] = $tpn->name ?? "";
                $data[$i]['no_kp'] = $tpn->no_ic ?? "";
                $data[$i]['alamat_kuarters'] = $tpn->quarters_address ?? "";
                $data[$i]['id_notis_bayaran'] = $tpn->id ?? "0";
                $data[$i]['no_notis_bayaran'] = $tpn->payment_notice_no ?? "";
                $data[$i]['status_bayaran'] = $tpn->payment_status ?? "";
                $data[$i]['butiran_bayaran'] = $tpn->payment_description ?? ""; 
                $data[$i]['nama_pembayar'] = $tpn->name ?? "";            
                $data[$i]['no_resit'] = $tpn->payment_receipt_no ?? "";
                $data[$i]['cara_bayaran'] = $tpn->payment_method ?? "";

                $total_amount = 0;
                foreach($tpnVot as $tpnvot){
                    if($tpnvot->account_type_id == 1){ $code = "sewa"; $label = "Sewa"; }
                    elseif($tpnvot->account_type_id == 2){ $code = "denda"; $label = "Denda"; }
                    elseif($tpnvot->account_type_id == 3){ $code = "yuran_penyelenggaraan"; $label = "Yuran Penyelenggaraan"; }
                    $data[$i][$code]['label'] = $label;
                    $data[$i][$code]['kod_bendahari'] = $tpnvot->ispeks_account_code;
                    $data[$i][$code]['jenis_bayaran'] = $tpnvot->ispeks_account_description;
                    $data[$i][$code]['amaun'] = $tpnvot->vot_amount ?? "0.00";
                    $total_amount += $tpnvot->vot_amount;
                }

                $total_amount = ($tpn->payment_status == 2) ? 0 : $total_amount;
                $data[$i]['jumlah_perlu_bayar'] = numberFormatNoComma($total_amount) ?? "0.00";

                // $data['kuarters']['title'] = "Maklumat Kuarters";
                // $data['kuarters']['lokasi']['label'] = "Lokasi";
                // $data['kuarters']['alamat']['label'] = "Alamat";
                // $data['kuarters']['lokasi']['info'] = $tpn->quarters_category?->name ?? "";
                // $data['kuarters']['alamat']['info'] = $tpn->quarters_address ?? "";

                // $data['notis_bayaran']['title'] = "Maklumat Notis Bayaran";
                // $data['notis_bayaran']['id_notis_bayaran'] = $tpn->id ?? "0";
                // $data['notis_bayaran']['status_bayaran'] = $tpn->payment_status ?? "";
                // $data['notis_bayaran']['no_notis_bayaran'] = $payment_notice_no ?? "";
                // $data['notis_bayaran']['butiran_bayaran'] = $tpn->payment_description ?? ""; 
                // $data['notis_bayaran']['nama_pembayar'] = $tpn->name ?? "";            
                // $data['notis_bayaran']['no_resit'] = $tpn->payment_receipt_no ?? "";
                
                // $total_amount = 0;
                // foreach($tpnVot as $tpnvot){
                //     if($tpnvot->account_type_id == 1){ $code = "sewa"; $label = "Sewa"; }
                //     elseif($tpnvot->account_type_id == 2){ $code = "denda"; $label = "Denda"; }
                //     elseif($tpnvot->account_type_id == 3){ $code = "yuran_penyelenggaraan"; $label = "Yuran Penyelenggaraan"; }
                //     $data['notis_bayaran'][$code]['label'] = $label;
                //     $data['notis_bayaran'][$code]['kod_bendahari'] = $tpnvot->ispeks_account_code;
                //     $data['notis_bayaran'][$code]['jenis_bayaran'] = $tpnvot->ispeks_account_description;
                //     $data['notis_bayaran'][$code]['amaun'] = $tpnvot->vot_amount ?? "0.00";
                //     $total_amount += $tpnvot->vot_amount;
                // }

                // $data['notis_bayaran']['cara_bayaran'] = $tpn->payment_method ?? "";

                // $total_amount = ($tpn->payment_status == 2) ? 0 : $total_amount;
                // $data['notis_bayaran']['jumlah_perlu_bayar'] = numberFormatNoComma($total_amount) ?? "0.00";

                return response()->json([
                    'data' => $data,
                ], 200);

            }else{

                return response()->json([
                    'message' => "No record found.",
                ], 200);
            }
        
        }
       
    }

    public function getTenantBillByAgency($agency_code)
    {
        $msg = [];
        try {
           
            $data = [];
            if($agency_code){

                $organization_id = Api_Organization::get_organization($agency_code)?->id;
       
                $sub1 = Api_TenantsPaymentNotice::select(DB::raw('MAX(id) as id'))->groupBy('tenants_id');

                $tpn = Api_TenantsPaymentNotice::from('tenants_payment_notice as tpn')  
                    ->leftJoin('tenants_payment_transaction as tpt', 'tpt.tenants_payment_notice_id', '=', 'tpn.id')
                    ->leftJoin('payment_method_johorpay as pmj', 'pmj.id', '=', 'tpt.payment_method_johorpay_id')  
                    ->select('tpn.id', 'tpn.payment_notice_no', 'tpn.no_ic', 'tpn.name', 'tpn.payment_status', 'tpn.quarters_category_id', 'tpn.quarters_address', 'tpn.name','tpt.payment_receipt_no','pmj.payment_method','tpt.payment_description')
                    ->join(DB::raw('(' . $sub1->toSql() . ') tpn2'), function ($join) use($organization_id){
                        $join->on('tpn.id', '=', 'tpn2.id')
                            ->where(['tpn.data_status' => 1, 'tpn.organization_id'=> $organization_id]);
                    })
                    ->where(['tpn.data_status' => 1, 'tpn.organization_id'=> $organization_id])
                    ->orderByDesc('tpn.notice_date')
                    ->orderByDesc('tpn.running_no')
                    ->get();

                //dd($tpn);
                if($tpn->count()){

                    foreach($tpn as $i => $tpnData){
                      
                        $tpnVot = Api_TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                            ->select('tpnvl.ispeks_account_code', 'tpnvl.ispeks_account_description', 'tpn.total_amount_after_adjustment as amount', 'tpnvl.total_amount_after_adjustment as vot_amount', 'tpnvl.account_type_id')
                            ->join('tenants_payment_notice_vot_list as tpnvl', 'tpn.id', '=','tpnvl.tenants_payment_notice_id')
                            ->where('tpn.data_status', 1)->where('tpn.id', $tpnData->id)//->where('tpn.payment_status', 0)
                            ->groupBy('tpnvl.ispeks_account_code')->orderBy('tpn.notice_date', 'DESC')->orderBy('tpnvl.account_type_id')
                            ->get();

                        $data[$i]['nama_penghuni'] = $tpnData->name ?? "";
                        $data[$i]['no_kp'] = $tpnData->no_ic ?? "";
                        $data[$i]['alamat_kuarters'] = $tpnData->quarters_address ?? "";
                        $data[$i]['id_notis_bayaran'] = $tpnData->id ?? "0";
                        $data[$i]['no_notis_bayaran'] = $tpnData->payment_notice_no ?? "";
                        $data[$i]['status_bayaran'] = $tpnData->payment_status ?? "";
                        $data[$i]['butiran_bayaran'] = $tpnData->payment_description ?? ""; 
                        $data[$i]['nama_pembayar'] = $tpnData->name ?? "";            
                        $data[$i]['no_resit'] = $tpnData->payment_receipt_no ?? "";
                        $data[$i]['cara_bayaran'] = $tpnData->payment_method ?? "";

                        $total_amount = 0;
                        foreach($tpnVot as $tpnvot){
                            if($tpnvot->account_type_id == 1){ $code = "sewa"; $label = "Sewa"; }
                            elseif($tpnvot->account_type_id == 2){ $code = "denda"; $label = "Denda"; }
                            elseif($tpnvot->account_type_id == 3){ $code = "yuran_penyelenggaraan"; $label = "Yuran Penyelenggaraan"; }
                            $data[$i][$code]['label'] = $label;
                            $data[$i][$code]['kod_bendahari'] = $tpnvot->ispeks_account_code;
                            $data[$i][$code]['jenis_bayaran'] = $tpnvot->ispeks_account_description;
                            $data[$i][$code]['amaun'] = $tpnvot->vot_amount ?? "0.00";
                            $total_amount += $tpnvot->vot_amount;
                        }

                        $total_amount = ($tpnData->payment_status == 2) ? 0 : $total_amount;
                        $data[$i]['jumlah_perlu_bayar'] = numberFormatNoComma($total_amount) ?? "0.00";
                    }
                    //dd($data);
                    return response()->json([
                        'data' => $data,
                    ], 200);

                }else{

                    return response()->json([
                        'message' => "No record found.",
                    ], 200);
                }
            
            }

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function processPayment(Request $request)
    {
        DB::beginTransaction();

        try {
             $apiKey = $request->header('X-API-KEY');
            //if($apiKey == config('env.prod_johorpay_api_key')){
            if($apiKey == config('env.stag_johorpay_api_key')){
                
                $json = file_get_contents('php://input');
				$data = json_decode($json);

                $payment_notice_arr = $data->payment_items;
                
                $payment_category_id = 3;
                $ispeks_payment_code_id = 0;
                $payment_type_id = $data->payment_type_id;
                if($payment_type_id == 1||$payment_type_id == 2) $ispeks_payment_code_id = 8;
                else if($payment_type_id == 3) $ispeks_payment_code_id = 10;

                $payment_method_id = PaymentMethod::where(['data_status'=> 1, 'ispeks_payment_code_id'=> $ispeks_payment_code_id, 'payment_category_id'=> $payment_category_id])->first()?->id;
                        
                $payment_date  = getDateFromDateTime($data->payment_date);
                $action_on  = $data->payment_date;

                if($payment_notice_arr){

                    foreach($payment_notice_arr as $payment_notice){

                        // $payment_notice_id = isset($payment_notice['payment_notice_id']) ? $payment_notice['payment_notice_id'] : null;; //dd($payment_notice_id);
                        // $receipt_no = $payment_notice['receipt_no']; //dd($receipt_no);
                        $payment_notice_id = isset($payment_notice->payment_notice_id) ? $payment_notice->payment_notice_id : null;; //dd($payment_notice_id);
                        $receipt_no = $payment_notice->receipt_no; //dd($receipt_no);

                        $tpn = Api_TenantsPaymentNotice::where('id', $payment_notice_id)->where('payment_status', 0)->where('data_status', 1)->first();
                            
                        if($tpn){
                            $tenants_payment_notice_id = $tpn->id;
                            //$year = getYearFromDate($tpn->notice_date);
                            //$month = getMonthFromDate($tpn->notice_date);
                            $payment_description = "BAYARAN KUARTERS KERAJAAN NEGERI JOHOR ";

                            $tpnt = new Api_TenantsPaymentTransaction;
                            $tpnt->payment_notice_no = $tpn->payment_notice_no;
                            $tpnt->notice_date = $tpn->notice_date;
                            $tpnt->tenants_payment_notice_id = $tenants_payment_notice_id;
                            $tpnt->payment_category_id = $payment_category_id;
                            $tpnt->payment_method_id = $payment_method_id;
                            $tpnt->payment_method_johorpay_id = $payment_type_id;
                            $tpnt->reconciliation_transaction_id = null;
                            $tpnt->collector_statement_id = null;
                            $tpnt->payment_receipt_no = $receipt_no;
                            $tpnt->district_id = $tpn->district_id;
                            $tpnt->tenants_id = $tpn->tenants_id;
                            $tpnt->payment_date = $payment_date;
                            $tpnt->payment_description = $payment_description;
                            $tpnt->total_payment = $data->total_payment;
                            $tpnt->online_payment_refno = $data->online_payment_refno;
                            $tpnt->online_payment_status = $data->online_payment_status;
                            //$tpnt->data_status = 1;
                            $tpnt->action_on = $action_on;
                            $tpnt->save();

                            $tenants_payment_transaction_id = $tpnt?->id;

                            if($tenants_payment_transaction_id>0){
                                //UPDATE ALL NOTICE INCLUDING TUNGGAKAN
                                Api_TenantsPaymentNotice::where('tenants_id', $tpn->tenants_id)
                                ->update([
                                    'tenants_payment_transaction_id' => $tenants_payment_transaction_id,
                                    'payment_status' => 2,
                                    //'total_payment_amount' => DB::raw('`total_amount`'),
                                    'action_on' => currentDate(),
                                ]);

                                //COPY ALL VOT LIST
                                DB::insert("INSERT INTO tenants_payment_transaction_vot_list SELECT 0, a.* FROM tenants_payment_notice_vot_list a WHERE a.tenants_payment_notice_id = ".$tenants_payment_notice_id);
                                Api_TenantsPaymentTransactionVotList::where('tenants_payment_notice_id', $tenants_payment_notice_id)->update(['tenants_payment_transaction_id' => $tenants_payment_transaction_id]);

                            }
                           
                        }

                        DB::commit();

                    }

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Successfully updated.',
                    ], 200);

                }else{
                    
                    return response()->json([
                        'message' => 'Data is empty',
                    ], 200);
                }
            }else{
                return response()->json([
                    'message' => 'token error',
                ], 401);
            }
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
