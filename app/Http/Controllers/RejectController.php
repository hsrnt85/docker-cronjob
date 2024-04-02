<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\UserOffice;
use App\Models\UserHouse;
use App\Models\UserSalary;
use App\Models\UserSpouse;
use App\Models\UserChild;
use App\Models\ChildAttachment;
use App\Models\Epnj;
use App\Models\ApplicationAttachment;
use App\Models\DocumentsQuartersAcceptanceAttachment;
use App\Models\UserInfo;

class RejectController extends Controller
{
    public function index()
    {

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $rejectedOfferAppAll = Application::getRejectedOffer($district_id);

        return view( getFolderPath().'.list',
        [
            'rejectedOfferAppAll' => $rejectedOfferAppAll,
        ]);
    }


    public function show(Application $application)
    {
        //$rejectedOfferApp = Application::getRejectedOfferChecked($application->id);
        $application_id = $application->id;
        $user = $application->user;

        $userOffice = UserOffice::where('users_id', $user->id)
                        ->where('data_status', 1)
                        ->first();

        $userHouse = UserHouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        $userSalary = UserSalary::where('new_ic', $user->new_ic)
                    ->where('application_id', $application_id)
                    ->where('data_status', 1)
                    ->first();

        $userSpouse = UserSpouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->first();

        $userChildAll = UserChild::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        $userChildAttachmentAll = ChildAttachment::where('application_id', $application_id)
                                ->whereIn('users_child_id', $userChildAll->pluck('id'))
                                ->where('data_status', 1)
                                ->get();

        $userEpnj = Epnj::where('ic', $user->new_ic)
                    ->where('data_status', 1)
                    ->first();

        $userSpouseEpnj = Epnj::where('ic', $user->spouse?->new_ic)
                            ->where('data_status', 1)
                            ->first();

        $applicationAttachmentAll = ApplicationAttachment::where('a_id', $application_id)
                                    ->where('data_status', 1)
                                    ->orderBy('d_id', 'asc')
                                    ->get();

        $quartersAcceptanceAttachmentAll = DocumentsQuartersAcceptanceAttachment::where('application_id', $application_id)
                                    ->where('data_status', 1)
                                    ->get();

        $userInfo = UserInfo::getUserInfoById($application->user_info_id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'application' => $application,
                'user' => $user,
                'userOffice' => $userOffice,
                'userHouse' => $userHouse,
                'userSalary' => $userSalary,
                'userSpouse' => $userSpouse,
                'userChildAll' => $userChildAll,
                'userChildAttachmentAll' => $userChildAttachmentAll,
                'userEpnj' => $userEpnj,
                'userSpouseEpnj' => $userSpouseEpnj,
                'userInfo' => $userInfo,
                'applicationAttachmentAll' => $applicationAttachmentAll,
                'quartersAcceptanceAttachmentAll' => $quartersAcceptanceAttachmentAll,
                'cdn' => config('env.upload_ftp_url')
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }
}
