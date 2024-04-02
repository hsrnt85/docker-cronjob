<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\ComplaintType;
use App\Models\QuartersCategory;
use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringReportController extends Controller
{
    public function index (Request $request){

        $district_id         = (!is_all_district()) ? districtId() : null;
        $search_ref_no       = ($request->ref_no) ? ($request->ref_no) : null;
        $search_date_from    = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $search_date_to      = ($request->date_to)   ? convertDatepickerDb($request->date_to)   : null;
        $search_quarters_cat = ($request->quarters_category) ? $request->quarters_category : null;
        $search_status       = ($request->complaint_status) ? $request->complaint_status : "0";
        $search_type         = ($request->complaint_type) ? $request->complaint_type : null;
        //dropdown
        $quartersCatAll = QuartersCategory::select('id', 'name')->where('data_status', 1)->orderBy('name');
        if($district_id) {$quartersCatAll = $quartersCatAll->where('district_id', $district_id); }
        $quartersCatAll = $quartersCatAll->get();
        $complaintTypeAll   = ComplaintType::where('data_status', 1)->get();

        $quarters_name     = ($search_quarters_cat) ?  QuartersCategory::select('name')->where('id', $search_quarters_cat )->first()->name : "";
        $complaint_type    = ($search_type) ? ComplaintType::select('complaint_name')->where('id', $search_type )->first()->complaint_name : "";
        $complaint_status  = ($search_status) ? ComplaintStatus::select('complaint_status')->where('id', $search_status )->first()->complaint_status : "BARU";

        $getMonitoringList = Complaint::getMonitoringReport($district_id, $search_date_from, $search_date_to, $search_quarters_cat, $search_status,  $search_type);
    
        if($request->input('muat_turun_pdf'))
        {
            $dataReturn = [   'getMonitoringList' => $getMonitoringList,
                        'quarters_name'           => $quarters_name,
                        'search_quarters_cat'     => $search_quarters_cat,
                        'search_status'           => $search_status,
                        'search_type'             => $search_type,
                        'search_date_from'        => convertDateSys($search_date_from),
                        'search_date_to'          => convertDateSys($search_date_to),
                        'complaint_type'          => $complaint_type,
                        'complaint_status'        => $complaint_status,
                    ];

            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath().'.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan_Pemantauan_'.date("dmY-His").'.pdf');

        }else{

            return view(getFolderPath().'.index',  [
                'search_ref_no'        => $search_ref_no,
                'search_date_from'     => convertDateSys($search_date_from),
                'search_date_to'       => convertDateSys($search_date_to),
                'search_quarters_cat'  => $search_quarters_cat,
                'search_status'        => $search_status,
                'search_type'          => $search_type,
                'quartersCatAll'       => $quartersCatAll,
                'getMonitoringList'    => $getMonitoringList,
                'complaintTypeAll'     => $complaintTypeAll,
                'quarters_name'        => $quarters_name,
                'complaint_type'       => $complaint_type,
                'complaint_status'       => $complaint_status,
            ]);
        }
    }

    public function ajaxGetComplaintStatus(Request $request)
    {
        $complaint_type = $request->input('type');

        $complaint_status = ComplaintStatus::select('id', 'complaint_status')
                ->where('status_data', 1);

                if($complaint_type == 1){
                    $complaint_status = $complaint_status->whereIn('id', [0,2, 3, 5]); //baru,ditolak,selesai,selenggara
                }else{
                    $complaint_status = $complaint_status->whereIn('id', [0, 1, 2, 3, 4]); //baru,diterima,ditolak,selesai,berulang
                }

                $complaint_status = $complaint_status->get();

        if ($complaint_status) { return response()->json($complaint_status, 200);
        } 
    }
}
