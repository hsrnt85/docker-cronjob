<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlacklistPenalty;
use App\Models\BlacklistPenaltyRate;
use App\Models\Tenant;
use App\Models\QuartersCategory;
use App\Models\Year;
use App\Models\Month;
use Barryvdh\DomPDF\Facade\Pdf;

class IndividualBlacklistPenaltyReportController extends Controller
{
    public function index(Request $request)
    {
        $district_id = (!is_all_district()) ? districtId() : null;
        $blacklistPenaltyAll = [];  $blacklistPenaltyFirst = ""; $monthName = "";

        //select option
        $selectedYear  = ($request->year) ? $request->year : "";
        $selectedMonth = ($request->month) ? $request->month : "";
        $searchNewIc   = $request->new_ic ?? null;

        // Get list of quarters categories
        $quartersCatsAll = QuartersCategory::where('data_status', 1)
        ->whereHas('allTenants', function ($tenantSubQ) {
            $tenantSubQ->blacklisted();
        })
        ->get();
        $yearAll = Year::get_year();
        $monthAll = Month::get_month();

        if ($selectedYear) {
            $blacklistPenaltyAll = BlacklistPenalty::where('data_status', 1)
                ->whereYear('penalty_date', $selectedYear);

            if($selectedMonth){
                $blacklistPenaltyAll = $blacklistPenaltyAll->whereMonth('penalty_date', $selectedMonth);
                $monthName = Month::find($selectedMonth)->name ?? '';
            }

            if ($district_id) {
                $blacklistPenaltyAll = $blacklistPenaltyAll->whereHas('tenant', function ($tenantSubQ) use ($district_id) {
                    $tenantSubQ->whereHas('quarters_category', function ($quartersCatSubQ) use ($district_id) {
                        $quartersCatSubQ->where('district_id', $district_id);
                    });
                });
            }

            if ($searchNewIc) {
                $blacklistPenaltyAll = $blacklistPenaltyAll->whereHas('tenant', function ($tenantSubQ) use ($searchNewIc) {
                    $tenantSubQ->where('new_ic', $searchNewIc);
                });
            }
            $blacklistPenaltyAll = $blacklistPenaltyAll->get();
            $blacklistPenaltyFirst = $blacklistPenaltyAll->first();

        }

        $tenantByIc = Tenant::where(['new_ic' => $searchNewIc, 'data_status' => 1])->first();

        $dataReturn = compact( 'selectedYear', 'selectedMonth', 'searchNewIc', 'tenantByIc', 'quartersCatsAll', 'yearAll', 'monthAll', 'monthName','blacklistPenaltyAll','blacklistPenaltyFirst');

        if($request->input('muat_turun_pdf'))
        {
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath().'.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan_Denda_Hilang_Kelayakan_Individu_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index' , $dataReturn);
        }
    }

    public function ajaxCheckTenantIC(Request $request)
    {
        try {

            $new_ic = $request->new_ic;      //$q_cat_id = $request->quarters_cat;

            $currentTenant = Tenant::getCurrentTenantsByCategoryandIc($new_ic, "");

            return response()->json([   'tenant' => $currentTenant,
                                    ], 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

}
