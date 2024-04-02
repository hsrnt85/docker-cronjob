<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\ServiceType;
use App\Models\Month;
use App\Models\Tenant;
use App\Models\QuartersCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\ListData;
use App\Models\Organization;
use App\Models\FinanceDepartment;

class NoticePaymentReportController extends Controller
{
    public function index (Request $request){

        //Request
        $searchIcNo               =  str_replace('-', '', $request->ic_no) ?? null;
        $selectedYear             =  $request->year ?? null;
        $selectedMonth            =  $request->month ?? null;
        $selectedServicesType     =  $request->services_type ?? null;
        $selectedDistrict         =  $request->district ?? null;
        $searchNoticeNo           =  $request->notice_no ?? null;
        $selectedQuartersCategory =  $request->quarters_category ?? null;
        $selectedOrganization     = $request->organization ?? null;


        //Dropdown
        $yearAll             = ListData::Year();
        $monthAll            = Month::get_month();
        $servicesTypeAll     = ServiceType::get_services_type();
        $quartersCategoryAll = QuartersCategory::getAllQuartersCategory();
        $districtAll         = (!is_all_district()) ? ListData::District(districtId()) : ListData::District();
        $organizationAll     = Organization::all();

        $finance_department = FinanceDepartment::finance_department_by_district(financeDistrictId());
        $tenantPaymentNoticeAll = Tenant::tenantPaymentNoticeAll($selectedYear, $selectedMonth, $selectedDistrict, $selectedQuartersCategory, $selectedServicesType, $searchNoticeNo, $searchIcNo);

        foreach ($tenantPaymentNoticeAll as $tenant) {
            $organization = Organization::find($tenant->organization_id);
            $tenant->organization_name = $organization ? $organization->name : 'N/A';
        }

        if ($selectedOrganization) {
            $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->where('organization_id', $selectedOrganization);
        }

        if($request->input('muat_turun_pdf'))
        {
            $month = ''; $district = ''; $servicesType = '';  $quartersCategory = '';

            ($selectedMonth) ? $month = Month::select('id','name')->where('id', $selectedMonth)->first()->name : '';
            ($selectedServicesType) ? $servicesType = ServiceType::select('id','services_type')->where('id', $selectedServicesType)->first()->services_type : '';
            ($selectedQuartersCategory) ? $quartersCategory = QuartersCategory::select('id', 'name')->where('id', $selectedQuartersCategory)->first()->name : '';
            ($selectedDistrict) ? $district = District::select('id', 'district_name')->where('id', $selectedDistrict)->first() : '';
            $organizationName = $selectedOrganization ? Organization::select('id', 'name')->where('id', $selectedOrganization)->first()->name : '';

            $dataReturn = compact('searchIcNo', 'selectedYear', 'selectedMonth', 'searchNoticeNo', 'selectedServicesType', 'selectedQuartersCategory', 'selectedDistrict', 'selectedOrganization', 'district', 'servicesType', 'quartersCategory', 'month', 'tenantPaymentNoticeAll','finance_department');

            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan_Notis_Bayaran_'.date("dmY-His").'.pdf');

        }
        else
        {
            return view(getFolderPath().'.index',  [
                'districtAll'          => $districtAll,
                'servicesTypeAll'      => $servicesTypeAll,
                'quartersCategoryAll'  => $quartersCategoryAll,
                'yearAll'              => $yearAll,
                'monthAll'             => $monthAll,
                'searchIcNo'           => $searchIcNo,
                'selectedYear'         => $selectedYear,
                'selectedMonth'        => $selectedMonth,
                'searchNoticeNo'       => $searchNoticeNo,
                'selectedDistrict'     => $selectedDistrict,
                'selectedServicesType' => $selectedServicesType,
                'selectedQuartersCategory' => $selectedQuartersCategory,
                'tenantPaymentNoticeAll'   => $tenantPaymentNoticeAll,
                'organizationAll'          => $organizationAll,
                'selectedOrganization'     => $selectedOrganization,

            ]);
        }
    }
}
