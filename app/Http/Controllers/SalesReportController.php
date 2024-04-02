<?php

namespace App\Http\Controllers;
use App\Models\FinanceDepartment;
use App\Models\IncomeAccountCode;
use App\Models\TenantsPaymentTransaction;
use App\Models\Year;
use App\Models\Month;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        //Filter by user district id
        $district_id = (!is_all_district()) ? districtId() : null;
        $receiptType = IncomeAccountCode::get_jenis_terimaan();
        $outstandingType = IncomeAccountCode::get_jenis_tunggakan();

        $recordList = TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->join('tenants_payment_transaction_vot_list as tptvl','tptvl.tenants_payment_transaction_id','=','tpt.id')
            ->leftJoin('payment_category as pc','tpt.payment_category_id','=','pc.id')
            ->select('tpt.tenants_id', 'tpt.payment_category_id','tpt.payment_receipt_no','tpt.action_on AS payment_date' , DB::raw('TIME_FORMAT(tpt.action_on, "%H:%i %p") as payment_time') ,'tpt.payment_description','pc.payer_name','pc.payment_category','tptvl.total_amount_after_adjustment AS amount', 'tptvl.income_code')
            ->where(['tpt.data_status'=>1,'tptvl.data_status'=>1]);

        //--------------------request input----------------------------
        $search_date_from = ($request->date_from) ? convertDatePickerDb($request->date_from) : null;
        $search_date_to = ($request->date_to) ? convertDatePickerDb($request->date_to) : null;
        $search_account = ($request->search_account) ? $request->search_account : null;
        $search_outstanding = ($request->search_outstanding) ? $request->search_outstanding : null;

        //--------------------searching----------------------------
        if($search_date_from) $recordList = $recordList->where('tpt.payment_date', '>=', $search_date_from);
        if($search_date_to) $recordList = $recordList->where('tpt.payment_date','<=', $search_date_to);
        if($search_account) $recordList = $recordList -> where('tptvl.income_code', 'LIKE', $search_account. '%');
        if($search_outstanding) $recordList = $recordList -> where ('tptvl.flag_outstanding', 'LIKE', '%' .$search_outstanding . '%');

        $recordList = $recordList->get();

        //Print based on search
        if($request->input('muat_turun_pdf'))
        {
            $selectedTerimaan = '';
            $selectedReceiptType = '';
            if (!empty($search_account)) {
                $selectedTerimaan = IncomeAccountCode::where('ispeks_account_code', $search_account)->first()->ispeks_account_description;
                $selectedReceiptType = $receiptType->where('ispeks_account_code', $search_account)->pluck('ispeks_account_description')->first();
            }

            $selectedTunggakan = '';
            if (!empty($search_outstanding)) {
                $selectedTunggakan = $outstandingType->where('flag_outstanding', $search_outstanding)->pluck('outstanding_type')->first();
            }

            $fd = FinanceDepartment::finance_department_by_district(financeDistrictId());

            $dataReturn =  compact('receiptType','outstandingType','search_date_to','search_date_from','search_account','search_outstanding','selectedTerimaan','selectedTunggakan','selectedReceiptType','recordList', 'fd');
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact( 'totalPages')));
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Terimaan_Hasil_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index', compact('receiptType','outstandingType','search_date_to','search_date_from','search_account','search_outstanding','recordList'));

        }

    }
}
