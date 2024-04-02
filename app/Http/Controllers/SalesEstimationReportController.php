<?php

namespace App\Http\Controllers;
use App\Models\Year;
use App\Models\Month;
use App\Models\AccountType;
use App\Models\FinanceDepartment;
use App\Models\TenantsPaymentTransaction;
use App\Models\Tenant;
use App\Models\TenantsPenalty;
use App\Models\TenantsBlacklistPenalty;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalesEstimationReportController extends Controller
{
    public function index(Request $request)
    {
        //$district_id  = (is_all_district()) ? districtId()  : null;
        $search_year  = ($request->carian_tahun)  ? $request->carian_tahun  : null;
        $search_month = ($request->carian_bulan) ? $request->carian_bulan : null;

        $yearAll = Year::get_year();
        $monthAll = Month::get_month();
        $accountTypeAll = AccountType::getAccountTypeAll();

        $year_prev = ($request->carian_tahun) ? $search_year-1 : "";
        $till_date = ($request->carian_tahun) ? getEndDateOfMonth($search_year.'-'.$search_month.'-'.'01') : "";
        $till_date_name = ($request->carian_tahun) ? getEndDayOfMonth($search_year.'-'.$search_month.'-'.'01')." ".getMonthName($search_month)." ".$search_year : "";
        
        $month_from_estimation = ($request->carian_tahun) ? (($search_month==12) ? $search_month : $search_month+1) : 0;//dd($month_from_estimation);
        $month_to_estimation = ($request->carian_tahun) ? 12 : 0;

        $date_from_estimation = ($request->carian_tahun) ? $search_year.'-'.$month_from_estimation.'-'.'01' : "";
        $date_from_estimation_name = ($request->carian_tahun) ? "01"." ".getMonthName($month_from_estimation)." ".$search_year : "";
        
        $date_to_estimation = ($request->carian_tahun) ? getEndDateOfMonth($search_year.'-'.$month_to_estimation.'-'.'01') : "";
        $date_to_estimation_name = ($request->carian_tahun) ? getEndDayOfMonth($search_year.'-'.$month_to_estimation.'-'.'01')." ".getMonthName($month_to_estimation)." ".$search_year : "";
        
        $date_from_year = $search_year.'-01-01';
        $date_to_year = $date_to_estimation;
        $monthEstimationAll = Month::get_month($month_from_estimation, 12);

        $dataReport = [];

        if($search_year && $search_month){

            foreach($accountTypeAll as $accountType){

                $account_type_id = $accountType->id;
                $account_type = $accountType->account_type;
                $total_estimation_year = 0;
                $total_estimation_balance = 0;

                //------------------------------------------------------------------------------------------------------------------------------------------------------
                //DATA ESTIMATION - CURRENT YEAR
                //------------------------------------------------------------------------------------------------------------------------------------------------------
                
                if($account_type_id == 2){
                    $total_estimation_year = $this->getPenalty($date_from_year, $date_to_year);
                }else{
                    foreach($monthAll as $month){
                        $date_to_year_temp = getEndDateOfMonth($search_year.'-'.$month->id.'-'.'01');
                        $total_estimation_year += $this->getTenants($date_to_year_temp, $account_type_id); 
                    }
                }

                //------------------------------------------------------------------------------------------------------------------------------------------------------
                //DATA SALES - CURRENT YEAR
                //------------------------------------------------------------------------------------------------------------------------------------------------------

                $total_till_date = $this->getPayment($till_date, $account_type_id);

                //------------------------------------------------------------------------------------------------------------------------------------------------------
                //DATA ESTIMATION - BALANCE MONTH
                //------------------------------------------------------------------------------------------------------------------------------------------------------
               
                if($account_type_id == 2){
                    $total_estimation_balance = $this->getPenalty($date_from_estimation, $date_to_estimation);
                }else{
                    foreach($monthEstimationAll as $month){
                        $date_from_estimation_temp = $search_year.'-'.$month->id.'-'.'01';
                        $total_estimation_balance += $this->getEstimationTenants($date_from_estimation_temp, $date_to_estimation, $account_type_id); 
                    }
                }

                //------------------------------------------------------------------------------------------------------------------------------------------------------ 
                
                //DATA REPORT
                $dataReport[$account_type]['total_estimation_year'] = $total_estimation_year;
                $dataReport[$account_type]['total_till_date'] = $total_till_date;
                $dataReport[$account_type]['total_estimation_balance'] = $total_estimation_balance;
    
            }
            //dd($dataReport);
        }

        // if($request->input('muat_turun_pdf'))
        // {
        //     $fd = FinanceDepartment::finance_department_by_district(financeDistrictId()); 
        //     $month_name = strtoupper(getMonthName($search_month));
           
        //     $dataReturn = compact( 'fd', 'search_year', 'month_name', 'year_prev', 'till_date_name', 'date_from_estimation_name', 'date_to_estimation_name', 'dataReport' );

        //     $pdf = PDF::loadview(getFolderPath().'.cetak-pdf', $dataReturn);
        //     $pdf->setPaper('A4', 'landscape'); 
        //     return $pdf->stream('Laporan_Prestasi_Terimaan_Hasil_'.date("dmY-His").'.pdf');

        // }else{

        //     $dataReturn = compact( 'yearAll', 'monthAll', 'search_year', 'search_month', 'year_prev', 'till_date_name', 'date_from_estimation_name', 'date_to_estimation_name', 'dataReport' );

        //     return view(getFolderPath().'.index',  $dataReturn);
        // }


        if ($request->input('muat_turun_pdf')) {
            $fd = FinanceDepartment::finance_department_by_district(financeDistrictId()); 
            $month_name = strtoupper(getMonthName($search_month));
        
            $dataReturn = compact('fd', 'search_year', 'month_name', 'year_prev', 'till_date_name', 'date_from_estimation_name', 'date_to_estimation_name', 'dataReport');
        
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------
        
            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape'); 
            return $pdf->stream('Laporan_Prestasi_Terimaan_Hasil_' . date("dmY-His") . '.pdf');
        } else {
            $dataReturn = compact('yearAll', 'monthAll', 'search_year', 'search_month', 'year_prev', 'till_date_name', 'date_from_estimation_name', 'date_to_estimation_name', 'dataReport');
        
            $pdf = PDF::loadview(getFolderPath() . '.index', $dataReturn);
            
            $currentPage = 1; 
            $totalPages = $pdf->getDomPDF()->getCanvas()->get_page_count();
        
            return view(getFolderPath() . '.index', array_merge($dataReturn, compact('totalPages')));
        }


    }

    function getPenalty($from_date, $to_date){

        //CHECK PENALTY 
        $dataSumTenantsPenalty = TenantsPenalty::whereBetween('penalty_date', [$from_date, $to_date])->where('data_status', 1)->sum('penalty_amount');
        $damage_penalty = $dataSumTenantsPenalty ?? 0;

        //CHECK BLACKLIST PENALTY 
        $dataSumTenantsBlacklistPenalty = TenantsBlacklistPenalty::whereBetween('penalty_date', [$from_date, $to_date])->where('data_status', 1)->sum('penalty_amount');
        $blacklist_penalty = $dataSumTenantsBlacklistPenalty ?? 0;

        $total_penalty = $damage_penalty + $blacklist_penalty;
        //dd($total_penalty);
        return $total_penalty;

    }

    function getTenants($to_date, $account_type_id){
        
        $col_name = ($account_type_id == 1) ? 'rental_fee' : 'maintenance_fee';

        $dataTenants = Tenant::whereRaw('(leave_date is null OR leave_date >= "'.$to_date.'")')
            ->where('quarters_acceptance_date','<=', $to_date)//->dd();
            ->sum($col_name);

        return $dataTenants ?? 0;

    }

    function getEstimationTenants($from_date, $to_date, $account_type_id){
        
        $col_name = ($account_type_id == 1) ? 'rental_fee' : 'maintenance_fee';

        $dataTenants = Tenant::whereRaw('quarters_acceptance_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"')
            ->whereRaw('(leave_date is null OR leave_date <= "'.$to_date.'")')
            ->sum($col_name);

        //dd($dataTenants);
        return $dataTenants ?? 0;

    }

    function getPayment($date, $account_type_id){
        
        $dataCurrentSales = TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->join('tenants_payment_transaction_vot_list as tptvl','tptvl.tenants_payment_transaction_id','=','tpt.id')
            ->where(['tpt.data_status'=>1,'tptvl.data_status'=>1])->where('tptvl.account_type_id', $account_type_id)
            ->where('tpt.payment_date','<=', $date)
            ->sum('tptvl.total_amount_after_adjustment');

        return $dataCurrentSales ?? 0;

    }
    
}
