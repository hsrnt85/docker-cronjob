<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use App\Models\ScoringMappingHrmis;
use App\Models\ScoringScheme;
use App\Models\ScoringCriteria;
use App\Models\ScoringSubCriteria;
use App\Models\Application;
use App\Models\ApplicationReview;
use App\Models\ApplicationScoring;
use App\Models\ApplicationHistory;
use App\Models\Officer;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Http\Requests\ApplicationScoringRequest;
use App\Notifications\ApplicationStatusChangeNotification;
use Illuminate\Support\Facades\DB;

class ApplicationScoringController extends Controller
{

    public function index()
    {

        $application_status_id = 1;
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $applicationAll = ListData::Application($application_status_id, $district_id);
        $applicationHistoryAll = ListData::ApplicationHistory($application_status_id, $district_id);

        return view( getFolderPath().'.list',
        [
            'applicationAll' => $applicationAll,
            'applicationHistoryAll' => $applicationHistoryAll
        ]);
    }

    public function score(Request $request)
    {
        //-------------------------------------------------------------------------------------------------------------------
        //LIST DROPDOWN
        //-------------------------------------------------------------------------------------------------------------------
        $servicesTypeAll = ListData::ServicesType();
        $positionTypeAll = ListData::PositionType();
        $maritalStatusAll = ListData::MaritalStatus();
        $operatorAll = ListData::Operator();
        //-------------------------------------------------------------------------------------------------------------------
        // APPLICATION INFO
        //-------------------------------------------------------------------------------------------------------------------
        $application_id = $request->id;
        $quarters_category_id = $request->qcid;

        $application = getData::Application($application_id);//format -> ApplicationData($application_id)
        $application_status_id = $application?->current_status?->application_status_id;//check current status
        $data_status = $application?->data_status;
        if($data_status==0){
            //block if Permohonan ini telah dibuat pemarkahan permohonan
           return redirect()->route('applicationApproval.index')->with('error', 'Permohonan ini telah dihapus oleh pemohon !!');
        }if($data_status==2){
             //block if Permohonan ini telah dibuat pemarkahan permohonan
            return redirect()->route('applicationApproval.index')->with('error', 'Permohonan ini telah dibatalkan oleh pemohon !!');
        }elseif($application_status_id>1){
            //block if Permohonan ini telah dibuat pemarkahan permohonan
           return redirect()->route('applicationApproval.index')->with('error', 'Permohonan ini telah dibuat pemarkahan permohonan !!');
        }

        $applicationAttachmentAll = getData::ApplicationAttachment($application_id);
        $user = getData::User($application->user_id);
        if($user){
            $user_new_ic = $user->new_ic;
            $user_id = $user->id;
        }else {
            $user_id = 0;
            $user_new_ic = '';
        }
        $userOffice = getData::UserOffice($user_id);
        $userHouse = getData::UserHouse($user_id);
        $userSpouse = getData::UserSpouse($user_id);
        $userChildAll = getData::UserChild($user_id);
        $userChildAttachmentAll = getData::ChildAttachment($application_id, $userChildAll->pluck('id'));
        $userSalary = getData::UserSalary($user_new_ic, $application_id);
        $userEpnj = getData::Epnj($user_new_ic);
        $userSpouseEpnj = getData::Epnj($user?->spouse?->new_ic);

        //Get Latest User Info
        $userInfo = UserInfo::getLatestUserInfo();

        //-------------------------------------------------------------------------------------------------------------------
        //SCORING SECTION
        //-------------------------------------------------------------------------------------------------------------------
        $officerApproval = ListData::Officer(districtId(),2,1);

        $scoringScheme = ScoringScheme::select('id')
                        ->where('data_status', 1)
                        ->whereRaw('execution_date <= "'.$application->application_date_time.'"')
                        ->orderBy('execution_date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->first();
        //dd($scoringScheme);
        $scoring_scheme_id = $scoringScheme->id ?? 0;
        if($scoring_scheme_id==0){
            return redirect()->route('applicationScoring.index', [ 'id' => $request->id ])->with('error', 'Sila daftar kriteria pemarkahan terlebih dahulu!');
        }

        $criteriaArr = [];
        $subCriteriaArr = [];
        $total_mark = 0;
        $total_full_mark = 0;

        if($scoring_scheme_id>0){

            $user_id = $application->user_id;
            $new_ic = $user->new_ic;

            $scoringCriteriaAll = ScoringCriteria::where(['data_status'=> 1,'scoring_scheme_id'=>$scoring_scheme_id])->get();

            foreach($scoringCriteriaAll as $i => $criteria)
            {
                $criteria_id = $criteria->id;
                $criteria_name = $criteria->criteria_name;
                $calculation_method = $criteria->calculation_method;
                $scoring_mapping_hrmis_id = $criteria->scoring_mapping_hrmis_id;

                //MAPPING HRMIS TABLE
                $scoringMappingHrmis = ScoringMappingHrmis::where(['data_status'=>1, 'id'=>$scoring_mapping_hrmis_id])->first();
                $mapping_name = $scoringMappingHrmis->mapping_name ?? '';
                $dropdown_table = $scoringMappingHrmis->dropdown_table ?? '';
                $table = $scoringMappingHrmis->table ?? '';
                $column_id = $scoringMappingHrmis->column_id ?? '';
                $column_name = $scoringMappingHrmis->column_name ?? '';
                $is_dropdown = $scoringMappingHrmis->is_dropdown ?? '';
                $is_range = $scoringMappingHrmis->is_range ?? '';
                $is_count = $scoringMappingHrmis->is_count ?? '';
                $is_sum = $scoringMappingHrmis->is_sum ?? '';
                $is_date_diff = $scoringMappingHrmis->is_date_diff ?? '';
                $is_distance = $scoringMappingHrmis->is_distance ?? '';
                $collective_noun = $scoringMappingHrmis->collective_noun ?? '';
                $collective_noun_position = $scoringMappingHrmis->collective_noun_position ?? '';

                $criteriaArr[$i]['criteria_id'] = $criteria_id;
                $criteriaArr[$i]['criteria_name'] = $criteria_name;
                $criteriaArr[$i]['mapping_name'] = $mapping_name;
                $criteriaArr[$i]['calculation_method'] = $calculation_method;
                $criteriaArr[$i]['flag_table'] = $dropdown_table;
                $criteriaArr[$i]['is_dropdown'] = $is_dropdown;
                $criteriaArr[$i]['is_range'] = $is_range;
                $criteriaArr[$i]['collective_noun'] = $collective_noun;
                $criteriaArr[$i]['collective_noun_position'] = $collective_noun_position;

                //SUBCRITERIA
                $scoringSubCriteriaAll = ScoringSubCriteria::where(['data_status'=>1, 'scoring_criteria_id'=>$criteria_id])->get();//'id'=>170,

                foreach($scoringSubCriteriaAll as $i => $subCriteria)
                {
                    $checkData = 0;
                    $full_mark = 0;
                    $mark = 0;
                    $remarks = $subCriteria->remarks;
                    $item_id = $subCriteria->item_id;
                    $full_mark = $subCriteria->mark;

                    if($i == 0){ $total_full_mark += $subCriteria->mark; }

                    $subCriteriaArr[$criteria_id][$i]['item_id'] = $item_id;
                    $subCriteriaArr[$criteria_id][$i]['remarks'] = $remarks;
                    $subCriteriaArr[$criteria_id][$i]['full_mark'] = $full_mark;

                    //IS DROPDOWN WITH ITEM ID
                    if($is_dropdown==1) {
                        $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id,$user_id )->where( $column_name, $item_id )->first();
                        if($checkData) $subCriteriaArr[$criteria_id][$i]['mark'] = $mark = $subCriteria->mark;
                    }
                    if($is_range==1){

                        $i_prev = $i-1;
                        $range_from = $subCriteria->range_from;
                        $operator_id = $subCriteria->operator_id;
                        $range_to = $subCriteria->range_to;
                        $subCriteriaArr[$criteria_id][$i]['range_from'] = $range_from;
                        $subCriteriaArr[$criteria_id][$i]['operator_id'] = $operator_id;
                        $subCriteriaArr[$criteria_id][$i]['range_to'] = $range_to;

                        if($is_date_diff == 1){
                            if($table=='application') $id = $application_id;
                            else $id = $user_id;

                            if($operator_id == 0) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $id )->whereRaw( 'TIMESTAMPDIFF(year,'.$column_name.', now() ) <= '.$range_from )->first();
                            elseif($operator_id == 1) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $id )->whereRaw('TIMESTAMPDIFF(year,'.$column_name.', now() ) >= '.$range_from )->first();
                            elseif($operator_id == 2) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $id )->whereRaw('TIMESTAMPDIFF(year,'.$column_name.', now() ) BETWEEN '.$range_from.' AND '.$range_to )->first();//whereBetween( $column_name, [$range_from, $range_to] )->first();
                            elseif($operator_id == 3) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $id )->whereRaw( 'TIMESTAMPDIFF(year,'.$column_name.', now() ) <= '.$range_from )->first();

                            if($checkData) $subCriteriaArr[$criteria_id][$i]['mark'] = $mark = $subCriteria->mark;
                            if($checkData && isset($subCriteriaArr[$criteria_id][$i_prev])) $subCriteriaArr[$criteria_id][$i_prev]['mark'] = 0;

                        }else if($is_sum == 1){

                            $totalSumData = 0;
                            $tableSumArr = explode(',',$table);
                            $columnIdArr = explode(',',$column_id);
                            $columnSumArr = explode(',',$column_name);

                            if($tableSumArr){
                                foreach($tableSumArr as $k => $tableSum){
                                    $columnId = $columnIdArr[$k];
                                    $columnSum = $columnSumArr[$k];
                                    if($k==0){
                                        $columnIdArrL2 = explode(';',$columnId);//multiple column to filter
                                        $columnSumArrL2 = explode(';',$columnSum);//multiple column to sum
                                        $id = $application_id;
                                        $sumData = DB::table($tableSum)->select('id')->where('data_status',1)->where([$columnIdArrL2[0]=>$id, $columnIdArrL2[1]=>$new_ic])->sum(DB::raw($columnSumArrL2[0].'+'.$columnSumArrL2[1].'+'.$columnSumArrL2[2]));
                                        $totalSumData += $sumData;
                                    }else{
                                        $id = $user_id;
                                        $sumData = DB::table($tableSum)->select('id')->where('data_status',1)->where($columnId,$id)->sum($columnSum);
                                        $totalSumData += $sumData;
                                    }
                                }
                            }
                            //SUM ALL AND CHECK RANGE
                            //SALARY - USER(BASIC+ITP+BSH) & SPOUSE
                            if($operator_id == 0 && ($totalSumData <= $range_from)) $checkData = 1;
                            elseif($operator_id == 1 && ($totalSumData >= $range_from)) $checkData = 1;
                            elseif($operator_id == 2 && ($totalSumData >= $range_from && $totalSumData <= $range_to )) $checkData = 1;
                            elseif($operator_id == 3 && ($totalSumData <= $range_from)) $checkData = 1;

                            if($checkData) $subCriteriaArr[$criteria_id][$i]['mark'] = $mark = $subCriteria->mark;
                            if($checkData ==1 && isset($subCriteriaArr[$criteria_id][$i_prev])) $subCriteriaArr[$criteria_id][$i_prev]['mark'] = 0;
                            //dd($totalSumData);
                        }else if($is_count == 1){

                            $countData = DB::table($table)->select('id')->where($column_id, $user_id)->count();
                            if($countData == $range_from) $checkData = 1;
                            if($checkData) $subCriteriaArr[$criteria_id][$i]['mark'] = $mark = $subCriteria->mark;

                        }else if($is_distance == 1){

                            if($operator_id == 0) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where('address_type', 2 )->where( $column_name, '<=',  $range_from )->first();
                            elseif($operator_id == 1) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where('address_type', 2 )->where( $column_name, '>=',  $range_from )->first();
                            elseif($operator_id == 2) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where('address_type', 2 )->whereBetween( $column_name, [$range_from, $range_to] )->first();
                            elseif($operator_id == 3) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where('address_type', 2 )->where( $column_name, '<=',  $range_from )->first();

                            if($checkData) $subCriteriaArr[$criteria_id][$i]['mark'] = $mark = $subCriteria->mark;
                            if($checkData && isset($subCriteriaArr[$criteria_id][$i_prev])) $subCriteriaArr[$criteria_id][$i_prev]['mark'] = 0;

                        }else{

                            if($operator_id == 0) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where( $column_name, '<=',  $range_from )->first();
                            elseif($operator_id == 1) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where( $column_name, '>=',  $range_from )->first();
                            elseif($operator_id == 2) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->whereBetween( $column_name, [$range_from, $range_to] )->first();
                            elseif($operator_id == 3) $checkData = DB::table( $table )->select('id')->where('data_status',1)->where($column_id, $user_id )->where( $column_name, '<=',  $range_from )->first();

                            if($checkData) $subCriteriaArr[$criteria_id][$i]['mark'] = $mark = $subCriteria->mark;
                            if($checkData && isset($subCriteriaArr[$criteria_id][$i_prev])) $subCriteriaArr[$criteria_id][$i_prev]['mark'] = 0;

                        }
                    }
                    $total_mark += $mark;
                }

            }

            $criteriaArr['total']['total_full_mark'] = $total_full_mark;
           // dd($criteriaArr);
           $tab = 'application-for-action';
        }

        // if(checkPolicy("U"))
        // {
            return view( getFolderPath().'.score',
            [
                'application' => $application,
                'applicationAttachmentAll' => $applicationAttachmentAll,
                'criteriaArr' => $criteriaArr,
                'subCriteriaArr' => $subCriteriaArr,
                'servicesTypeAll' => $servicesTypeAll,
                'positionTypeAll' => $positionTypeAll,
                'maritalStatusAll' => $maritalStatusAll,
                'operatorAll' => $operatorAll,
                'user' => $user,
                'userSalary' => $userSalary,
                'userOffice' => $userOffice,
                'userHouse' => $userHouse,
                'userSpouse' => $userSpouse,
                'userInfo' => $userInfo,
                'userChildAll' => $userChildAll,
                'userChildAttachmentAll' => $userChildAttachmentAll,
                'userEpnj' => $userEpnj,
                'userSpouseEpnj' => $userSpouseEpnj,
                'cdn' => getCdn(),
                'officerApproval' => $officerApproval,
                'tab' => $tab,
            ]);
        // }
        // else
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }


    }

    public function store(ApplicationScoringRequest $request)
    {

        DB::beginTransaction();

        $application_id = $request->id;
        $quarters_category_id = $request->qcid;

        $blockNewAppl = ApplicationScoring::where('application_id',$application_id)->where('data_status',1)->first();

        if($blockNewAppl)
        {
            return redirect()->route('applicationScoring.index')->with('error', 'Pemarkahan permohonan telah dibuat!');
        }

        try {

            $criteriaNameArr = $request->criteria_name;


            foreach($criteriaNameArr as $i => $criteria_name)
            {

                $fullMarkArr = isset($request->full_mark[$i]) ? $request->full_mark[$i] : 0;
                $flag_edit = isset($request->flag_edit[$i]) ? $request->flag_edit[$i] : 0;
                $flag_section_auto_dropdown = isset($request->flag_section_auto_dropdown[$i]) ? $request->flag_section_auto_dropdown[$i] : 0;

                foreach($fullMarkArr as $k => $full_mark)
                {
                    $mark = isset($request->mark[$i][$k]) ? $request->mark[$i][$k] : 0;
                    $subcriteria_name = isset($request->subcriteria_name[$i][$k]) ? $request->subcriteria_name[$i][$k] : '';
                 
                    $applicationScoring = new ApplicationScoring;
                    $applicationScoring->application_id  = $application_id;
                    $applicationScoring->flag_auto_dropdown  = $flag_section_auto_dropdown;//1:allow mark reorder
                    $applicationScoring->flag_edit  = $flag_edit;//1:allow edit on meeting evaluation
                    $applicationScoring->criteria_name  = $criteria_name;
                    $applicationScoring->subcriteria_name  = $subcriteria_name;
                    $applicationScoring->full_mark   = $full_mark;//highest mark
                    $applicationScoring->mark        = $mark;
                    $applicationScoring->action_by   = loginId();
                    $applicationScoring->action_on   = currentDate();
                    $applicationScoring->save();
                  
                }
            }

            $remarks = isset($request->remarks) ? $request->remarks : '';
            $officer_id = $request->officer_approval;

            //GET APPLICATION
            $application = getData::Application($application_id);

            //UPDATE APPLICATION REVIEW
            $application_status =2;
            $applicationReview = new ApplicationReview;
            $applicationReview->application_id       = $application_id;
            $applicationReview->application_status_id  = $application_status; //semakan
            $applicationReview->remarks              = $remarks;
            $applicationReview->officer_id           = $officer_id;
            $applicationReview->action_by            = loginId();
            $applicationReview->action_on            = currentDate();
            $applicationReview->save();

            //UPDATE APPLICATION HISTORY
            $applicationHistory = new ApplicationHistory;
            $applicationHistory->application_id         = $request->id;
            $applicationHistory->application_status_id  = $application_status; //semakan
            $applicationHistory->action_by              = loginId();
            $applicationHistory->action_on              = currentDate();
            $applicationHistory->save();

            $applicationHistory->refresh();

            $application_status_id = $applicationHistory->application_status_id;
            $status     = $applicationHistory->status->status;
            $url        = route('applicationReview.review', ['id'=> $application_id]);

            $officerApproval = Officer::where(['data_status'=> 1, 'id'=> $officer_id])->whereRaw('FIND_IN_SET(?, officer_category_id)', [1])->get();

            $officerApproval->each(function($officer, $key) use($application, $status, $url, $applicationHistory, $application_status_id){
                $officer->user?->notify(new ApplicationStatusChangeNotification($application, $status, $url, loginData()->name ?? '', $applicationHistory->action_on, $application_status_id));
            });

            // User Activity - Save
            setUserActivity("P", "Pemarkahan Permohonan ".$application->user?->name);


            DB::commit();
            

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('applicationScoring.score', [ 'id' => $application_id, 'qcid' => $quarters_category_id])->with('error', 'Pemarkahan Permohonan tidak berjaya disimpan !' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationScoring.index')->with('success', 'Pemarkahan Permohonan telah disimpan dan akan disemak oleh pegawai penyemak !');
    }

    public function view(Request $request)
    {
        $application_id = $request->id;

        //APPLICATION INFO
        $application = getData::Application($application_id);//format -> ApplicationData($application_id,  $is_selected=NULL)
        $applicationAttachmentAll = getData::ApplicationAttachment($application_id);
        $user = getData::User($application->user_id);
        if($user){
            $user_new_ic = $user->new_ic;
            $user_id = $user->id;
        }else {
            $user_id = 0;
            $user_new_ic = '';
        }
        $userOffice = getData::UserOffice($user_id);
        $userHouse = getData::UserHouse($user_id);
        $userSpouse = getData::UserSpouse($user_id);
        $userChildAll = getData::UserChild($user_id);
        $userChildAttachmentAll = getData::ChildAttachment($application_id, $userChildAll->pluck('id'));
        $userSalary = getData::UserSalary($user_new_ic, $application_id);
        $userEpnj = getData::Epnj($user_new_ic);
        $userSpouseEpnj = getData::Epnj($user?->spouse?->new_ic);

        //Get Latest User Info
        $userInfo = UserInfo::getLatestUserInfo();

        //SCORING INFO
        $applicationScoringInfo = getData::ApplicationScoring($application_id);

        $applicationReview = ApplicationReview::where(['application_id'=> $application_id, 'data_status' => 1])->first();

        $tab = 'application-history';

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'application' => $application,
                'applicationAttachmentAll' => $applicationAttachmentAll,
                'user' => $user,
                'userSalary' => $userSalary,
                'userOffice' => $userOffice,
                'userHouse' => $userHouse,
                'userSpouse' => $userSpouse,
                'userChildAll' => $userChildAll,
                'userChildAttachmentAll' => $userChildAttachmentAll,
                'userEpnj' => $userEpnj,
                'userSpouseEpnj' => $userSpouseEpnj,
                'userInfo' => $userInfo,
                'applicationScoringInfo' => $applicationScoringInfo,
                'cdn' =>getCdn(),
                // 'officerApproval' =>   $officerApproval,
                'applicationReview' => $applicationReview,
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }
}
