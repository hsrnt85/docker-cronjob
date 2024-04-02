<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use App\Models\Meeting;
use App\Models\MeetingPanel;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\ApplicationStatus;
use App\Models\ApplicationQuartersCategory;
use App\Models\QuartersCategory;
use App\Models\ApplicationScoring;
use App\Models\MeetingApplication;
use App\Http\Requests\EvaluationMeetingApplicationRequest;
use App\Http\Requests\EvaluationMeetingAttendanceRequest;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EvaluationMeetingController extends Controller
{
    public function index()
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $meeting = Meeting::where('data_status', 1)->orderBy('date','DESC');

        if($district_id)
        {
            $meeting =  $meeting->where('district_id', $district_id);
        }

        $meetingAll = $meeting->get();

        return view( getFolderPath().'.list',
        [
            'meetingAll' => $meetingAll
        ]);
    }

    public function edit(Request $request)
    {
        if(isset($request->application_id)){
            $this -> updateApplicationScoring($request);
        }

        $meeting_id = $request->id;
        $meeting = Meeting::where(['id'=> $meeting_id])->first();
        $application_status_id_arr = ApplicationStatus::where('flag_penilaian_mesyuarat',1)->select('id','status')->get();
        //PANEL - INTERNAL & MEETING PANEL
        $internalPanelAll = self::internalPanel('j',$meeting);//flag_join, $meeting data
        //PANEL - INVITATION & MEETING PANEL
        $invitationPanelAll = self::invitationPanel('j',$meeting);//flag_join, $meeting data

        //QUARTERS CATEGORY LIST
        $listQuartersCategoryAll = self::QuartersCategory($meeting_id, 0);//meeting_id, application_id
        //APPLICATION & MEETING APPLICATION
        $listApplicationAll = Application::join('application_scoring', 'application_scoring.application_id', '=', 'application.id')
            ->join('application_quarters_category', 'application_quarters_category.application_id', '=', 'application.id')
            ->join('users', 'users.id', '=', 'application.user_id')
            ->join('services_type', 'services_type.id', '=', 'users.services_type_id')
            ->join('meeting_application', function ($join) use ($meeting_id){
                $join->on('meeting_application.application_id','=','application.id');
                    $join->where('meeting_application.data_status', '=', 1);
                    $join->where('meeting_application.meeting_id', '=', $meeting_id);
                })
            ->select('application.id', DB::raw('users.name as applicant_name'), 'application.application_date_time', 'services_type.services_type',
                DB::raw('SUM(application_scoring.mark) as total_mark', 'meeting_application.application_status_id'),
                DB::raw('(CASE WHEN meeting_application.application_status_id IS NULL THEN 0 ELSE meeting_application.application_status_id END) AS meeting_application_status_id'),
                DB::raw('(CASE WHEN meeting_application.quarters_category_id IS NULL THEN 0 ELSE meeting_application.quarters_category_id END) AS meeting_quarters_category_id'),
                DB::raw('(CASE WHEN meeting_application.application_id IS NULL THEN 0 ELSE meeting_application.application_id END) AS meeting_application_id')
            )
            ->where([
                ['application.data_status', 1],
                ['application_quarters_category.data_status', 1],
                ['services_type.data_status', 1]
            ]);
            $listApplicationAll = $listApplicationAll->whereHas('current_status', function ($query) use ($meeting_id) {
                if($meeting_id==0){
                    $query->where('application_status_id', 5); // 5:Lulus
                }
            })
            ->groupBy('application_scoring.application_id')
            ->get()
            ->sortBy('total_mark');

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'meeting' => $meeting,
                'application_status_id_arr' => $application_status_id_arr,
                'internalPanelAll' => $internalPanelAll,
                'invitationPanelAll' => $invitationPanelAll,
                'listQuartersCategoryAll' => $listQuartersCategoryAll,
                'listApplicationAll' => $listApplicationAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function updateAttendance(Request $request)
    {
        $meeting_id = $request->meeting_id;

        //UPDATE ATTENDANCE STATUS
        Meeting::where('id', $meeting_id) ->update(['is_check_attendance'=> 1]);

        //UPDATE MEETING PANEL ATTENDANCE
        //---------------------------------------------------------------------------------------------------------------------------
        $meeting_internal_panel_ids = $request->meeting_internal_panel_ids;
        // Uncheck Panel-internal
        MeetingPanel::where('data_status', 1)
                    ->where('meeting_id', $meeting_id)
                    ->whereNotIn('users_id', array_keys($meeting_internal_panel_ids))
                    ->update([
                        'is_attend' => 0,
                        'delete_by' => loginId(),
                        'delete_on' => currentDate()
                    ]);
        //Update - hadir
        if($meeting_internal_panel_ids){
            foreach($meeting_internal_panel_ids as $users_id){
                $meetingPanel = MeetingPanel::where(['meeting_id'=> $meeting_id, 'users_id'=> $users_id, 'data_status'=> 1])->first();
                if($meetingPanel){

                    $meetingPanel->is_attend = 1;
                    $meetingPanel->action_by = loginId();
                    $meetingPanel->action_on = currentDate();
                    $meetingPanel->save();

                }
            }
        }
        //---------------------------------------------------------------------------------------------------------------------------
        $meeting_invitation_panel_ids = $request->meeting_invitation_panel_ids;
        // Uncheck Panel -invitation
        MeetingPanel::where('data_status', 1)
                    ->where('meeting_id', $meeting_id)
                    ->whereNotIn('invitation_panel_id', array_keys($meeting_invitation_panel_ids))
                    ->update([
                        'is_attend' => 0,
                        'delete_by' => loginId(),
                        'delete_on' => currentDate()
                    ]);
         //Update - hadir
        if($meeting_invitation_panel_ids){
            foreach($meeting_invitation_panel_ids as $invitation_panel_id)
            {
                $meetingPanel = MeetingPanel::where(['meeting_id'=> $meeting_id, 'invitation_panel_id'=> $invitation_panel_id, 'data_status'=> 1])->first();
                if($meetingPanel){
                    $meetingPanel->is_attend = 1;
                    $meetingPanel->action_by = loginId();
                    $meetingPanel->action_on = currentDate();
                    $meetingPanel->save();
                }
            }
        }

        return redirect()->route('evaluationMeeting.edit',['id' => $meeting_id])->with('success', 'Kehadiran Ahli Jawatankuasa telah dikemaskini!');

    }

    public function updateApplication(Request $request)
    {
        $meeting_id = $request->meeting_id;
        $bil_no = $request->bil_no;//dd($bil_no);
        $btn_submit_sah = $request->btn_submit;

        $quarters_category_arr = $request->quarters_category;// get all id
        foreach($quarters_category_arr as $quarters_category_id){
            //UPDATE APPLICATION STATUS
            $application_status_arr = isset($request->application_status[$quarters_category_id]) ? $request->application_status[$quarters_category_id] : '';// get all id

            if($application_status_arr){

                foreach($application_status_arr as $application_id => $application_status){

                    //MEETING - APPLICATION
                    $meetingApplication = MeetingApplication::select('id')->where(['meeting_id'=> $meeting_id, 'quarters_category_id'=> $quarters_category_id, 'application_id'=> $application_id, 'data_status'=> 1])->first();
                    if($meetingApplication){
                        $meetingApplication->application_status_id  = $application_status;
                        $meetingApplication->is_delay        = ($application_status == 99) ? 1 : 0;
                        $meetingApplication->action_by       = loginId();
                        $meetingApplication->action_on       = currentDate();
                        $meetingApplication->save();
                    }

                    if($btn_submit_sah){
                        //MEETING
                        $meeting = Meeting::select('id')->where('id', $meeting_id)->first();
                        $meeting->is_done = 1;
                        $meeting->save();

                        //APPLICATION QUARTERS CATEGORY
                        $applicationQuartersCategory = ApplicationQuartersCategory::where(['application_id'=> $application_id, 'quarters_category_id'=> $quarters_category_id, 'data_status'=>1])->first();
                        $is_selected = ($application_status == 7) ? 1 : NULL;
                        $applicationQuartersCategory->is_selected = $is_selected;
                        $applicationQuartersCategory->action_by = loginId();
                        $applicationQuartersCategory->action_on = currentDate();
                        $applicationQuartersCategory->save();

                        //APPLICATION HISTORY
                        $applicationHistory = ApplicationHistory::select('id')->where(['application_id'=> $application_id, 'data_status'=> 1])->whereIn('application_status_id', [7,8,9])->orderBy('id','desc')->first();
                        if(!$applicationHistory){
                            if($application_status>0 && $application_status!=99){
                                $applicationHistory = new ApplicationHistory;
                                $applicationHistory->application_id         = $application_id;
                                $applicationHistory->application_status_id  = $application_status; //7:lulus, 8:Gagal, 9:Gagal (Rayuan semula)
                                $applicationHistory->action_by              = loginId();
                                $applicationHistory->action_on              = currentDate();
                                $applicationHistory->save();
                            }
                        }else{
                            if($application_status == 7){
                                ApplicationHistory::where('application_id', $application_id)->orderBy('id','desc')->take(1)
                                            ->update([
                                                'application_status_id' => $application_status,
                                                'action_by' => loginId(),
                                                'action_on' => currentDate()
                                            ]);
                            }
                            
                        }
                    }
                }
            }
        }

        if($btn_submit_sah){
            // User Activity - Save
            setUserActivity("P", " Pengesahan Penilaian Untuk Bil Mesyuarat ".$bil_no);

            return redirect()->route('evaluationMeeting.index')->with('success', 'Penilaian permohonan telah disahkan!');
        }else{
            // User Activity - Save
            setUserActivity("P", " Kemaskini Penilaian Untuk Bil Mesyuarat ".$bil_no);

            return redirect()->route('evaluationMeeting.edit',['id' => $meeting_id])->with('success', 'Penilaian permohonan telah dikemaskini!');
        }
    }

    public function updateApplicationScoring(Request $request)
    {

        $scoringCriteriaIdArr = $request->scoring_criteria_id;
        if($scoringCriteriaIdArr){
            foreach($scoringCriteriaIdArr as $i => $scoring_criteria_id)
            {
                if(isset($request->mark[$i])){
                    $mark = isset($request->mark[$i]) ? $request->mark[$i] : 0;

                    $applicationScoring = ApplicationScoring::where('id', $scoring_criteria_id)
                                        ->update([
                                            'mark' => $mark,
                                            'action_by' => loginId(),
                                            'action_on' => currentDate()
                                        ]);
                }

            }

            return response()->json($applicationScoring, 200);
        }

    }

    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //AJAX PROCESS
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //BY CATEGORY
    public function ajaxGetApplicationList(Request $request)
    {
        $meeting_id = $request->mid;
        $quarters_category_id = $request->qcid;

        $listApplicationAll = Application::join('application_scoring', 'application_scoring.application_id', '=', 'application.id')
            ->join('application_quarters_category', 'application_quarters_category.application_id', '=', 'application.id')
            ->join('users', 'users.id', '=', 'application.user_id')
            ->join('quarters_category', 'quarters_category.id', '=', 'application_quarters_category.quarters_category_id')
            ->join('services_type', 'services_type.id', '=', 'users.services_type_id')
            ->join('meeting_application', function ($join) use ($meeting_id, $quarters_category_id){
                    $join->on('meeting_application.application_id','=','application.id');
                    $join->where('meeting_application.data_status', '=', 1);
                   // if($meeting_id>0){
                    $join->where('meeting_application.meeting_id', '=', $meeting_id);
                    $join->where('meeting_application.quarters_category_id', '=', $quarters_category_id);
                   // }
                });
            $listApplicationAll = $listApplicationAll->select('application.id', DB::raw('users.name as applicant_name'),DB::raw("DATE_FORMAT(application.application_date_time,'%d/%m/%Y') AS application_date"), 'services_type.services_type',
                DB::raw('quarters_category.name as category_name'),  'application_quarters_category.id as application_quarters_category_id', 'application_quarters_category.quarters_category_id',
                DB::raw('SUM(application_scoring.mark) as total_mark'),
                DB::raw('(CASE WHEN meeting_application.is_delay IS NULL THEN 0 ELSE meeting_application.is_delay END) AS is_delay'),
                DB::raw('(CASE WHEN meeting_application.application_id IS NULL THEN 0 ELSE meeting_application.application_id END) AS meeting_application_id'),
                DB::raw('(CASE WHEN meeting_application.application_status_id IS NULL THEN 0 ELSE meeting_application.application_status_id END) AS meeting_application_status_id'),
                DB::raw('(CASE WHEN meeting_application.quarters_category_id IS NULL THEN 0 ELSE meeting_application.quarters_category_id END) AS meeting_quarters_category_id')
                //DB::raw('(select MAX(application_history.application_status_id) FROM application_history where application_id = application.id order by application_status_id ASC) AS application_status')
            )
            ->where([
                ['application.data_status', 1],
                ['application_quarters_category.data_status', 1],
                ['services_type.data_status', 1]
            ]);
            if($quarters_category_id>0){
                $listApplicationAll = $listApplicationAll->where('application_quarters_category.quarters_category_id', '=', $quarters_category_id);
            }
            if($meeting_id==0){
                $listApplicationAll = $listApplicationAll->whereHas('current_status', function ($query) use ($meeting_id) {
                // if($meeting_id==0){
                    $query->where('application_status_id', 5); // 5:Lulus
                //}
                });
            }
            $listApplicationAll = $listApplicationAll->groupBy('application_scoring.application_id')->get();

        $listApplicationAll = $listApplicationAll->sortBy('total_mark');

        return response()->json($listApplicationAll);
    }

    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //FUNCITON
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function ajaxGetApplicationById(Request $request)
    {
        $application_id = $request->id;
        $quarters_category_id = $request->qcid;
        $meeting_id = $request->mid;

        $meeting = Meeting::select('is_done')->where(['id'=> $meeting_id])->first();
        //APPLICATION INFO
        $application = getData::Application($application_id);//format -> ApplicationData($application_id, $is_selected=NULL)
        $quarters_category = QuartersCategory::select('name')->where('id',$quarters_category_id)->first();
        $quarters_category_name = $quarters_category?->name ?? '';

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
        $userInfo = UserInfo::getUserInfoById($application->user_info_id);

        //SCORING INFO
        $applicationScoringInfo = ApplicationScoring::where(['data_status'=> 1, 'application_id'=>$application_id])->get();

        $cdn = getCdn();

        //RETURN ALL DATA TO ARRAY DATA ON PAGE
        $data = view( getFolderPath().'.view-application')
            ->with(compact('meeting',
                    'application',
                    'quarters_category_id',
                    'quarters_category_name',
                    'applicationAttachmentAll',
                    'user',
                    'userSalary',
                    'userOffice',
                    'userHouse',
                    'userSpouse',
                    'userChildAll',
                    'userChildAttachmentAll',
                    'userEpnj',
                    'userSpouseEpnj',
                    'userInfo',
                    'applicationScoringInfo',
                    'cdn')
            )->render();

        return response()->json(['success' => true, 'html' => $data]);

    }

    public function internalPanel($flag_join, $meeting){
        //dd( $meeting);
        $meeting_id = $meeting->id;

        if($flag_join == "j"){
            $internalPanelAll = MeetingPanel::join('officer', function ($join) use ($meeting_id){
                $join->on('officer.users_id','=','meeting_panel.users_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                });
        }else{
            $internalPanelAll = MeetingPanel::rightJoin('officer', function ($join) use ($meeting_id){
                $join->on('officer.users_id','=','meeting_panel.users_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                });
        }
        $internalPanelAll = $internalPanelAll->join('users','users.id','=','officer.users_id')
            ->where([
                ['officer.district_id',$meeting->district_id],
                ['officer.officer_group_id', 1],
            ])
            ->select('meeting_panel.meeting_id',DB::raw('meeting_panel.users_id as meeting_panel_id'),'meeting_panel.is_chairmain','meeting_panel.is_attend','officer.users_id','users.name','users.position_id','users.email')
            ->groupBy('officer.users_id')
            ->get();

      return $internalPanelAll;

    }

    public function invitationPanel($flag_join, $meeting){

        $meeting_id = $meeting->id;
        if($flag_join == "j"){
            $invitationPanelAll = MeetingPanel::join('invitation_panel', function ($join) use ($meeting_id){
                $join->on('invitation_panel.id','=','meeting_panel.invitation_panel_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                });
        }else{
            $invitationPanelAll = MeetingPanel::rightJoin('invitation_panel', function ($join) use ($meeting_id){
                $join->on('invitation_panel.id','=','meeting_panel.invitation_panel_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                });
        }
        $invitationPanelAll = $invitationPanelAll->select('meeting_panel.meeting_id',DB::raw('meeting_panel.invitation_panel_id as meeting_panel_id'),'meeting_panel.is_attend',DB::raw('invitation_panel.id as invitation_panel_id'),'invitation_panel.name','invitation_panel.position','invitation_panel.department','invitation_panel.email')//,'representative','representative_position')
            ->groupBy('invitation_panel.id')
            ->get();

        return $invitationPanelAll;

    }

    //QUARTERS CATEGORY LIST
    public function QuartersCategory($meeting_id, $application_id){

        $quartersCategoryAll = Application::join('application_quarters_category', 'application.id', '=', 'application_quarters_category.application_id')
                ->join('quarters_category', 'quarters_category.id', '=', 'application_quarters_category.quarters_category_id');
                if($meeting_id>0){
                    $quartersCategoryAll = $quartersCategoryAll->join('meeting_application', function ($join) use ($meeting_id){
                        $join->on('application.id','=','meeting_application.application_id')
                            ->where('meeting_application.meeting_id', '=', $meeting_id)
                            ->where('meeting_application.data_status', '=', 1);
                    });
                }
                $quartersCategoryAll = $quartersCategoryAll->select('application.id', DB::raw('quarters_category.name as category_name'), 'application_quarters_category.quarters_category_id')
                ->where([
                    ['application.data_status', 1],
                    ['application_quarters_category.data_status', 1]
                ]);
                if($application_id>0){
                    $quartersCategoryAll = $quartersCategoryAll->whereHas('current_status', function ($query) use ($application_id){
                        $query->where('application_status_id', $application_id); // 5:Lulus
                    });
                }
                $quartersCategoryAll = $quartersCategoryAll->groupBy('quarters_category.name')->get();

        return $quartersCategoryAll;

    }
}
