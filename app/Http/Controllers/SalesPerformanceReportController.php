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

class SalesPerformanceReportController extends Controller
{
    public function index(Request $request)
    {

        $search_year  = ($request->carian_tahun) ? $request->carian_tahun  : null;
        $search_month = ($request->carian_bulan) ? $request->carian_bulan : null;

        $yearAll = Year::get_year();
        $monthAll = Month::get_month();
        $accountTypeAll = AccountType::getAccountTypeAll();

        $year_prev = ($request->carian_tahun) ? $search_year-1 : "";
        $till_date = ($request->carian_tahun) ? getEndDateOfMonth($search_year.'-'.$search_month.'-'.'01') : "";
        $till_date_name = ($request->carian_tahun) ? getEndDayOfMonth($search_year.'-'.$search_month.'-'.'01')." ".getMonthName($search_month)." ".$search_year : "";
        
        $dataReport = [];

        if($search_year && $search_month){

            foreach($accountTypeAll as $accountType){

                $account_type_id = $accountType->id;
                $account_type = $accountType->account_type;
                //------------------------------------------------------------------------------------------------------------------------------------------------------
                //DATA SALE - LAST YEAR
                //------------------------------------------------------------------------------------------------------------------------------------------------------ 

                $total_last_year = $this->getPayment($year_prev, $account_type_id,"");

                //DATA REPORT
                $dataReport[$account_type]['total_last_year'] = $total_last_year;
                $total_estimation = 0;
                $total_current = 0;
                //------------------------------------------------------------------------------------------------------------------------------------------------------
                //DATA ESTIMATION - CURRENT YEAR
                //------------------------------------------------------------------------------------------------------------------------------------------------------
                
                if($account_type_id == 2){
                    $total_estimation = $this->getPenalty($till_date);
                }else{
                    foreach($monthAll as $month){
                        $till_date_temp = getEndDateOfMonth($search_year.'-'.$month->id.'-'.'01');
                        $total_estimation += $this->getTenants($till_date_temp, $account_type_id); 
                    }
                }
              
                //------------------------------------------------------------------------------------------------------------------------------------------------------
                //DATA SALES - CURRENT YEAR
                //------------------------------------------------------------------------------------------------------------------------------------------------------
                
                $total_current = $this->getPayment($till_date, $account_type_id, "C");

                //------------------------------------------------------------------------------------------------------------------------------------------------------ 
                
                //DATA REPORT
                $dataReport[$account_type]['total_estimation'] = $total_estimation;
                $dataReport[$account_type]['total_current'] = $total_current;
    
            }
            //dd($dataReport);
        }

        if ($request->input('muat_turun_pdf')) {
            $fd = FinanceDepartment::finance_department_by_district(financeDistrictId()); 
            $month_name = strtoupper(getMonthName($search_month));
        
            $dataReturn = compact('fd', 'search_year', 'month_name', 'year_prev', 'till_date_name', 'dataReport');
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------
        
            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact( 'totalPages')));
            $pdf->setPaper('A4', 'landscape'); 
            return $pdf->stream('Laporan_Prestasi_Terimaan_Hasil_' . date("dmY-His") . '.pdf');

        } else {

            $dataReturn = compact('yearAll', 'monthAll', 'search_year', 'search_month', 'year_prev', 'till_date_name', 'dataReport');
        
            $pdf = PDF::loadview(getFolderPath() . '.index', $dataReturn);
        
            $currentPage = 1; // Set the actual current page number
            $totalPages = $pdf->getDomPDF()->getCanvas()->get_page_count();
        
            return view(getFolderPath() . '.index', array_merge($dataReturn, compact( 'totalPages')));
        }
        

    }

    function getPenalty($date){

        //CHECK PENALTY 
        $dataSumTenantsPenalty = TenantsPenalty::where('penalty_date', '<=', $date)->where('data_status', 1)->sum('penalty_amount');
        $damage_penalty_amount = $dataSumTenantsPenalty ?? 0;

        //CHECK BLACKLIST PENALTY 
        $dataSumTenantsBlacklistPenalty = TenantsBlacklistPenalty::where('penalty_date', '<=', $date)->where('data_status', 1)->sum('penalty_amount');
        $blacklist_penalty_amount = $dataSumTenantsBlacklistPenalty ?? 0;
        $total_penalty = $damage_penalty_amount + $blacklist_penalty_amount;

        return $total_penalty;

    }

    function getTenants($date, $account_type_id){
        
        $col_name = ($account_type_id == 1) ? 'rental_fee' : 'maintenance_fee';

        $dataTenants = Tenant::whereRaw('(leave_date is null OR leave_date >= "'.$date.'")')
            ->where('quarters_acceptance_date','<=', "'.$date.'")
            ->sum($col_name);
     
        return $dataTenants ?? 0;

    }

    function getPayment($param, $account_type_id, $flag_current){
        
        $dataCurrentSales = TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->join('tenants_payment_transaction_vot_list as tptvl','tptvl.tenants_payment_transaction_id','=','tpt.id')
            ->where(['tpt.data_status'=>1,'tptvl.data_status'=>1])->where('tptvl.account_type_id', $account_type_id);
            if($flag_current){
                $dataCurrentSales = $dataCurrentSales->where('tpt.payment_date','<=', $param);
            }else{
                $dataCurrentSales = $dataCurrentSales->whereYear('tpt.payment_date', $param);
            } 
            $dataCurrentSales = $dataCurrentSales->sum('tptvl.total_amount_after_adjustment');

        return $dataCurrentSales ?? 0;

    }
    
}
