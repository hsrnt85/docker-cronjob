<?php

namespace App\Http\Controllers;

use App\Models\QuartersCategory;
use App\Models\TenantsPenalty;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Psy\Command\WhereamiCommand;

class TenantsPenaltyReportController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->date_from);

        $selectedDateFrom = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $selectedDateTo = ($request->date_to) ? convertDatepickerDb($request->date_to) : null;
        $selectedQuartersCat = $request->quarters_cat ?? null;

        // get list of quarters categories
        $quartersCatsAll = QuartersCategory::where('data_status', 1)
            ->whereHas('allTenants', function ($q) {
                $q->whereHas('tenant_penalties', function ($q) {
                    $q->where('data_status', 1);
                });
            })
            ->get();

        // Get penalty records based on filters
        if ($selectedDateFrom && $selectedDateTo) {
            $tenantPenalties = TenantsPenalty::with(['tenants', 'tenants.quarters_category'])
                ->whereDate('penalty_date', '>=', $selectedDateFrom)
                ->whereDate('penalty_date', '<=', $selectedDateTo);

            if ($selectedQuartersCat) {
                $tenantPenalties->whereHas('tenants', function ($tSubQ) use ($selectedQuartersCat) {
                    $tSubQ->where('quarters_category_id', $selectedQuartersCat);
                });
            }

            $tenantPenalties = $tenantPenalties->get();
        }


        $print_pdf = $request->input('muat_turun_pdf');

        if ($request->muat_turun_pdf) {

            $selectedQuartersCatPdf = QuartersCategory::find($selectedQuartersCat);

            $dataReturn = [
                'selectedDateFrom' => $selectedDateFrom,
                'selectedDateTo' => $selectedDateTo,
                'selectedQuartersCat' => $selectedQuartersCat,
                'quartersCatsAll' => $quartersCatsAll,
                'tenantPenalties' => $tenantPenalties ?? null,
                'selectedQuartersCatPdf' => $selectedQuartersCatPdf
            ];

            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));

            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Denda_Kerosakan_' . date("dmY-His") . '.pdf');
        }

        return view(getFolderPath() . '.index', [
            'selectedDateFrom' => $selectedDateFrom,
            'selectedDateTo' => $selectedDateTo,
            'selectedQuartersCat' => $selectedQuartersCat,
            'quartersCatsAll' => $quartersCatsAll,
            'tenantPenalties' => $tenantPenalties ?? null
        ]);
    }
}
