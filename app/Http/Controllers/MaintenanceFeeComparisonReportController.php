<?php

namespace App\Http\Controllers;
use App\Models\Year;
use App\Models\Month;
use App\Models\FinanceDepartment;
use App\Models\Quarters;
use App\Models\Tenant;
use App\Models\TenantsPaymentNotice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MaintenanceFeeComparisonReportController extends Controller
{
    public function index(Request $request)
    {
        $district_id  = (is_all_district()) ? districtId()  : null;
        $search_year  = ($request->search_year) ? $request->search_year : null;
        $search_month = ($request->search_month) ? $request->search_month : null;
        $search_date = ($request->search_month) ? getEndDateOfMonth($search_year.'-'.$search_month.'-'.'01') : null;

        $year = Year::get_year();
        $month = Month::get_month();
        $till_date = ($request->search_year) ? getEndDateOfMonth($search_year.'-'.$search_month.'-'.'01') : "";
        $monthEstimationAll = Month::get_month(1, $search_month);
        $dataReport = null;

        if($search_year && $search_month){

            $dataReport =  Quarters::from('quarters as q')
                ->leftJoin('tenants as t', function ($join) use($till_date){
                    $join->on('t.quarters_id', '=', 'q.id')
                        ->whereRaw('(t.leave_date is null OR t.leave_date >= "'.$till_date.'")')
                        ->whereRaw('(t.quarters_acceptance_date is null OR t.quarters_acceptance_date <= "'.$till_date.'")');
                })
                ->leftJoin('tenants_payment_notice as tpn', function($join){
                    $join->on('tpn.no_ic', '=', 't.new_ic')
                        ->where(['tpn.data_status' => 1, 'tpn.payment_status' => 2 ]);
                })
                ->leftJoin('tenants_payment_transaction as tptj', function($join){//JOHORPAY
                    $join->on('tptj.tenants_payment_notice_id', '=', 'tpn.id')
                    ->where(['tptj.data_status' => 1, 'tptj.payment_category_id'=>3]);
                })
                ->leftJoin('tenants_payment_transaction AS tpt', function($join){//IGFMAS / ISPEKS
                    $join->on('tpt.id', '=', 'tpn.tenants_payment_transaction_id')
                        ->where(['tpt.data_status' => 1])
                        ->whereIn('tpt.payment_category_id', [1,2]);
                })
                ->leftJoin('tenants_payment_transaction_vot_list as tptvl', 'tptvl.tenants_payment_transaction_id', '=', 'tpt.id')
                ->where(['q.data_status' => 1, 'q.quarters_condition_id'=> 1])->where('q.maintenance_fee','>',0)
                
                ->select('q.unit_no','q.address_1', 'q.address_2','q.address_3', 'q.maintenance_fee','t.name', 't.new_ic','t.maintenance_fee', 'tptvl.amount')
                ->selectRaw('(CASE WHEN t.maintenance_fee IS NOT NULL THEN t.maintenance_fee ELSE q.maintenance_fee END) AS maintenance_fee');

                $dataReport = $dataReport->get();

        }
        
        if($request->input('muat_turun_pdf'))
        {
            $fd = FinanceDepartment::finance_department_by_district(districtId());
            $month_name = strtoupper(getMonthName($search_month));
            $dataReturn = compact('dataReport', 'fd','search_year','month_name'); 

            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));

            // $pdf = PDF::loadview(getFolderPath().'.cetak-pdf', $dataReturn);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Perbandingan_Yuran_Penyelenggaraan_'.date("dmY-His").'.pdf');

            
        }else{
            $dataReturn = compact('dataReport', 'search_year','search_month','year','month');
            return view(getFolderPath().'.index', $dataReturn);
        }

    }

    function getTenants($to_date, $account_type_id){
        
        $col_name = ($account_type_id == 1) ? 'rental_fee' : 'maintenance_fee';

        $dataTenants = Tenant::whereRaw('(leave_date is null OR leave_date >= "'.$to_date.'")')
            ->where('quarters_acceptance_date','<=', $to_date)//->dd();
            ->sum($col_name);

        return $dataTenants ?? 0;

    }

}
