<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectorStatement;
use App\Models\TransactionStatus;
use App\Models\FinanceDepartment;
use Barryvdh\DomPDF\Facade\Pdf;


class CollectorStatementReportController extends Controller
{
    public function index(Request $request)
    {
        //FILTER BY USER DISTRICT ID
        //$district_id = (!is_all_district()) ?  districtId() : null;

        //---------------------search section-------------------------------------
        $search_collector_statement_no = ($request->search_collector_statement_no) ? $request->search_collector_statement_no : null;
        $search_date_from = ($request->search_date_from) ? $request->search_date_from : null;
        $search_date_to = ($request->search_date_to) ? $request->search_date_to : null;
        $search_date_from_db = convertDatepickerDb($search_date_from);
        $search_date_to_db = convertDatepickerDb($search_date_to);
        

        //--------------------List of Penyata Pemungut-------------------------------------
        $collectorStatementAll = CollectorStatement::where('data_status',1)->get();
        //--------------------List of Penyata Pemungut-------------------------------------

        $collectorStatementList = CollectorStatement::from('collector_statement as cs')
            ->join('collector_statement_vot_list as csvl','cs.id','=','csvl.collector_statement_id')
            ->join('income_account_code as iac','iac.id','=','csvl.income_account_code_id')
            ->join('district as d','d.id','=','cs.district_id',)
            ->join('finance_department as fd','d.id','=','fd.district_id',)
            ->join('transaction_status as ts','ts.id','=','cs.transaction_status_id')
            ->select('cs.collector_statement_no','cs.transaction_status_id','cs.collector_statement_date','cs.collector_statement_date_from','cs.collector_statement_date_to',
                    'csvl.total_amount','iac.general_income_code','iac.income_code','ts.status_code','fd.department_code','fd.ptj_code')
            ->where(['cs.data_status'=>1, 'csvl.data_status'=>1,'iac.data_status'=>1,'ts.data_status'=>1,'d.data_status'=>1,'fd.data_status'=>1]);

        //if($district_id) $collectorStatementList = $collectorStatementList->where('cs.district_id', $district_id);

        //--------------------Searching--------------------
        if($search_collector_statement_no) $collectorStatementList = $collectorStatementList->where('cs.collector_statement_no', 'LIKE', '%' . $search_collector_statement_no . '%');
        if($search_date_from) $collectorStatementList = $collectorStatementList->where('cs.collector_statement_date','>=' , $search_date_from_db);
        if($search_date_to) $collectorStatementList = $collectorStatementList->where('cs.collector_statement_date', '<=' , $search_date_to_db);

        $collectorStatementListAll = $collectorStatementList->get();

        //Print based on search
        if($request->input('muat_turun_pdf'))
        {
            $fd = FinanceDepartment::finance_department_by_district(financeDistrictId()); 
            $transactionStatusList = TransactionStatus::where('data_status',1)->get();

            $summaryStatusPenyataPemungutArr = [];
            $total_by_status = 0;
            $bil_by_status = 0;
            foreach($transactionStatusList as $i => $data){
                $transaction_status_id = $data->id;
                $total_by_status = $collectorStatementListAll->where('transaction_status_id', $transaction_status_id)->sum('total_amount');
                if(!empty($total_by_status)){
                    $summaryStatusPenyataPemungutArr[$i]['bil'] = ++$bil_by_status;
                    $summaryStatusPenyataPemungutArr[$i]['transaction_status_id'] = $data->status_code;
                    $summaryStatusPenyataPemungutArr[$i]['status'] = $data->status;
                    $summaryStatusPenyataPemungutArr[$i]['total'] = $total_by_status;
                }
            }

            $dataReturn = compact('fd','search_date_from_db','search_date_to_db','collectorStatementListAll','summaryStatusPenyataPemungutArr');
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------
   
            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Penyata_Pemungut_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index',
                compact('search_date_from','search_date_to','search_collector_statement_no','collectorStatementListAll'
            ));

         }
    }
}
