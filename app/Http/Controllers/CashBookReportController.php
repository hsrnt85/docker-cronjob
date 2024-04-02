<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceDepartment;
use App\Models\TenantsPaymentTransaction;
use App\Models\CollectorStatement;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;

class CashBookReportController extends Controller
{
    public function index (Request $request){

        $fd = FinanceDepartment::finance_department_by_district(financeDistrictId());

        $search_date_from = ($request->search_date_from) ? $request->search_date_from : null;
        $search_date_to = ($request->search_date_to) ? $request->search_date_to : null;
        //$date_from = ($search_date_from) ? $search_date_from : currentDateDb();
        //$date_to = ($search_date_to) ? $search_date_to   : currentDateDb();
        $search_payment_method = ($request->search_payment_method) ? ($request->search_payment_method) : null;
        $paymentMethodAll = PaymentMethod::getPaymentMethod();

        $dataReport = TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->join('collector_statement as cs','cs.id','=','tpt.collector_statement_id')
            ->join('transaction_status as ts','ts.id','=','cs.transaction_status_id')
            ->leftJoin('payment_method as pc','cs.payment_method_id','=','pc.id')
            ->select('tpt.action_on AS payment_date','pc.payment_method','tpt.payment_receipt_no','cs.collector_statement_no','cs.receipt_no','cs.receipt_date','tpt.total_payment AS amount','cs.bank_slip_date','ts.status')
            ->where(['tpt.data_status'=>1, 'cs.data_status'=>1,'ts.data_status'=>1]);

        //if($district_id) $dataReport = $dataReport->where('cs.district_id', $district_id);

        //--------------------Searching--------------------
        if($search_date_from) $dataReport = $dataReport->whereDate('payment_date','>=' , convertDatepickerDb($search_date_from));
        if($search_date_to) $dataReport = $dataReport->whereDate('payment_date', '<=' , convertDatepickerDb($search_date_to));
        if($search_payment_method) $dataReport = $dataReport->where('cs.payment_method_id', $search_payment_method);
        //--------------------Searching--------------------
        
        $dataReport = $dataReport->get();

        //DATA SUMMARY BY PAYMENT METHOD
        $dataSummary = $dataReport->groupBy('payment_method')->map(function ($row) {
            return $row->sum('amount');
        });

        //$dataReturn = compact('paymentMethodAll','dataReport', 'dataSummary', 'fd', 'search_payment_method', 'search_date_from','search_date_to');

        if($request->input('muat_turun_pdf'))
        {

            $dataReturn = compact('dataReport', 'dataSummary', 'fd', 'search_payment_method', 'search_date_from','search_date_to');
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
           //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));

            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Buku_Tunai_'.date("dmY-His").'.pdf');

        }else{
            $dataReturn = compact('paymentMethodAll','dataReport', 'search_payment_method', 'search_date_from','search_date_to');
            return view(getFolderPath().'.index', $dataReturn);
        }
    }
}
