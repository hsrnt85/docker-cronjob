<?php

namespace App\Http\Controllers;

use App\Models\TenantsPaymentNotice;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class IndividualStatementReportController extends Controller
{
    public function index(Request $request)
    {
        $district_id = (!is_all_district()) ? districtId() : null;
        $search_date_from = ($request->search_date_from) ? $request->search_date_from : null;
        $search_date_to = ($request->search_date_to) ? $request->search_date_to : null;
        $search_date_from_db = convertDatepickerDb($search_date_from);
        $search_date_to_db = convertDatepickerDb($search_date_to);
        $search_ic_no = ($request->search_ic_no) ? $request->search_ic_no : null;
        $dataReport = null;
        $dataTenant = null;
        $dataBbUnpaid = null;
        $dataBbPaid = null;

        if($search_date_from && $search_date_to && $search_ic_no){

            $dataTenant = TenantsPaymentNotice::select('name','quarters_address')->where(['data_status'=>1, 'no_ic'=>$search_ic_no])->groupBy('no_ic')->first();

            //------------------------------------------------------------------------------------------------------------------------------------------------------
            //DATA BAKI BAWA HADAPAN
            //------------------------------------------------------------------------------------------------------------------------------------------------------
            $month_bb = getPrevMonth($search_date_from_db, 1);//dd($month_bb);
            //$month_bb = getPrevMonth($search_date_from_db, 2);//dd($month_bb);
            $year = getYearFromDate($search_date_from_db);
            $year_bb = ($month_bb == 12) ? $year-1 : $year;

            $dataBbUnpaid = TenantsPaymentNotice::selectRaw('total_amount AS bb_amount')
                ->whereYear('notice_date',$year_bb)->whereMonth('notice_date',$month_bb)
                ->where(['data_status'=>1, 'payment_status'=>0, 'no_ic'=>$search_ic_no])
                ->first();
            
            $dataBbPaid = TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                ->join('tenants_payment_transaction AS tpt', 'tpt.id', '=', 'tpn.tenants_payment_transaction_id')
                ->select('tpn.total_amount AS bb_amount')
                ->where(['tpn.data_status'=> 1,'tpt.data_status'=> 1, 'tpn.payment_status'=>2])
                ->where('tpn.no_ic', $search_ic_no)
                ->whereYear('tpn.notice_date',$year_bb)->whereMonth('tpn.notice_date',$month_bb)
                ->first();
                
            //------------------------------------------------------------------------------------------------------------------------------------------------------
            //DATA NOTIS BAYARAN
            //------------------------------------------------------------------------------------------------------------------------------------------------------//------------------------------------------------------------------------------------------------------------------------------------------------------
            $dataTpn = TenantsPaymentNotice::select('notice_date AS transaction_date', 'payment_notice_no AS transaction_no', 'notice_description AS description')
                ->selectRaw('total_amount_without_outstanding AS debit_amount, "0" AS credit_amount')
                ->whereBetween('notice_date', [$search_date_from_db, $search_date_to_db])
                ->where(['data_status'=>1, 'no_ic'=>$search_ic_no]);

            //------------------------------------------------------------------------------------------------------------------------------------------------------
            //DATA PELARASAN
            //------------------------------------------------------------------------------------------------------------------------------------------------------
            $dataTpnAdj = TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                ->join('internal_journal AS ij', 'tpn.id', '=', 'ij.tenants_payment_notice_id')
                ->join('tenants_payment_notice_vot_list AS tpnvl', 'ij.id', '=', 'tpnvl.internal_journal_id')
                ->select('ij.journal_date AS transaction_date', 'ij.journal_no AS transaction_no','ij.description AS description')
                ->selectRaw('SUM(tpnvl.debit_amount) AS debit_amount, SUM(tpnvl.credit_amount) AS credit_amount')
                ->where(['tpn.data_status'=> 1, 'ij.data_status'=> 1, 'tpnvl.data_status'=> 1])
                ->whereBetween('ij.journal_date', [$search_date_from_db, $search_date_to_db])
                ->where('tpn.no_ic', $search_ic_no)->groupBy('transaction_no');

            //------------------------------------------------------------------------------------------------------------------------------------------------------
            //DATA BAYARAN - JOHORPAY
            //------------------------------------------------------------------------------------------------------------------------------------------------------
            $dataTpt = TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                ->join('tenants_payment_transaction AS tpt', 'tpt.tenants_payment_notice_id', '=', 'tpn.id')
                ->select('tpt.payment_date AS transaction_date', 'payment_receipt_no AS transaction_no','payment_description AS description')
                ->selectRaw('"0" AS debit_amount, total_payment AS credit_amount')
                ->where(['tpn.data_status'=> 1,'tpt.data_status'=> 1])->where('tpn.payment_category_id',3)
                ->whereBetween('tpt.payment_date', [$search_date_from_db, $search_date_to_db])
                ->where('tpn.no_ic', $search_ic_no);//->whereRaw('(tenants_payment_transaction_id is null OR tenants_payment_transaction_id = 0)');//where('tenants_payment_transaction_id',0)->orWhereNull('tenants_payment_transaction_id');

            //------------------------------------------------------------------------------------------------------------------------------------------------------
            //DATA BAYARAN - IGFMAS, ISPEKS
            //------------------------------------------------------------------------------------------------------------------------------------------------------
            $dataTptRecon = TenantsPaymentNotice::from('tenants_payment_notice as tpn')
                ->join('tenants_payment_transaction AS tpt', 'tpt.id', '=', 'tpn.tenants_payment_transaction_id')
                ->select('payment_date AS transaction_date', 'payment_receipt_no AS transaction_no','payment_description AS description')
                ->selectRaw('"0" AS debit_amount, tpn.total_amount_after_adjustment AS credit_amount')
                ->where(['tpn.data_status'=> 1,'tpt.data_status'=> 1])->where('payment_status',2)->whereIn('tpn.payment_category_id',[1,2])
                ->whereBetween('tpt.payment_date', [$search_date_from_db, $search_date_to_db])
                ->where('tpn.no_ic', $search_ic_no)->where('tenants_payment_transaction_id','>',0);

            //DATA ALL TRANSACTION
            $dataReport = $dataTpn->unionAll($dataTpnAdj)->unionAll($dataTpt)->unionAll($dataTptRecon)->orderBy('transaction_date') ->get();
        }

        $dataReturn = compact( 'dataTenant', 'dataBbUnpaid', 'dataBbPaid', 'dataReport', 'search_date_from', 'search_date_to', 'search_ic_no');

        if($request->input('muat_turun_pdf'))
        {
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath().'.cetak-pdf', array_merge($dataReturn, compact('totalPages')))->setPaper('A4');

            return $pdf->stream('Laporan_Penyata_Individu_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index', $dataReturn);

        }
    }
}
