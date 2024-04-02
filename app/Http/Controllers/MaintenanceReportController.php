<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceStatus;
use App\Models\MaintenanceTransaction;
use App\Models\QuartersCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MaintenanceReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedDateFrom = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $selectedDateTo = ($request->date_to) ? convertDatepickerDb($request->date_to) : null;
        $selectedQuartersCat = $request->quarters_cat ?? null;
        $selectedStatus = $request->status ?? null;

        // Get list of quarters categories
        $quartersCatsAll = QuartersCategory::where('data_status', 1)
            ->whereHas('quarters', function ($quartersSubq) {
                $quartersSubq->whereHas('complaints', function ($complaintSubQ) {
                    $complaintSubQ->whereHas('maintenance_transaction')
                        ->where('complaint_type', 1)
                        ->where('data_status', 1);
                });
            })
            ->get();

        $statusAll = MaintenanceStatus::where('data_status', 1)->get();

        // Get maintenance
        if ($selectedDateFrom && $selectedDateTo) {
            $maintenanceAll = MaintenanceTransaction::where('data_status', 1)
                ->where('maintenance_date', '>=', $selectedDateFrom)
                ->where('maintenance_date', '<=', $selectedDateTo);

            if ($selectedQuartersCat) {
                $maintenanceAll->whereHas('complaint.quarters', function ($quartersSubq) use ($selectedQuartersCat) {
                    $quartersSubq->where('quarters_cat_id', $selectedQuartersCat);
                });
            }

            if ($selectedStatus) {
                $maintenanceAll->where('maintenance_status_id', $selectedStatus);
            }

            $maintenanceAll = $maintenanceAll->with(['complaint', 'complaint.quarters', 'complaint.user', 'status'])->get();
        }

        $quartersCatsAll = $quartersCatsAll ?? null;
        $maintenanceAll = $maintenanceAll ?? null;
        
        if ($request->muat_turun_pdf) {

            $selectedQuartersCatPdf = QuartersCategory::find($selectedQuartersCat);

            $dataReturn = compact('selectedDateFrom','selectedDateTo','selectedQuartersCat','selectedStatus','statusAll','quartersCatsAll','maintenanceAll','selectedQuartersCatPdf');
                 
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));

            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Penyelenggaraan_' . date("dmY-His") . '.pdf');

        }else{

            $dataReturn = compact('selectedDateFrom','selectedDateTo','selectedQuartersCat','selectedStatus','statusAll','quartersCatsAll','maintenanceAll');
              
            return view(getFolderPath() . '.index', $dataReturn);
        }

       
    }
}
