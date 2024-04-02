<?php

namespace App\Http\Controllers;

use App\Models\AppointmentStatus;
use App\Models\ComplaintAppointment;
use App\Models\PositionGradeType;
use App\Models\QuartersCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ComplaintAppointmentReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedDateFrom = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $selectedDateTo = ($request->date_to) ? convertDatepickerDb($request->date_to) : null;
        $selectedQuartersCat = $request->quarters_cat ?? null;
        $selectedAppointmentStatus = $request->status ?? null;

        // get list of quarters categories
        $quartersCatsAll = QuartersCategory::where('data_status', 1)
            ->whereHas('quarters', function ($quartersSubq) {
                $quartersSubq->whereHas('complaints', function ($complaintSubQ) {
                    $complaintSubQ->whereHas('current_complaint_appointment')
                        ->where('complaint_type', 1)
                        ->where('data_status', 1);
                });
            })
            ->get();

        $appointmentStatusAll = AppointmentStatus::where('data_status', 1)->get();


        if ($selectedDateFrom && $selectedDateTo) {
            $appointments = ComplaintAppointment::with('complaint')
                ->with('complaint.quarters')
                ->with('complaint.user')
                ->with('complaint.user.user_info')
                ->where('data_status', 1)
                ->whereHas('complaint', function ($compSubQ) {
                    $compSubQ->where('complaint_type', 1); //1:Kerosakan 
                })
                ->where('appointment_date', '>=', $selectedDateFrom)
                ->where('appointment_date', '<=', $selectedDateTo);

            if ($selectedQuartersCat) {
                $appointments->whereHas('complaint.quarters', function ($quartersSubq) use ($selectedQuartersCat) {
                    $quartersSubq->where('quarters_cat_id', $selectedQuartersCat);
                });
            }

            if ($selectedAppointmentStatus) {
                $appointments->where('appointment_status_id', $selectedAppointmentStatus);
            }

            $appointments = $appointments->get();
        }


        if ($request->muat_turun_pdf) {

            $selectedQuartersCatPdf = QuartersCategory::find($selectedQuartersCat);

            $dataReturn = [   
                'selectedDateFrom' => $selectedDateFrom,
                'selectedDateTo' => $selectedDateTo,
                'selectedQuartersCat' => $selectedQuartersCat,
                'selectedAppointmentStatus' => $selectedAppointmentStatus,
                'appointmentStatusAll' => $appointmentStatusAll,
                'quartersCatsAll' => $quartersCatsAll ?? null,
                'appointments' => $appointments ?? null,
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
        }else{

            return view(getFolderPath() . '.index', [
                'selectedDateFrom' => $selectedDateFrom,
                'selectedDateTo' => $selectedDateTo,
                'selectedQuartersCat' => $selectedQuartersCat,
                'selectedAppointmentStatus' => $selectedAppointmentStatus,
                'appointmentStatusAll' => $appointmentStatusAll,
                'quartersCatsAll' => $quartersCatsAll ?? null,
                'appointments' => $appointments ?? null
            ]);
        }
    }
}
