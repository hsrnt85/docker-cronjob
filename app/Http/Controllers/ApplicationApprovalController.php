<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use App\Models\ApplicationReview;
use App\Models\ApplicationHistory;
use App\Models\ApplicationStatus;
use App\Models\ApplicationQuartersCategory;
use App\Http\Requests\ApplicationApprovalPostRequest;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApplicationApprovalController extends Controller
{

    public function index()
    {

        $application_status_id = 3;
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $applicationAll = ListData::ApplicationByOfficerID($application_status_id, $district_id);
        $applicationHistoryAll = ListData::ApplicationHistoryByOfficerID($application_status_id, $district_id);

        return view( getFolderPath().'.list',
        [
            'applicationAll' => $applicationAll,
            'applicationHistoryAll' => $applicationHistoryAll
        ]);
    }

    public function edit(Request $request)
    {

        $application_id = $request->id;
        $quarters_category_id = $request->qcid;

        $application_statusAll = ApplicationStatus::where('flag_kelulusan_permohonan',1)->select('id','status')->get();

        //APPLICATION INFO
        $application = getData::Application($application_id);//format -> ApplicationData($application_id, $is_selected=NULL)
        if(!$application){
            return redirect()->route('applicationApproval.index')->with('error', 'Rekod permohonan tidak ditemui!');
        }else{

            $application_status_id = $application?->current_status?->application_status_id;//check current status
            if($application_status_id>3){
                //block if Permohonan ini telah dibuat kelulusan permohonan
                return redirect()->route('applicationApproval.index')->with('error', 'Permohonan ini telah dibuat kelulusan permohonan !!');
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
            $userInfo = UserInfo::getUserInfoById($application->user_info_id);

            //SCORING INFO
            $applicationScoringInfo = getData::ApplicationScoring($application_id);

        }

        $tab = 'application-for-action';

        // if(checkPolicy("U"))
        // {
            return view( getFolderPath().'.approval',
                [
                    'application' => $application,
                    'applicationAttachmentAll' => $applicationAttachmentAll,
                    'application_statusAll' => $application_statusAll,
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
                    'cdn' => getCdn(),
                    'tab' => $tab,

                ]);
        // }
        // else
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }

    }

    public function store(ApplicationApprovalPostRequest $request)
    {

        $application_id = $request->id;
        $quarters_category_id = $request->qcid;
        $application_statusArr = $request->application_status;
        $remarks = isset($request->remarks) ? $request->remarks : '';

        DB::beginTransaction();

        try {

            if(isset($application_statusArr)){

                foreach($application_statusArr as $application_status_id){
                    //UPDATE APPLICATION REVIEW
                    $applicationReview = new ApplicationReview;
                    $applicationReview->application_id       = $application_id;
                    $applicationReview->application_status_id  = $application_status_id; //semakan
                    $applicationReview->remarks              = $remarks;
                    $applicationReview->action_by            = loginId();
                    $applicationReview->action_on            = currentDate();
                    $applicationReview->save();

                    //UPDATE APPLICATION HISTORY
                    $applicationHistory = new ApplicationHistory;
                    $applicationHistory->application_id         = $application_id;
                    $applicationHistory->application_status_id  = $application_status_id; //4:Diluluskan 6:Ditolak semasa lulus
                    $applicationHistory->action_by              = loginId();
                    $applicationHistory->action_on              = currentDate();
                    $applicationHistory->save();

                    ApplicationQuartersCategory::where(['application_id'=> $application_id,'quarters_category_id'=>$quarters_category_id])
                                            ->update([
                                                'is_selected' => 1,
                                                'action_by' => loginId(),
                                                'action_on' => currentDate()
                                            ]);

                    // User Activity - Save
                    setUserActivity("P", "Kelulusan Permohonan ".$applicationReview->application?->user?->name);

                }
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('applicationApproval.score', [ 'id' => $application_id, 'qcid' => $quarters_category_id ])->with('error', 'Proses kelulusan permohonan tidak disimpan!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationApproval.index')->with('success', 'Proses kelulusan permohonan telah disimpan!');
    }

    public function view(Request $request)
    {

        $application_id = $request->id;

        //APPLICATION INFO
        $application = getData::Application($application_id);//format -> ApplicationData($application_id,  $is_selected=NULL)

        if(!$application){
            return redirect()->route('applicationApproval.index')->with('error', 'Rekod permohonan tidak ditemui!');
        }else{
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
            $applicationScoringInfo = getData::ApplicationScoring($application_id);

            $applicationReview = ApplicationReview::where(['application_id'=> $application_id, 'application_status_id'=> 5, 'data_status' => 1])->first();
        }

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
