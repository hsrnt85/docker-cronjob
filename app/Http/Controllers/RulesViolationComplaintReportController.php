<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Models\QuartersCategory;
use App\Http\Requests\RulesViolationComplaintReportRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RulesViolationComplaintReportExport;
use Maatwebsite\Excel\Facades\Excel;


class RulesViolationComplaintReportController extends Controller
{

    public function __construct()
    {
        set_time_limit(0);
    }

    public function rulesViolationComplaintList(Request $request){

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;
        $quarters_category = ($district_id) ? QuartersCategory::where('data_status', 1)->where('district_id', $district_id)->get(): QuartersCategory::where('data_status', 1)->get();

        $carian_tarikh_aduan_dari    = ($request->carian_tarikh_aduan_dari) ? convertDatepickerDb($request->carian_tarikh_aduan_dari ): null;
        $carian_tarikh_aduan_hingga  = ($request->carian_tarikh_aduan_hingga) ? convertDatepickerDb($request->carian_tarikh_aduan_hingga) : null;
        $carian_kategori             = ($request->carian_kategori) ? $request->carian_kategori : null;
        // $carian_status_aduan         = (in_array($request->status_aduan, [0,2,3])) ? $request->status_aduan : "";
        $carian_status_aduan = $request->status_aduan ?? "";

        $complaintStatusAll = ComplaintStatus::getComplaintStatus();

        $complaintList = Complaint::join('users','users.id','=','complaint.users_id')
        ->join('users_address_office','users_address_office.users_id','=','users.id')
        ->leftjoin('quarters','complaint.quarters_id','=','quarters.id','complaint.data_status','=','quarters.data_status')
        ->leftjoin('quarters_category','quarters.quarters_cat_id','=','quarters_category.id','complaint.data_status','=','quarters_category.data_status')
        ->select('users.name','complaint.*','quarters.unit_no', 'quarters.address_1', 'quarters.address_2', 'quarters.address_3', 'quarters_category.name as q_cat_name')
        ->whereRaw('complaint.data_status=1 AND users.data_status=1')
        ->where('complaint_type', 2);

        if($district_id) {  $complaintList = $complaintList->where('quarters_category.district_id', $district_id); }
        if($carian_kategori) {  $complaintList = $complaintList->where('quarters_category.id', $carian_kategori); }
        if($carian_tarikh_aduan_dari){ $complaintList = $complaintList->where('complaint.complaint_date','>=' , $carian_tarikh_aduan_dari); }
        if($carian_tarikh_aduan_hingga  ){ $complaintList = $complaintList->where('complaint.complaint_date','<=' , $carian_tarikh_aduan_hingga); }

        if (in_array($carian_status_aduan, [0, 2, 3])) {
            $complaintList = $complaintList->where('complaint.complaint_status_id', $carian_status_aduan);
        }else if($carian_status_aduan == ""){ //Show All Status
            $complaintList = $complaintList->whereIn('complaint.complaint_status_id', [0,2,3]);  // baru,ditolak,selesai
        }

        $complaintListAll = $complaintList->orderBy('complaint.id', 'DESC')->get();

        $quarters_categorydata = QuartersCategory::select('name')->where('id', $carian_kategori)->first();
        $quarters_category_name = $quarters_categorydata?->name ?? '';

        $print_pdf = $request->input('muat_turun_pdf');
        $print_excel = $request->input('muat_turun_excel');

            if($print_pdf == 'pdf' || $print_excel == 'excel')
            {

                if($print_pdf == 'pdf')
                {

                    $dataReturn = [
                        'complaintListAll' => $complaintListAll,
                        'quarters_category' => $quarters_category,
                        'carian_tarikh_aduan_dari' => convertDateSys($carian_tarikh_aduan_dari),
                        'carian_tarikh_aduan_hingga' => convertDateSys($carian_tarikh_aduan_hingga),
                        'carian_id_kategori' => $carian_kategori,
                        'quarters_category_name' => $quarters_category_name,
                        'carian_status_aduan' => $request->status_aduan ?? null
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

                    return $pdf->stream('Laporan_Aduan_Langgar_Peraturan_'.date("dmY-His").'.pdf');

                }
                // elseif($print_excel == 'excel')
                // {
                //     DB::statement(DB::raw('set @row=0'));

                //     $complaintListExcel = Complaint::create(['@row_number:=0'])
                //     // ->join('complaint_appointment','complaint.id','=','complaint_appointment.complaint_id')
                //     ->join('users','users.id','=','complaint.users_id')
                //     ->join('position','position.id','=','users.position_id')
                //     ->leftjoin('users_address_office','users_address_office.users_id','=','users.id','users_address_office.data_status','=','users.data_status')
                //     ->join('organization','organization.id','=','users_address_office.organization_id')
                //     ->leftjoin('quarters','complaint.quarters_id','=','quarters.id','complaint.data_status','=','quarters.data_status')
                //     ->leftjoin('quarters_category','quarters.quarters_cat_id','=','quarters_category.id','complaint.data_status','=','quarters_category.data_status')
                //     ->leftjoin('department','users_address_office.department_id','=','department.id','department.data_status','=','users.data_status')
                //     ->select(DB::raw("@row := @row + 1 as num"),
                //     'users.name AS app_name',
                //     'position.position_name AS position',
                //     'organization.name AS jabatan',
                //      DB::raw(" CONCAT(IFNULL(quarters.unit_no,''), ', ', IFNULL(quarters.address_1,''), ', ', IFNULL(quarters.address_2,''), ', ', IFNULL(quarters.address_3,'') ) AS full_address"),
                //     'complaint.complaint_description AS desc',
                //     (DB::raw('DATE_FORMAT(complaint.complaint_date, "%d/%m/%Y") as complaintdate')),
                //     // (DB::raw('DATE_FORMAT(complaint_appointment.appointment_date, "%d/%m/%Y") as appointmentdate')),

                //     )
                //     ->whereRaw('complaint.data_status=1 AND users.data_status=1 AND position.data_status=1')
                //     // ->whereRaw('complaint_appointment.appointment_status_id=1 AND complaint.complaint_type=2')
                //     ->where('complaint_type', 2)
                //     ->orderBy('complaint_date', 'DESC');

                //     if ($request->status_aduan != null) {
                //         $complaintListExcel = $complaintListExcel->where('complaint.complaint_status_id', $request->status_aduan);
                //     }

                //     if($district_id) {  $complaintListExcel = $complaintListExcel->where('quarters_category.district_id', $district_id); }
                //     if($carian_kategori) {  $complaintListExcel = $complaintListExcel->where('quarters_category.id', $carian_kategori); }
                //     if($carian_status_aduan) {  $complaintListExcel = $complaintListExcel->where('complaint.complaint_status_id', $carian_status_aduan); }
                //     if($carian_tarikh_aduan_dari){ $complaintListExcel = $complaintListExcel->where('complaint.complaint_date','>=' , $carian_tarikh_aduan_dari); }
                //     if($carian_tarikh_aduan_hingga  ){ $complaintListExcel = $complaintListExcel->where('complaint.complaint_date','<=' , $carian_tarikh_aduan_hingga); }


                //     $complaintListExcel = $complaintListExcel->get();

                //     if (!empty($complaintListExcel))
                //     {
                //         $headingname = "";
                //         return Excel::download(new RulesViolationComplaintReportExport($quarters_category_name,$complaintListExcel,$headingname), 'Laporan_Aduan_Langgar_Peraturan_'.date("dmY-His").'.xlsx');

                //     }else
                //     {
                //         return redirect()->route('rulesViolationComplaintReport.index')->with('error', 'Data tidak berjaya dicetak!');
                //     }

                // }else
                // {

                // }
            }else
            {
                    return view(getFolderPath().'.index',
                [
                    'complaintListAll' => $complaintListAll,
                    'quarters_category' => $quarters_category,
                    'carian_tarikh_aduan_dari' => convertDateSys($carian_tarikh_aduan_dari),
                    'carian_tarikh_aduan_hingga' => convertDateSys($carian_tarikh_aduan_hingga),
                    'carian_kategori' => $carian_kategori,
                    'carian_status_aduan' => $carian_status_aduan,
                    'complaintStatusAll' => $complaintStatusAll
                ]);
            }





    }
}
