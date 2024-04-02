<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceDepartment;
use App\Models\InternalJournal;
use Barryvdh\DomPDF\Facade\Pdf;

class InternalJournalReportController extends Controller
{
    public function index (Request $request){

        $district_id        = (!is_all_district()) ? districtId() : null;
        $search_ref_no      = ($request->ref_no) ? ($request->ref_no) : null;
        $search_date_from   = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $search_date_to     = ($request->date_to)   ? convertDatepickerDb($request->date_to)   : null;
        $finance_department = FinanceDepartment::finance_department_by_district($district_id);
        $getInternalJournal = InternalJournal::get_passed_internal_journal( $district_id, $search_date_from, $search_date_to); // telah lulus

        if($request->input('muat_turun_pdf'))
        {

            $dataReturn = [   'getInternalJournal' => $getInternalJournal,
                        'fd'  => $finance_department,
                        'search_date_from' => convertDateSys($search_date_from),
                        'search_date_to'   => convertDateSys($search_date_to),
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
            return $pdf->stream('Laporan_Jurnal_Pelarasan_Dalaman_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index',  [
                'search_ref_no'    => $search_ref_no,
                'search_date_from' => convertDateSys($search_date_from),
                'search_date_to'   => convertDateSys($search_date_to),
                'getInternalJournal' => $getInternalJournal
            ]);
        }
    }
}
