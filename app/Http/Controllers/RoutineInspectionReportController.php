<?php

namespace App\Http\Controllers;

use App\Models\InspectionStatus;
use App\Models\QuartersCategory;
use App\Models\RoutineInspectionTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\RoutineInspectionTransactionAttachment;


class RoutineInspectionReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedDateFrom = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $selectedDateTo = ($request->date_to) ? convertDatepickerDb($request->date_to) : null;
        $selectedQuartersCat = $request->quarters_cat ?? null;
        $selectedStatus = $request->status ?? null;

        $inspectionStatusAll = InspectionStatus::where('data_status', 1)->get();

        // Get list of quarters categories
        $quartersCatsAll = QuartersCategory::where('data_status', 1)
            ->whereHas('routine_inspection', function ($riSubQ) {
                $riSubQ->where('data_status', 1);
            })
            ->get();

        if ($selectedDateFrom && $selectedDateTo) {

            $inspectionTransactionAll = RoutineInspectionTransaction::where('data_status', 1)
                ->whereHas('routineInspection', function ($inspectionSubQ) use ($selectedDateFrom, $selectedDateTo) {
                    $inspectionSubQ->where('inspection_date', '>=', $selectedDateFrom)
                        ->where('inspection_date', '<=', $selectedDateTo);
                });

            if ($selectedQuartersCat) {
                $inspectionTransactionAll = $inspectionTransactionAll->whereHas('routineInspection', function ($inspectionSubQ) use ($selectedQuartersCat) {
                    $inspectionSubQ->where('quarters_category_id', $selectedQuartersCat);
                });
            }

            if ($selectedStatus) {
                $inspectionTransactionAll = $inspectionTransactionAll->where('inspection_status_id', $selectedStatus);
            }

            $inspectionTransactionAll = $inspectionTransactionAll->with(['routineInspection', 'officer', 'inspectionStatus'])->get();
        }

        if ($request->muat_turun_pdf) {

            $selectedQuartersCatPdf = QuartersCategory::find($selectedQuartersCat);
            $dataReturn = [   
                'selectedDateFrom' => $selectedDateFrom,
                'selectedDateTo' => $selectedDateTo,
                'selectedQuartersCat' => $selectedQuartersCat,
                'selectedStatus' => $selectedStatus,
                'inspectionStatusAll' => $inspectionStatusAll,
                'quartersCatsAll' => $quartersCatsAll ?? null,
                'inspectionTransactionAll' => $inspectionTransactionAll ?? null,
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
            
            $inspectionTransactionAll->load('attachments');

            return $pdf->stream('Laporan_Pemantauan_Berkala_' . date("dmY-His") . '.pdf');
        }else{

            return view(getFolderPath() . '.index', [
                'selectedDateFrom' => $selectedDateFrom,
                'selectedDateTo' => $selectedDateTo,
                'selectedQuartersCat' => $selectedQuartersCat,
                'selectedStatus' => $selectedStatus,
                'inspectionStatusAll' => $inspectionStatusAll,
                'quartersCatsAll' => $quartersCatsAll ?? null,
                'inspectionTransactionAll' => $inspectionTransactionAll ?? null
            ]);
        }
    }
}
