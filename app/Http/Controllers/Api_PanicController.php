<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_Panic;
use App\Models\Api\Api_Officer;
use Illuminate\Http\Request;
use App\Notifications\Api_EmergencyNotification;

class Api_PanicController extends Controller
{
    public function updatePanicStatus(Request $request){
        
        $user = auth('sanctum')->user();

        $updated = Api_Panic::updateOrCreate([
            'user_id' => $user->id
        ],[
            'status' => ($request->panicStatus) ? 1 : 0
        ]);

        $is_all_district = auth('sanctum')->user()->roles->is_district; 
        
        if($is_all_district){
            $officerAll = Api_Officer::join('users','officer.users_id','=','users.id')
            ->where('officer.data_status', 1)
            ->select('officer.*', 'users.name')
            ->orderBy('users.name')
            ->get();
        }
        else{
            $officerAll = Api_Officer::join('users','officer.users_id','=','users.id')
                ->where([
                        ['officer.data_status', 1],
                        ['officer.district_id', districtId()]
                    ])
                ->select('officer.*', 'users.name')
                ->orderBy('users.name')
                ->get();
        }

        foreach ($officerAll as $officer) { 
            if($officer->user){
                $officer->user?->notify(new Api_EmergencyNotification($user->name));
            }
        }

        return response()->json([
            'status' => "Notifikasi kecemasan berjaya dihantar!"
        ], 200);
    }
}
