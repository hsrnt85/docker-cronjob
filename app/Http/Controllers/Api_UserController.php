<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_Quarters;
use Illuminate\Http\Request;
use App\Models\Api\Api_User;

class Api_UserController extends Controller
{
    public function getUserProfileInfo(Request $request)
    {
        $user_id = $request->id;

        $user = Api_User::getUserProfileInfo($user_id);

        $item = [];

        if($user)
        {
            $item['id']           = $user->id;
            $item['name']         = capitalizeText($user->name);
            $item['phone_num']    = $user->phone_no_hp;
            $item['staff_id']     = "";
            $item['email']        = capitalizeText($user->email);
            $item['ic_num']       = $user->new_ic;

            array_push($item);
        }
        return response()->json([
            'user_model' => $item,
        ], 200);
    }

    public function getUserProfileKuartersInfo(Request $request)
    {
        $quarters_id = $request->id;

        $quarters = Api_Quarters::getUserProfileKuartersInfo($quarters_id);

        $item = [];

        if($quarters)
        {
            $item['kuartersCategory']   = $quarters->category->name;
            $item['state']              = $quarters->category->district->district_name;
            $item['noUnit']             = $quarters->unit_no;
            $item['address1']           = $quarters->address_1;
            $item['address2']           = $quarters->address_2;
            $item['address3']           = $quarters->address_3;

            array_push($item);
        }
        return response()->json([
            'kuarters_info_model' => $item,
        ], 200);
    }
}
