<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\DB;
use App\Models\Application;
use App\Models\ActiveStatus;
use App\Models\District;
use App\Models\FinanceOfficerCategory;
use App\Models\FinanceOfficer;
use App\Models\Postcode;
use App\Models\ServicesType;
use App\Models\ServicesStatus;
use App\Models\Position;
use App\Models\PositionType;
use App\Models\PositionGrade;
use App\Models\PositionGradeType;
use App\Models\MaritalStatus;
use App\Models\Organization;
use App\Models\Roles;
use App\Models\System;
use App\Models\Operator;
use App\Models\OfficerCategory;
use App\Models\OfficerGroup;
use App\Models\Officer;
use App\Models\User;
use App\Models\InvitationPanel;
use App\Models\ScoringMappingHrmis;
use App\Models\ScoringScheme;
use App\Models\ScoringCriteria;
use App\Models\ScoringSubCriteria;
use App\Models\Year;
use App\Models\PaymentCategory;
use App\Models\AccountType;

class ListData
{
    //APPLICATION LIST BY APPLICATION STATUS
    public static function Application($application_status_id, $district_id=0){
        //DB::enableQueryLog();
        $list = Application::where([
                ['application.is_draft', 0],
                ['application.data_status', 1]
            ])
            ->whereHas('current_status', function ($query) use ($application_status_id) {
                $query->where('application_status_id', $application_status_id);
            });
        if($district_id>0){
            $list = $list->join('users', function ($join){
                $join->on('users.id', '=', 'application.user_id')
                    ->where('users.data_status', '=', 1);
            });
            $list = $list->join('users_address_office', function ($join) use ($district_id){
                $join->on('users_address_office.users_id','=','users.id')
                    ->where('users_address_office.district_id', '=', $district_id)
                    ->where('users_address_office.data_status', '=', 1);
            });
        }

        $list = $list->select('application.id','application.user_id','application.application_date_time');
        $list = $list->groupBy('application.id')->orderBy('application.application_date_time','desc')->get();

        return $list;
    }

    //APPLICATION LIST BY APPLICATION STATUS
    public static function ApplicationHistory($application_status_id, $district_id=0){

        $list = Application::where([
                ['application.is_draft', 0],
                ['application.data_status', 1]
            ])
            ->whereHas('current_status', function ($query) use ($application_status_id){
                $query->where('application_status_id', '>',$application_status_id);
                //$query->where('action_by', loginId());
            });
        if($district_id>0){
            $list = $list->join('users', function ($join){
                $join->on('users.id', '=', 'application.user_id')
                    ->where('users.data_status', '=', 1);
            });
            $list = $list->join('users_address_office', function ($join) use ($district_id){
                $join->on('users_address_office.users_id','=','users.id')
                    ->where('users_address_office.district_id', '=', $district_id)
                    ->where('users_address_office.data_status', '=', 1);
            });
        }

        $list = $list->select('application.id','application.user_id','application.application_date_time');
        $list = $list->groupBy('application.id')->orderBy('application.application_date_time','desc')->get();

        return $list;
    }

    //--------------------------- APPLICATION LIST BY OFFICER ID (SEMAKAN PERMOHONAN & KELULUSAN PERMOHONAN ) --------------------------------------------------------------------------

    //APPLICATION LIST BY APPLICATION STATUS AND OFFICER ID
    public static function ApplicationByOfficerID($application_status_id, $district_id=0){
        //DB::enableQueryLog();
        $list = Application::where([
                ['application.is_draft', 0],
                ['application.data_status', 1],
                // ['application_review.application_id', $district_id]
            ])
            ->whereHas('current_status', function ($query) use ($application_status_id) {
                $query->where('application_status_id', $application_status_id);
            });

        $list = $list->select('application.id','application.user_id','application.application_date_time', 'application_review.officer_id')
                        ->join('application_review','application_review.application_id','=','application.id')
                        ->join('officer', 'officer.id', '=', 'application_review.officer_id')
                        ->where(['application_review.data_status'=> 1, 'application_review.application_status_id'=> $application_status_id, 'officer.users_id' => loginId()]);
        if($district_id>0){
            $list = $list->where('officer.district_id', $district_id);
        }
        $list = $list->groupBy('application.id')->orderBy('application.application_date_time', 'desc')->get();

        return $list;
    }

    //APPLICATION LIST BY APPLICATION STATUS AND OFFICER ID
    public static function ApplicationHistoryByOfficerID($application_status_id, $district_id=0){

        $list = Application::where([
                ['application.is_draft', 0],
                ['application.data_status', 1]
            ])
            ->whereHas('current_status', function ($query) use ($application_status_id){
                $query->where('application_status_id', '>',$application_status_id);
                //$query->where('action_by', loginId());
            });
        $list = $list->select('application.id','application.user_id','application.application_date_time')
                    ->join('application_review','application_review.application_id','=','application.id')
                    ->join('officer', 'officer.id', '=', 'application_review.officer_id')
                    ->where(['application_review.data_status'=> 1, 'application_review.application_status_id'=> $application_status_id, 'officer.users_id' => loginId()]);

        if($district_id>0){
            $list = $list->where('officer.district_id', $district_id);
        }
        $list = $list->groupBy('application.id')->orderBy('application.application_date_time', 'desc')->get();

        return $list;
    }

    //--------------------------- KELULUSAN PERMOHONAN --------------------------------------------------------------------------

    //ORGNZATION
    public static function Organization($id=0){
        $list = Organization::where('data_status', 1);
        if($id>0){
            $list = $list->where('id',$id);
        }
        $list = $list->orderBy('name')->get();
        return $list;
    }
    //DISTRICT LIST
    public static function District($id=0){
        $list = District::where('data_status', 1);
        if($id>0){
            $list = $list->where('id',$id);
        }
        $list = $list->orderBy('district_name')->get();
        return $list;
    }
    //POSTCODE LIST
    public static function Postcode($id=0){
        $list = Postcode::with('district');

        $list = $list->orderBy('district_id')->get();
        return $list;
    }
    //SERVICE TYPE LIST
    public static function ServicesType(){
        $list = ServicesType::where('data_status', 1)->get();
        return $list;
    }
    //SERVICE STATUS LIST
    public static function ServicesStatus(){
        $list = ServicesStatus::where('data_status', 1)->get();
        return $list;
    }
    //ROLES LIST
     public static function Roles(){
        $list = Roles::where('data_status', 1)->orderBy('name')->get();
        return $list;
    }
    //ROLES LIST
    public static function SystemPlatform(){
        $list = System::where('data_status', 1)->orderBy('name')->get();
        return $list;
    }
    //OFFICER CATEGORY LIST
    public static function OfficerCategory(){
        $list = OfficerCategory::where('data_status', 1)->get();
        return $list;
    }
    public static function OfficerCategoryInSet($officer_category_id_arr){
        if($officer_category_id_arr){
            $list = OfficerCategory::where('data_status', 1)->whereIn('id', $officer_category_id_arr)->get();
            return $list;
        }
    }
    //OFFICER GROUP LIST
    public static function OfficerGroup(){
        $list = OfficerGroup::where('data_status', 1)->get();
        return $list;
    }
    //OFFICER LIST
    public static function Officer($district_id=0, $officer_group_id=0, $officer_category_id=0){
        $list = Officer::join('users','users.id','=','officer.users_id')
                    ->select('officer.id','users.name','users.id AS users_id','users.position_id')
                    ->where('officer.data_status',1);
        if($district_id>0){
            $list = $list->where('officer.district_id', $district_id);
        }
        if($officer_group_id>0){
            $list = $list->where('officer.officer_group_id', $officer_group_id);
        }
        if($officer_category_id>0){
            $list = $list->whereRaw('FIND_IN_SET(?, officer_category_id)', [$officer_category_id]);
        }
        $list = $list->orderBy('name')->get();

        return $list;
    }
    //OFFICER IN set
    public static function OfficerInSet($officer_category_id){
        $list = Officer::where('data_status', 1)
            ->whereRaw('FIND_IN_SET(?, officer_category_id)', [$officer_category_id])
            ->get();
        return $list;
    }
    //USER LIST
    public static function User($district_id=0){
        $list = User::join('users_address_office','users_address_office.users_id','=','users.id')->where('users.data_status', 1);
        if($district_id>0){
            $list = $list->where('district_id', $district_id);
        }
        $list = $list->orderBy('name')->get();

        return $list;
    }
    //INVITATION PANEL LIST
    public static function InvitationPanel($district_id=0){

        $list = InvitationPanel::where('data_status',1);
        if($district_id)
        {
            $list = InvitationPanel::where('district_id', $district_id);
        }
        $list = $list->orderBy('name')->get();

        return $list;
    }
    //POSITON GRADE LIST
    public static function Position(){
        $list = Position::where('data_status', 1)->orderBy('position_name')->get();
        return $list;
    }
    //POSITON GRADE LIST
    public static function PositionGrade(){
        $list = PositionGrade::where('data_status', 1)->orderBy(DB::raw('CAST(grade_no AS unsigned)'))->get();
        return $list;
    }
    //POSITON GRADE TYPE LIST
    public static function PositionGradeType(){
        $list = PositionGradeType::where('data_status', 1)->orderBy('grade_type')->get();
        return $list;
    }
    //POSITION TYPE LIST
    public static function PositionType(){
        $list = PositionType::where('data_status', 1)->get();
        return $list;
    }
    //MARITAL STATUS
    public static function MaritalStatus(){
        $list = MaritalStatus::where('data_status', 1)->get();
        return $list;
    }
    //POSITION TYPE LIST
    public static function PaymentCategory(){
        $list = PaymentCategory::where('data_status', 1)->get();
        return $list;
    }
    //POSITION TYPE LIST
    public static function AccountType(){
        $list = AccountType::where('data_status', 1)->get();
        return $list;
    }
    //AGENCY LIST
    public static function Agency(){
        $list = Organization::where('data_status', 1)->where('data_status',1)->orderBy('name','ASC')->get();
        return $list;
    }

    //OPERATOR
    public static function Operator(){
        $list = Operator::where('data_status', 1)->get();
        return $list;
    }

    //ACTIVE STATUS
    public static function Year(){
        $list = Year::where('data_status', 1)->orderBy('year','DESC')->get();
        return $list;
    }

     //ACTIVE STATUS
     public static function ActiveStatus(){
        $list = ActiveStatus::where('data_status', 1)->get();
        return $list;
    }

    //SCORING MAPPING HRMIS
    public static function ScoringMappingHrmis($scoring_mapping_hrmis_id=0){
        $list = ScoringMappingHrmis::where('data_status', 1);
        if ($scoring_mapping_hrmis_id > 0) {
            $list->where('id',$scoring_mapping_hrmis_id);
        }

        $list = $list->get();

        return $list;
    }
    //SCORING SCHEME
    public static function ScoringScheme(){
        $list = ScoringScheme::where('data_status', 1)->get();
        return $list;
    }
    //SCORING CRITERIA
    public static function ScoringCriteria($scoring_scheme_id=0){
        $list = ScoringCriteria::where('data_status', 1);
        if ($scoring_scheme_id > 0) {
            $list->where('scoring_scheme_id',$scoring_scheme_id);
        }
        $list = $list->get();
        return $list;
    }
    //SCORING SUBCRITERIA
    public static function ScoringSubCriteria($scoring_criteria_id=0){
        $list = ScoringSubCriteria::where('data_status', 1);
        if ($scoring_criteria_id > 0) {
            $list->where('scoring_criteria_id',$scoring_criteria_id);
        }
        $list = $list->get();
        return $list;
    }


    //--------------------------- KEWANGAN  --------------------------------------------------------------------------

    //FINANCE OFFICER CATEGORY LIST
    public static function FinanceOfficerCategory(){
    $list = FinanceOfficerCategory::where('data_status', 1)->get();
    return $list;
    }

    //FINANCE OFFICER LIST
    public static function get_finance_officer_by_district($district_id=0, $finance_officer_cat_id=0){
        $list = FinanceOfficer::join('users','users.id','=','finance_officer.users_id')
                    ->select('finance_officer.users_id', 'finance_officer.id as fin_officer_id','users.name','users.position_id')
                    ->where(['finance_officer.data_status' => 1, 'users.data_status' => 1]);
        if($district_id>0){
            $list = $list->where('finance_officer.district_id', $district_id);
        }
        if($finance_officer_cat_id>0){
            $list = $list->whereRaw('FIND_IN_SET(?, finance_officer_category_id)', [$finance_officer_cat_id]);
        }
        $list = $list->orderBy('name')->get();

        return $list;
    }

    //--------------------------- LAPORAN DINAMIK  --------------------------------------------------------------------------

    // GET STATUS PEMOHONAN UTK REPORT DROPDOWN
    public static function getReportApplicantStatus($flag_tawaran=0){
        $list = DB::table('report_applicant_status')->where('data_status', 1)->orderBy('order');
        if($flag_tawaran>0){
            $list = $list->where('flag_tawaran', $flag_tawaran);
        }
        $list = $list->get();

        return $list;
    }

    // GET STATUS PENGHUNI UTK REPORT DROPDOWN
    public static function getReportTenantStatus(){
        $list = DB::table('report_tenants_status')->where('data_status', 1)->get();
        return $list;
    }

    public static function months(){
        $months = collect([
            (object) ['bm' => 'Januari', 'month' => 1],
            (object) ['bm' => 'Februari', 'month' => 2],
            (object) ['bm' => 'Mac', 'month' => 3],
            (object) ['bm' => 'April', 'month' => 4],
            (object) ['bm' => 'Mei', 'month' => 5],
            (object) ['bm' => 'Jun', 'month' => 6],
            (object) ['bm' => 'Julai', 'month' => 7],
            (object) ['bm' => 'Ogos', 'month' => 8],
            (object) ['bm' => 'September', 'month' => 9],
            (object) ['bm' => 'Oktober', 'month' => 10],
            (object) ['bm' => 'November', 'month' => 11],
            (object) ['bm' => 'Disember', 'month' => 12],
        ]);

        return $months;
    }

}
