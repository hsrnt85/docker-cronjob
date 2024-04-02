<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceDepartment;
use App\Models\TenantsPaymentTransaction;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        //$district_id = (!is_all_district()) ? districtId() : null;
        $search_date_from = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $search_date_to   = ($request->date_to)   ? convertDatepickerDb($request->date_to)   : null;

        $summaryData = TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->join('tenants_payment_transaction_vot_list as tptvl','tptvl.tenants_payment_transaction_id','=','tpt.id')
            ->select('tptvl.ispeks_account_code','tptvl.income_code','tptvl.income_code_description')
            ->selectRaw('SUM(tptvl.total_amount_after_adjustment) AS total_amount')
            ->where(['tpt.data_status'=>1,'tptvl.data_status'=>1])
            ->groupBy('tptvl.income_code');

        //if($district_id){ $summaryData = $summaryData->where('tpt.district_id', $district_id);  }
        if($search_date_from){ $summaryData = $summaryData->where('tpt.payment_date','>=' , $search_date_from); }
        if($search_date_to  ){ $summaryData = $summaryData->where('tpt.payment_date','<=' , $search_date_to); }

        $summaryData = $summaryData->get();

        if($request->input('muat_turun_pdf'))
        {
            $fd = FinanceDepartment::finance_department_by_district(1);
            $date_title = "DARI ".convertDateSys($search_date_from)." HINGGA ". convertDateSys($search_date_to);

            $dataReturn =  compact('summaryData','fd','date_title');
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact( 'totalPages')));

            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan_Ringkasan_Terimaan_Hasil_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index',  [
                'search_date_from' => convertDateSys($search_date_from),
                'search_date_to'   => convertDateSys($search_date_to),
                'summaryData' => $summaryData
            ]);

        }


    }
}
