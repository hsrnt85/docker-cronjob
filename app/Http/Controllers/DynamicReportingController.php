<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListData;
use App\Models\AccountType;
use App\Models\Application;
use App\Models\ApplicationScoring;
use App\Models\District;
use App\Models\DynamicReporting;
use App\Models\LandedType;
use App\Models\MeetingApplication;
use App\Models\PositionGrade;
use App\Models\Quarters;
use App\Models\QuartersCategory;
use App\Models\QuartersCondition;
use App\Models\ServicesType;
use App\Models\Tenant;
use App\Models\TenantsPaymentNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DynamicReportingController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = (isset($request->category)) ? $request->category : $request->kategori_laporan;
        $selectedCondition = $request->condition ?? null;
        $selectedVacancy = $request->vacancy ?? null;
        $selectedEligibility = $request->eligibility ?? null;
        $selectedGradeOption    = $request->gradeOption ?? null;
        $selectedStatus = $request->applicant_status ?? null;
        $selectedOfferStatus = $request->offer_status ?? null;
        $selectedServicesType = isset($request->services_type) ? ServicesType::find($request->services_type) : null;
        $selectedAgency = $request->agency ?? null;
        $selectedAccountType = $request->account_type ?? null;
        $selectedMeetingResult = $request->meeting_result ?? null;
        $selectedMonth = $request->month ?? null;
        $selectedYear = $request->year ?? null;
        $ic = str_replace('-', '', $request->ic) ?? null;

        $categoryAll = DynamicReporting::where('data_status', 1)->select('report_category')->distinct()->get();
        $typeAll = DynamicReporting::where('data_status', 1);
        $typeAll = ($selectedCategory) ? $typeAll->where('report_category', $selectedCategory)->get() : $typeAll->where('report_category', $categoryAll->first()->report_category)->get();
        $districtAll = (!is_all_district()) ? ListData::District(districtId()) : ListData::District();

        $quartersCategoryAll = QuartersCategory::getAllQuartersCategory();
        $quartersCategoryGrouped = $this->_groupQuartersCategoryByDistrict($quartersCategoryAll);

        $landedTypeAll = LandedType::all();
        $servicesTypeAll = ServicesType::all();
        // $gradeAll = PositionGrade::where('data_status', 1)->orderBy('grade_no')->get();
        $gradeOptions = DB::table('report_position_grade')->where('data_status', 1)->get();
        $conditionAll = QuartersCondition::all();
        $vacancyAll = collect([
            (object)['name' => 'Berpenghuni'],
            (object)['name' => 'Kosong']
        ]);
        $eligibilityAll = collect([
            (object)['name' => 'Boleh Ditawarkan'],
            (object)['name' => 'Tidak Boleh Ditawarkan']
        ]);

        $applicantStatusAll = ListData::getReportApplicantStatus();
        $offerStatusAll = ListData::getReportApplicantStatus(1);
        $tenantStatusAll = ListData::getReportTenantStatus();
        $accountTypeAll = ListData::AccountType();
        $agencyAll = ListData::Agency();
        $yearDBAll = Application::getDistinctYear();
        $months = ListData::months();

        $selectedDistrict = isset($request->district) ? District::find($request->district) : null;
        $selectedQuartersCategory = isset($request->quarters_category) ? QuartersCategory::find($request->quarters_category) : null;

        $report = (isset($request->report_type)) ? DynamicReporting::where('data_status', 1)->where('id', $request->report_type)->first() : null;
        $reportData = [];

        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Senarai Rumah Kuarters Mengikut Daerah
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 1) {
            $title = $report->report_type;
            $quartersCategories = $this->_filterQuartersCategory($request);
            // $reportData = ($request->quarters_category) ? Quarters::getStatisticByQuartersCategory($request->quarters_category) : Quarters::getStatisticByQuartersCategory2($quartersCategories, $selectedEligibility);
            $reportData = Quarters::getStatisticByQuartersCategory2($quartersCategories, $selectedEligibility, $selectedCondition);

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, "", "", "");
             }

        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Senarai Penama yang Ditawarkan Kuarters Kerajaan
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 5) {
            $title          = 'Laporan Senarai Penama yang Ditawarkan Kuarters Kerajaan';
            $from           = convertDatepickerDb($request->date_from);
            $to             = convertDatepickerDb($request->date_to);
            $district_id    = (is_all_district()) ? $request->district : districtId();
            $selectedOfferStatus = $request->offer_status;
            $selectedServicesId = $request->services_type ?? null;

            $statusTitleMap = [
                3 => ' Terima Tawaran',
                4 => ' Tolak Tawaran',
            ];

            $reportData = Application::laporanDinamikPemohonDitawarkan($from, $to, $district_id, $selectedQuartersCategory?->id, $selectedOfferStatus, $selectedServicesId, $ic);
            // if($reportData->count() > 0){
            //     dd($reportData);
            // }

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, "");
             }

        }

        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Senarai Permohonan Kuarters Kerajaan
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 6) {
            $title1          = 'Laporan Senarai Permohonan ';
            $title2          = ' Kuarters Kerajaan Negeri Johor';
            $from           = convertDatepickerDb($request->date_from);
            $to             = convertDatepickerDb($request->date_to);
            $district_id    = (is_all_district()) ? $request->district : districtId();
            $selectedStatus = $request->applicant_status;
            $selectedServicesId = $request->services_type ?? null;

            $statusTitleMap = [
                1 => ' Berjaya',
                2 => ' Tidak Berjaya',
                3 => ' Terima Tawaran',
                4 => ' Tolak Tawaran',
                5 => ' Draf',
                6 => ' Baru',
            ];

            $reportData = Application::laporanDinamikPemohon($from, $to, $district_id, $selectedStatus, $selectedServicesId, $ic);

            if (isset($selectedStatus)) {
                $title = $title1 . $statusTitleMap[$selectedStatus] . $title2;
            }else{
                $title = $title1 . $title2;
            }

            if ($selectedServicesId) {
                $title = $title1 . $title2 . ' Mengikut Taraf Perkhidmatan';
            }

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, "");
             }

        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Penghuni Kuarters
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 8) {
            $title          = 'Laporan Penghuni Kuarters';
            $from           = convertDatepickerDb($request->date_from);
            $to             = convertDatepickerDb($request->date_to);
            $selectedTenantStatus = $request->tenant_status;
            $selectedServicesId = $request->services_type ?? null;

            $reportData = Tenant::laporan_dinamik_tenant($from, $to, $selectedQuartersCategory, $selectedServicesId, $ic);

            if ($selectedServicesId) {
                $title = $title . ' Mengikut Taraf Perkhidmatan';
            }

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, "");
            }

        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Skor Pemarkahan
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 10) {
            $title = 'Laporan Skor Pemarkahan';
            $district_id = (!is_all_district()) ? districtId() : $request->district;

            $reportData = ApplicationScoring::with('application')
                ->with('application.user')
                ->with('application.user_info')
                ->with('application.user.services_type')
                ->with('application.user.position_type')
                ->with('application.user.position_grade_code')
                ->with('application.user.position_grade')
                ->with('application.user.office')
                ->with('application.user.office.organization')
                ->where('data_status', 1)
                ->whereHas('application', function ($appSubq) {
                    $appSubq->where('data_status', 1);
                })
                ->whereHas('application.histories', function ($historySubq) {
                    $historySubq->whereIn('application_status_id', [5, 7]);
                });

            if (!$request->ic) {
                $reportData = $reportData->whereHas('application.user.office', function ($offSubq) use ($district_id) {
                    $offSubq->where('district_id', $district_id);
                });
            }

            if (!$request->ic && $request->quarters_category) {
                $quartersCategory = $request->quarters_category;

                $reportData = $reportData->whereHas('application.application_quarters_categories', function ($aqcSubq) use ($quartersCategory) {
                    $aqcSubq->whereHas('quarters_category', function ($qcSubq) use ($quartersCategory) {
                        $qcSubq->where('id', $quartersCategory);
                    });
                });
            }

            if (!$request->ic && $request->services_type) {
                $service = $request->services_type;

                $reportData = $reportData->whereHas('application.user', function ($userSubq) use ($service) {
                    $userSubq->where('services_type_id', $service);
                });
            }

            if ($request->ic) {
                $reportData = $reportData->whereHas('application.user', function ($userSubq) use ($ic) {
                    $userSubq->where('new_ic', $ic);
                });
            }

            $reportData = $reportData->groupBy('application_id')
                ->select('id', 'application_id', DB::raw('sum(mark) as total_marks'))
                ->orderBy('total_marks', 'DESC');

            $reportData = $reportData->get();

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, "", "", "");
            }

        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Sejarah Pemohon
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 11) {
            $title = $report->report_type;
            $district_id = (!is_all_district()) ? districtId() : $request->district;

            $reportData = Application::with('user')->with('user_info')->where('is_draft', 0)->where('data_status', 1);

            if (!$request->ic && $district_id) {
                $reportData = $reportData->whereHas('application_quarters_categories', function ($aqcSubq) use ($district_id) {
                    $aqcSubq->whereHas('quarters_category', function ($qcSubq) use ($district_id) {
                        $qcSubq->where('district_id', $district_id);
                    });
                });
            }

            $statusMap = [
                1 => [7, 11, 12], // Berjaya
                2 => [4, 6, 8, 9], // Tidak berjaya
            ];

            if (!$request->ic && isset($statusMap[$selectedStatus])) {
                $reportData = $reportData->whereHas('current_status', function ($currSubq) use ($statusMap, $selectedStatus) {
                    $currSubq->whereIn('application_status_id', $statusMap[$selectedStatus]);
                });
            }

            if (!$selectedStatus) {
                $reportData = $reportData->whereHas('current_status', function ($currSubq) {
                    $currSubq->whereIn('application_status_id', [7, 11, 12, 4, 6, 8, 9]);
                });
            }


            if (!$request->ic && $selectedYear) {
                $reportData = $reportData->whereYear('application_date_time', $selectedYear);
            }

            if ($request->ic) {
                $reportData = $reportData->whereHas('user', function ($userSubq) use ($ic) {
                    $userSubq->where('new_ic', $ic);
                });
            }

            $reportData = $reportData->get();

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, "", "", $months);
            }
        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Sejarah Penghuni
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 12) {
            $title = $report->report_type;
            $district_id = (!is_all_district()) ? districtId() : $request->district;

            $reportData = Tenant::with('user')->where('data_status', 1);

            if (!$request->ic && $district_id) {
                $reportData = $reportData->whereHas('quarters_category', function ($qcSubq) use ($district_id) {
                    $qcSubq->where('district_id', $district_id);
                });
            }

            if (!$request->ic && $selectedQuartersCategory) {
                $reportData = $reportData->whereHas('quarters_category', function ($qcSubq) use ($selectedQuartersCategory) {
                    $qcSubq->where('id', $selectedQuartersCategory->id);
                });
            }

            if (!$request->ic && $selectedServicesType) {
                $reportData = $reportData->where('services_type_id', $selectedServicesType->id);
            }

            if (!$request->ic && $selectedYear) {
                $reportData = $reportData->whereYear('quarters_acceptance_date', $selectedYear);
            }

            if ($request->ic) {
                $reportData = $reportData->where('new_ic', $ic);
            }

            $reportData = $reportData->get();

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, "", "", $months);
            }
        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Bayaran Penghuni Kuarters
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 13) {
            $sortedData = [];
            $accountType = AccountType::find($selectedAccountType);
            $title = "Laporan Bayaran " . capitalizeText($accountType?->account_type) . " Penghuni Kuarters " . $selectedYear;
            $district_id = (!is_all_district()) ? districtId() : $request->district;

            $queryData = TenantsPaymentNotice::where('data_status', 1);

            if (!$request->ic && $district_id) {
                $queryData = $queryData->where('district_id', $district_id);
            }

            if (!$request->ic && $selectedQuartersCategory) {
                $queryData = $queryData->where('quarters_category_id', $selectedQuartersCategory->id);
            }

            if (!$request->ic && $selectedAccountType) {
                if ($selectedAccountType == 1) $queryData = $queryData->selectRaw('*, total_rental as amount');
                if ($selectedAccountType == 2) $queryData = $queryData->selectRaw('*, total_penalty as amount');
                if ($selectedAccountType == 3) $queryData = $queryData->selectRaw('*, total_maintenance_fee as amount');
            } else {
                $queryData = $queryData->selectRaw('*, total_amount_after_adjustment AS amount');
            }

            if (!$request->ic && $selectedYear) {
                $queryData = $queryData->whereYear('notice_date', $selectedYear);
            }

            if ($request->ic) {
                $queryData = $queryData->where('no_ic', $ic);
            }

            $queryData = $queryData->get();

            foreach ($queryData as $data) {
                $keyIc = $data->no_ic;
                $noticeDateMonth = (int) date('n', strtotime($data->notice_date));
                $amount = (!$selectedAccountType) ? $data->total_payment_amount : $data->amount;
                $total_payment = ($data->payment_status == 2) ? $amount : 0;

                $sortedData[$keyIc]['name'] = $data->name;
                $sortedData[$keyIc]['ic'] = $keyIc;
                $sortedData[$keyIc]['services_type'] = $data->tenant?->user?->services_type?->services_type;
                $sortedData[$keyIc]['position_type'] = $data->tenant?->user?->position_type?->position_type;
                $sortedData[$keyIc]['position_name'] = $data->tenant?->user?->position?->position_name;
                $sortedData[$keyIc]['grade_type'] = $data->tenant?->user?->position_grade_code?->grade_type;
                $sortedData[$keyIc]['grade_no'] = $data->tenant?->user?->position_grade?->grade_no;
                $sortedData[$keyIc]['organization_name'] = $data->tenant?->user?->office?->organization?->name;
                $sortedData[$keyIc]['quarters_address'] = $data->quarters_address;
                $sortedData[$keyIc]['acceptance_date'] = convertDateSys($data->tenant?->quarters_acceptance_date);
                $sortedData[$keyIc]['leave_date'] = convertDateSys($data->tenant?->leave_date) ?? '';
                $sortedData[$keyIc]['amount'] = $data->amount;
                $sortedData[$keyIc]['payments'][$noticeDateMonth] = $total_payment;
                $sortedData[$keyIc]['total_payments'] = isset($sortedData[$keyIc]['total_payments']) ? $sortedData[$keyIc]['total_payments'] + $total_payment : $total_payment;
            }

            // transform array to Object
            $sortedData = collect($sortedData)->map(function ($item) {
                return (object) $item;
            });

            $reportData = $sortedData;

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, "", "", $months);
            }
        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Hilang Kelayakan Penghuni Kuarters
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 14) {
            $title          = $report->report_type;
            $district_id    = (!is_all_district()) ? districtId() : $request->district;
            $from           = convertDatepickerDb($request->date_from);
            $to             = convertDatepickerDb($request->date_to);

            $reportData = Tenant::with('user')
                ->blacklisted()
                ->where('data_status', 1);

            if (!$request->ic && $district_id) {
                $reportData = $reportData->whereHas('quarters_category', function ($q) use ($district_id) {
                    $q->where('district_id', $district_id);
                });
            }

            if (!$request->ic && ($from && $to)) {
                $reportData = $reportData->whereBetween('blacklist_date', [$from, $to]);
            }

            if (!$request->ic && $selectedQuartersCategory) {
                $reportData = $reportData->whereHas('quarters_category', function ($qc) use ($selectedQuartersCategory) {
                    $qc->where('id', $selectedQuartersCategory->id);
                });
            }

            if ($request->ic) {
                $reportData = $reportData->where('new_ic', $ic);
            }

            $reportData = $reportData->get();

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, "");
            }
        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Penghuni Dijangka Bersara
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 15) {
            $title          = $report->report_type;
            $district_id    = (!is_all_district()) ? districtId() : $request->district;
            $from           = convertDatepickerDb($request->date_from);
            $to             = convertDatepickerDb($request->date_to);

            $reportData = Tenant::with('user')
                ->where('data_status', 1);

            if ($district_id) {
                $reportData = $reportData->whereHas('quarters_category', function ($qc) use ($district_id) {
                    $qc->where('district_id', $district_id);
                });
            }

            if (($from && $to) || $ic) {
                $reportData = $reportData->whereHas('user', function ($query) use ($from, $to, $ic) {
                    if($from && $to) $query->whereBetween('expected_date_of_retirement', [$from, $to]);
                    if($ic) $query->where('new_ic', $ic);
                });
            }

            $reportData = $reportData->get();

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, "");
            }
        }
        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Mesyuarat
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 16) {
            $title          = $report->report_type;
            $district_id    = (!is_all_district()) ? districtId() : $request->district;
            $from           = convertDatepickerDb($request->date_from);
            $to             = convertDatepickerDb($request->date_to);

            $reportData = MeetingApplication::where('data_status', 1);

            if ($district_id) {
                $reportData = $reportData->whereHas('meeting', function ($meetingSubQuery) use ($district_id) {
                    $meetingSubQuery->where('district_id', $district_id);
                });
            }

            if ($selectedQuartersCategory) {
                $reportData = $reportData->where('quarters_category_id', $selectedQuartersCategory->id);
            }

            if ($selectedMeetingResult) {
                if ($selectedMeetingResult == 99) {
                    $reportData = $reportData->where('is_delay', 1);
                } else {
                    $reportData = $reportData->where('application_status_id', $selectedMeetingResult);
                }
            }

            if ($from && $to) {
                $reportData = $reportData->whereHas('meeting', function ($query) use ($from, $to) {
                    if($from && $to) $query->whereBetween('date', [$from, $to]);
                });
            }

            if ($ic) {
                $reportData = $reportData->whereHas('application.user', function ($query) use ($ic) {
                    if($ic) $query->where('new_ic', $ic);
                });
            }

            $reportData = $reportData->with([
                'application.user', 'application.user.services_type', 'application.user.position', 'application.user.position_grade_code', 'application.user.position_grade',
                'application.user.office', 'quarters_category', 'application', 'application_status', 'meeting', 'application.user_info.services_type', 'application.user_info.position', 
                'application.user_info.position_grade_code', 'application.user_info.position_grade',
            ])->get();

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, "");
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------
        // Laporan Bayaran Penghuni Terperinci Kuarters
        //--------------------------------------------------------------------------------------------------------------------------------------
        if ($report?->id == 17) {
            $sortedData = [];
            $accountType = AccountType::find($selectedAccountType);
            $title = "Laporan Bayaran Terperinci Penghuni Kuarters " . $selectedYear;
            $district_id = (!is_all_district()) ? districtId() : $request->district;

            $queryData = TenantsPaymentNotice::where('data_status', 1);

            if (!$request->ic && $district_id) {
                $queryData = $queryData->where('district_id', $district_id);
            }

            if (!$request->ic && $selectedQuartersCategory) {
                $queryData = $queryData->where('quarters_category_id', $selectedQuartersCategory->id);
            }

            if (!$request->ic && $selectedAccountType) {
                if ($selectedAccountType == 1) $queryData = $queryData->selectRaw('*, total_rental as amount');
                if ($selectedAccountType == 2) $queryData = $queryData->selectRaw('*, total_penalty as amount');
                if ($selectedAccountType == 3) $queryData = $queryData->selectRaw('*, total_maintenance_fee as amount');
            } else {
                $queryData = $queryData->selectRaw('*, total_amount_after_adjustment AS amount');
            }

            if (!$request->ic && $selectedYear) {
                $queryData = $queryData->whereYear('notice_date', $selectedYear);
            }

            if ($request->ic) {
                $queryData = $queryData->where('no_ic', $ic);
            }

            $queryData = $queryData->get();

            foreach ($queryData as $data) {
                $keyIc = $data->no_ic;
                $noticeDateMonth = (int) date('n', strtotime($data->notice_date));
                $amount = (!$selectedAccountType) ? $data->total_payment_amount : $data->amount;
                $total_payment = ($data->payment_status == 2) ? $amount : 0;

                $sortedData[$keyIc]['name'] = $data->name;
                $sortedData[$keyIc]['ic'] = $keyIc;
                $sortedData[$keyIc]['payment_notice_no'] = $data->payment_notice_no;
                $sortedData[$keyIc]['services_type'] = $data->tenant?->user?->services_type?->services_type;
                $sortedData[$keyIc]['position_type'] = $data->tenant?->user?->position_type?->position_type;
                $sortedData[$keyIc]['position_name'] = $data->tenant?->user?->position?->position_name;
                $sortedData[$keyIc]['grade_type'] = $data->tenant?->user?->position_grade_code?->grade_type;
                $sortedData[$keyIc]['grade_no'] = $data->tenant?->user?->position_grade?->grade_no;
                $sortedData[$keyIc]['organization_name'] = $data->tenant?->user?->office?->organization?->name;
                $sortedData[$keyIc]['quarters_address'] = $data->quarters_address;
                $sortedData[$keyIc]['acceptance_date'] = convertDateSys($data->tenant?->quarters_acceptance_date);
                $sortedData[$keyIc]['leave_date'] = convertDateSys($data->tenant?->leave_date) ?? '';
                $sortedData[$keyIc]['rental_amount'] = $data->rental_amount;
                $sortedData[$keyIc]['outstanding_rental_amount'] = $data->outstanding_rental_amount;
                $sortedData[$keyIc]['total_rental'] = $data->total_rental;
                $sortedData[$keyIc]['penalty_amount'] = $data->damage_penalty_amount + $data->blacklist_penalty_amount;
                $sortedData[$keyIc]['outstanding_penalty_amount'] = $data->outstanding_damage_penalty_amount + $data->outstanding_blacklist_penalty_amount;
                $sortedData[$keyIc]['total_penalty'] = $data->total_damage_penalty + $data->total_blacklist_penalty;
                $sortedData[$keyIc]['maintenance_fee_amount'] = $data->maintenance_fee_amount;
                $sortedData[$keyIc]['outstanding_maintenance_fee_amount'] = $data->outstanding_maintenance_fee_amount;
                $sortedData[$keyIc]['total_maintenance_fee'] = $data->total_maintenance_fee;
                $sortedData[$keyIc]['adjustment_amount'] = $data->adjustment_amount;
                $sortedData[$keyIc]['total_amount_after_adjustment'] = $data->total_amount_after_adjustment;
                $sortedData[$keyIc]['total_payment_amount'] = $data->total_payment_amount;
                $sortedData[$keyIc]['total_payment_balance'] = $data->total_amount_after_adjustment - $data->total_payment_amount;

                $sortedData[$keyIc]['total_payments'] = isset($sortedData[$keyIc]['total_payments']) ? $sortedData[$keyIc]['total_payments'] + $total_payment : $total_payment;
            }

            // transform array to Object
            $sortedData = collect($sortedData)->map(function ($item) {
                return (object) $item;
            });

            $reportData = $sortedData;

            if($request->input('muat_turun_pdf')) {
                return  $this->generatePDF($title, $reportData, $report?->id, $ic, $selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, "", "", "");
            }
        }

        return view(
            getFolderPath() . '.index',
            [
                'categoryAll' => $categoryAll,
                'typeAll' => $typeAll,
                'selectedCategory' => $selectedCategory,
                'districtAll' => $districtAll,
                'quartersCategoryGrouped' => json_encode($quartersCategoryGrouped),
                'landedTypeAll' => $landedTypeAll,
                'servicesTypeAll' => $servicesTypeAll,
                'agencyAll' => $agencyAll,
                'gradeOptions' => $gradeOptions,
                'applicantStatusAll' => $applicantStatusAll,
                'offerStatusAll' => $offerStatusAll,
                'tenantStatusAll' => $tenantStatusAll,
                'conditionAll' => $conditionAll,
                'vacancyAll' => $vacancyAll,
                'eligibilityAll' => $eligibilityAll,
                'accountTypeAll' => $accountTypeAll,
                'yearDBAll' => $yearDBAll,
                'months' => $months,
                'selectedDistrict' => $selectedDistrict,
                'selectedQuartersCategory' => $selectedQuartersCategory,
                'selectedServicesId' => $selectedServicesId ?? null,
                'selectedServicesType' => $selectedServicesType ?? null,
                'selectedStatus' => $selectedStatus ?? null,
                'selectedOfferStatus' => $selectedOfferStatus ?? null,
                'selectedTenantStatus' => $selectedTenantStatus ?? null,
                'selectedCondition' => $selectedCondition,
                'selectedVacancy' => $selectedVacancy,
                'selectedEligibility' => $selectedEligibility,
                'selectedAccountType' => $selectedAccountType,
                'selectedAgency' => $selectedAgency,
                'selectedMonth' => $selectedMonth,
                'selectedYear' => $selectedYear,
                'selectedLandedType' => $request->landed_type ?? null,
                'selectedGradeOption' => $selectedGradeOption,
                'selectedMeetingResult' => $selectedMeetingResult,
                'ic' => $ic,
                'from' => $from ?? null,
                'to' => $to ?? null,
                'report' => $report,
                'reportData' => $reportData,
                'title' => $title ?? null
            ]
        );
    }

    //--------------------------------------------------------------------------------------------------------------------------------------

    public function report(Request $request)
    {

        $report = DynamicReporting::where('report_type', $request->name)->where('data_status', 1)->first();

        if (!$report) {
            return redirect()->route('dynamicReport.index')->with('error', 'Laporan tidak dijumpai!');
        }
    }

    public function ajaxGetReport(Request $request)
    {

        $report = DynamicReporting::where('id', $request->name)->where('data_status', 1)->first();

        if (!$report) {
            return response()->json(['error' => 'Report tidak dijumpai'], 404);
        }

        return response()->json(['data' => $report], 201);
    }

    public function ajaxGetQuartersCategory(Request $request)
    {
        $quartersCategories = $this->_filterQuartersCategory($request);

        return response()->json(['data' => $quartersCategories], 200);
    }
    private function _groupQuartersCategoryByDistrict($quartersCategoryAll)
    {
        // Initialize an empty array to store the grouped towns
        $groupedCategories = [];

        // Loop over the towns
        foreach ($quartersCategoryAll as $quartersCategory) {
            $districtId = $quartersCategory->district->id;

            // If the state ID doesn't exist in the groupedCategories array, create a new empty array for it
            if (!isset($groupedCategories[$districtId])) {
                $groupedCategories[$districtId] = [];
            }

            // Add the town object to the respective state ID array
            $groupedCategories[$districtId][] = $quartersCategory;
        }

        return $groupedCategories;
    }

    private function _filterQuartersCategory($request)
    {
        $district_id    = (!is_all_district()) ? districtId() : $request->district;
        $gradeOptionMap = ['1' => '<=', '2' => '<='];
        $gradeOption    = $request->gradeOption;
        $landedType     = $request->landed_type;
        $categoryId     = $request->quarters_category;

        if ($gradeOption) {
            $quartersCategories = QuartersCategory::getQuartersCategoryByGradeRange41($district_id, $gradeOptionMap[$gradeOption], $landedType, $categoryId);
        } else {
            $quartersCategories = QuartersCategory::getAllQuartersCategory($district_id, $landedType, $categoryId);
        }

        return $quartersCategories;
    }

    //--------------------------------------------------------------------------------------------------------------------------------------
    //GENERATE PDF
    //--------------------------------------------------------------------------------------------------------------------------------------
    private function generatePDF($title= "" ,$reportData, $reportId, $ic,$selectedDistrict, $selectedQuartersCategory, $selectedCategory, $selectedCondition, $selectedVacancy, $selectedEligibility, $selectedGradeOption, $selectedStatus, $selectedOfferStatus, $selectedServicesType, $selectedAgency, $selectedAccountType, $selectedMeetingResult, $selectedMonth, $selectedYear, $from, $to, $months)
    {

        try {

            $fileName = str_replace(' ', '_', $title);
            $dataReturn = compact('reportData', 'title', 'reportId' ,'ic', 'selectedDistrict', 'selectedQuartersCategory' , 'selectedCategory', 'selectedCondition', 'selectedVacancy', 'selectedEligibility', 'selectedGradeOption', 'selectedStatus', 'selectedOfferStatus', 'selectedServicesType', 'selectedAgency', 'selectedAccountType', 'selectedMeetingResult', 'selectedMonth', 'selectedYear', 'from', 'to', 'months');

            // Get The Current Page
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();

            //Generate PDF
            $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream($fileName.'_' . date("dmY-His") . '.pdf');

        } catch (\Exception $e) {
            // Log or handle the exception
            return $e->getMessage().' at line '. $e->getLine();

        }
    }

}
