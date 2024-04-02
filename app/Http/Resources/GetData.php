<?php

namespace App\Http\Resources;

use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\User;
use App\Models\UserOffice;
use App\Models\UserHouse;
use App\Models\UserSpouse;
use App\Models\UserChild;
use App\Models\ChildAttachment;
use App\Models\UserSalary;
use App\Models\Epnj;
use App\Models\ApplicationScoring;
use App\Models\Api\Api_UserOffice;
use App\Models\Api\Api_District;

class GetData
{

    public static function Application($application_id, $is_selected=0){

        $data = Application::where([
                ['application.is_draft', 0],
                //['application.data_status', 1],
                ['application.id', $application_id]
            ])
            ->whereNot('data_status', 0)
            ->first();

        // $data = Application::join('application_quarters_categoryx', 'application.id', '=', 'application_quarters_category.application_id')
        //     ->join('quarters_category', 'quarters_category.id', '=', 'application_quarters_category.quarters_category_id')
        //     ->where([
        //         ['application.is_draft', 0],
        //         ['application.data_status', 1],
        //         ['application.id', $application_id],
        //         ['is_selected', $is_selected],
        //     ])
        //     ->select('application.*', 'quarters_category_id', 'quarters_category.name')
        //     ->first();
        //dd($data);
        return $data;
    }

    public static function ApplicationAttachment($application_id){

        $data = ApplicationAttachment::where('a_id', $application_id)
                ->where('data_status', 1)
                ->orderBy('d_id', 'asc')
                ->get();
        return $data;
    }

    public static function User($user_id){

        $data = User::where('id', $user_id)
                ->where('data_status', 1)
                ->first();
        return $data;
    }

    public static function UserOffice($user_id){

        $data = UserOffice::where('users_id', $user_id)
                ->where('data_status', 1)
                ->first();
        return $data;
    }

    public static function UserHouse($user_id){
        $data = UserHouse::where('users_id', $user_id)
                ->where('data_status', 1)
                ->get();
        return $data;
    }

    public static function UserSpouse($user_id){
        $data = UserSpouse::where('users_id', $user_id)
                ->where('data_status', 1)
                ->first();
        return $data;
    }

    public static function UserChild($user_id){
        $data = UserChild::where('users_id', $user_id)
                ->where('data_status', 1)
                ->get();
        return $data;
    }

    public static function ChildAttachment($application_id, $user_child_id_arr){
        $data = ChildAttachment::where('application_id', $application_id)
                ->whereIn('users_child_id', $user_child_id_arr)
                ->where('data_status', 1)
                ->get();
        return $data;
    }

    public static function UserSalary($user_new_ic, $application_id){
        $data = UserSalary::where('new_ic', $user_new_ic)
                ->where('application_id', $application_id)
                ->where('data_status', 1)
                ->first();
        return $data;
    }

    public static function Epnj($user_new_ic){
        $data = Epnj::where('ic', $user_new_ic)
                ->where('data_status', 1)
                ->first();
        return $data;
    }

    public static function ApplicationScoring($application_id){

        $data = ApplicationScoring::where(['data_status'=> 1, 'application_id'=>$application_id])->get();
        return $data;
    }

    public static function Api_UserOffice($user_id){

        $data = Api_UserOffice::where('users_id', $user_id)
                ->where('data_status', 1)
                ->first();
        return $data;
    }

    public static function Api_District($district_id){
        $data = Api_District::where('id', $district_id)
                ->where('data_status', 1)
                ->first();
        return $data;
    }
}
