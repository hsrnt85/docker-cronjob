<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\QuartersCategory;
use App\Http\Requests\RulesViolationComplaintReportRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DamageComplaintReportExport;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;

class DamageComplaintReportController extends Controller
{
    public function __construct()
    {
        set_time_limit(0);
    }

    public function damageComplaintList(Request $request)
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $carian_tarikh_aduan_dari = $request->carian_tarikh_aduan_dari;
        $carian_tarikh_aduan_hingga = $request->carian_tarikh_aduan_hingga;
        $carian_tarikh_aduan_dari_db = convertDatePickerDb($carian_tarikh_aduan_dari);
        $carian_tarikh_aduan_hingga_db = convertDatePickerDb($carian_tarikh_aduan_hingga);
        $carian_id_kategori = $request->carian_id_kategori;
        $carian_status_aduan = $request->status_aduan ?? "";

        $complaintStatusAll = ComplaintStatus::getComplaintStatus();

        $complaintList = Complaint::select('appointment.appointment_date' ,'complaint.*')
        ->leftJoin('complaint_appointment as appointment', function($q_app) { //temujanji
            $q_app->on('appointment.complaint_id','=','complaint.id')
                ->whereRaw('appointment.id IN (select MAX(appointment.id) from complaint_appointment where data_status = 1 group by appointment.complaint_id)')
                ->whereNotNull('appointment.appointment_status_id');
        })
        ->join('quarters as q', 'q.id', '=', 'complaint.quarters_id')
        ->where(['complaint.data_status' => 1, 'complaint.complaint_type' => 1])
        // ->groupby('complaint.id')
        ->orderBy('complaint.complaint_date' , 'desc');

        //FILTER BY OFFICER DISTRICT ID
        if($district_id) {
            $quarters_category = QuartersCategory::where('data_status', 1)->where('district_id', $district_id)->get();
        } else {
            $quarters_category = QuartersCategory::where('data_status', 1)->get();
        }

        //FILTER BY CARIAN
        if ($carian_tarikh_aduan_hingga_db) {
            $complaintList = $complaintList->whereBetween('complaint.complaint_date', [$carian_tarikh_aduan_dari_db, $carian_tarikh_aduan_hingga_db]);
        }
        if ($carian_id_kategori) {
            $complaintList = $complaintList->where('q.quarters_cat_id', $carian_id_kategori);
            $quarters_categorydata = QuartersCategory::select('name')->where('id', $carian_id_kategori)->first();
            $quarters_category_name = $quarters_categorydata?->name ?? '';
        }
        if (in_array($carian_status_aduan, [0, 2, 3])) {
            $complaintList = $complaintList->where('complaint.complaint_status_id', $carian_status_aduan);
        }else if($carian_status_aduan == ""){ //Show All Status
            $complaintList = $complaintList->whereIn('complaint.complaint_status_id', [0,2,3]);  // baru,ditolak,selesai
        }

        $complaintListAll = $complaintList->get();

        $print_pdf = $request->input('muat_turun_pdf');
        $print_excel = $request->input('muat_turun_excel');

        if($print_pdf == 'pdf' || $print_excel == 'excel')
        {
            $carian_tarikh_aduan_dari_convert = convertDateSys($carian_tarikh_aduan_dari_db);
            $carian_tarikh_aduan_hingga_convert = convertDateSys($carian_tarikh_aduan_hingga_db);

            if($print_pdf == 'pdf')
            {
                $dataReturn = compact('complaintListAll','quarters_category','carian_tarikh_aduan_dari_convert','carian_tarikh_aduan_hingga_convert','carian_id_kategori','quarters_category_name');

                //------------------------------------------------------------------------------------------------------
                $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
                $tempPdf->setPaper('A4', 'landscape');
                $tempPdf->output();
                // Get the total page count
                $totalPages = $tempPdf->getCanvas()->get_page_count();
                //------------------------------------------------------------------------------------------------------

                $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
                $pdf->setPaper('A4', 'landscape');

                return $pdf->stream('Laporan_Aduan_Kerosakan_'.date("dmY-His").'.pdf');

            }

        }else
        {
            return view(getFolderPath().'.index', compact('complaintListAll', 'complaintStatusAll', 'quarters_category', 'carian_tarikh_aduan_dari', 'carian_tarikh_aduan_hingga', 'carian_id_kategori', 'carian_status_aduan'));
        }
    }
}
