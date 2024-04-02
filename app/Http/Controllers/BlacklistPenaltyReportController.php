<?php

namespace App\Http\Controllers;

use App\Models\BlacklistPenalty;
use App\Models\District;
use App\Models\Officer;
use App\Models\QuartersCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BlacklistPenaltyReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedDateFrom = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $selectedDateTo = ($request->date_to) ? convertDatepickerDb($request->date_to) : null;
        $selectedQuartersCat = $request->quarters_cat ?? null;
        $selectedOfficer = $request->officer ?? null;

        $district_id    = ((!is_all_district()) ? District::where('id', districtId())->first()->id : ($request->district_id)) ? $request->district_id : null;

        // Get list of quarters categories
        $quartersCatsAll = QuartersCategory::where('data_status', 1)
            ->whereHas('allTenants', function ($tenantSubQ) {
                $tenantSubQ->blacklisted();
            })
            ->get();

        $officerAll = ($district_id) ? Officer::getPegawaiPemantauanByDaerah($district_id) : Officer::getPegawaiPemantauan();

        if ($selectedDateFrom && $selectedDateTo) {
            $blacklistPenaltyAll = BlacklistPenalty::where('data_status', 1)
                ->whereBetween('penalty_date', [$selectedDateFrom, $selectedDateTo]);

            if ($district_id) {
                $blacklistPenaltyAll = $blacklistPenaltyAll->whereHas('tenant', function ($tenantSubQ) use ($district_id) {
                    $tenantSubQ->whereHas('quarters_category', function ($quartersCatSubQ) use ($district_id) {
                        $quartersCatSubQ->where('district_id', $district_id);
                    });
                });
            }

            if ($selectedQuartersCat) {
                $blacklistPenaltyAll = $blacklistPenaltyAll->whereHas('tenant', function ($tenantSubQ) use ($selectedQuartersCat) {
                    $tenantSubQ->where('quarters_category_id', $selectedQuartersCat);
                });
            }

            $blacklistPenaltyAll = $blacklistPenaltyAll->get();
        }

        if ($request->muat_turun_pdf) {

            $selectedQuartersCatPdf = QuartersCategory::find($selectedQuartersCat) ?? null;
            
            $dataReturn = [
                'selectedDateFrom' => $selectedDateFrom,
                'selectedDateTo' => $selectedDateTo,
                'selectedQuartersCat' => $selectedQuartersCat,
                'blacklistPenaltyAll' => $blacklistPenaltyAll ?? null,
                'quartersCatsAll' => $quartersCatsAll ?? null,
                'selectedQuartersCatPdf' => $selectedQuartersCatPdf
            ];

            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = Pdf::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));

            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Pemantauan_Berkala_' . date("dmY-His") . '.pdf');
        }

        return view(getFolderPath() . '.index', [
            'selectedDateFrom' => $selectedDateFrom,
            'selectedDateTo' => $selectedDateTo,
            'selectedQuartersCat' => $selectedQuartersCat,
            'selectedOfficer' => $selectedOfficer,
            'officerAll' => $officerAll ?? null,
            'quartersCatsAll' => $quartersCatsAll ?? null,
            'blacklistPenaltyAll' => $blacklistPenaltyAll ?? null
        ]);
    }
}
