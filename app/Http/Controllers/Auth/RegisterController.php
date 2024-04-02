<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ListData;
use App\Models\User;
use App\Models\UserOffice;
use App\Models\UserSpouse;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegisterPostRequest;
use App\Models\PositionType;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{

    public function index() {

        Session::put('lang_file', 'user');

        $positionAll = ListData::Position();
        $positionGradeCodeAll = ListData::PositionGradeType();
        $positionGradeAll = ListData::PositionGrade();
        $districtAll =ListData::District();
        $positionTypeAll = ListData::PositionType();
        $servicesTypeAll = ListData::ServicesType();
        $organizationAll = ListData::Organization();
        $postcodeAll = ListData::Postcode();

        return view('auth.register',
        [
            'positionAll' => $positionAll,
            'positionGradeCodeAll' => $positionGradeCodeAll,
            'positionGradeAll' => $positionGradeAll,
            'positionTypeAll' => $positionTypeAll,
            'districtAll' => $districtAll,
            'postcodeAll' => $postcodeAll,
            'servicesTypeAll' => $servicesTypeAll,
            'organizationAll' => $organizationAll
        ]);

	}

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // $name  = $request->name;
            // $email = $request->email;
            $new_ic = $request->new_ic;

            $dataUser = User::whereNot('data_status', 0)->where('new_ic', $new_ic)->first();//->exists();

            if (!$dataUser) {

                $dataHRMIS  = getDataFromHRMIS($new_ic);
                $user       = insertDataHRMIS($dataHRMIS);

                $userSpouse = UserSpouse::where([
                    'data_status' => 1,
                    'users_id' => $user->id,
                ])
                ->first();

                // EPNJ Process
                $userEPNJ       = getDataFromEPNJ($user?->new_ic);
                $userSpouseEPNJ = getDataFromEPNJ($userSpouse?->new_ic);

                if($userEPNJ) insertDataEPNJ($userEPNJ);
                if($userSpouseEPNJ) insertDataEPNJ($userSpouseEPNJ);

                //SAVE TOKEN
                // $passwordReset = new PasswordReset;
                // $token = PasswordReset::token();
                // $passwordReset->users_id = $user->id;
                // $passwordReset->token = $token;
                // $passwordReset->save();

                DB::commit();

                //Email
                // $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                // $data_email = array('nama_pengguna'=>$name, 'no_kp_baru'=>$new_ic, 'emel_pengguna'=>$email, 'token'=>$token, 'pautan'=>$link);

                // Mail::send('email.auth.email-new-user', $data_email, function($message) use ($name, $email)
                // {
                //     $message->from(config('env.mail_username'), config('env.mail_sender'));
                //     $message->to($email, $name)->subject('Makluman Pendaftaran Pengguna Baru');

                // });

                return redirect()->route('login')->with('success', 'Pendaftaran Pengguna berjaya! Sila tunggu emel pengesahan dari pihak admin sistem untuk log masuk ke dalam sistem.');

            }else{

                return redirect()->route('register')->with('error', 'No Kad Pengenalan Pengguna telah wujud dalam sistem!');

                // //SAVE TOKEN
                // $passwordReset = new PasswordReset;
                // $token = PasswordReset::token();
                // $passwordReset->users_id = $dataUser->id;
                // $passwordReset->token = $token;
                // $passwordReset->save();

                // DB::commit();

                // //Email
                // $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                // $data_email = array('nama_pengguna'=>$name, 'no_kp_baru'=>$new_ic, 'emel_pengguna'=>$email, 'token'=>$token, 'pautan'=>$link);

                // Mail::send('email.auth.email-new-user', $data_email, function($message) use ($name, $email)
                // {
                //     $message->from(config('env.mail_username'), config('env.mail_sender'));
                //     $message->to($email, $name)->subject('Makluman Pendaftaran Pengguna Baru');

                // });

                // return redirect()->route('email.new.user')->with('success', 'Pendaftaran Pengguna berjaya! Sila pastikan anda menerima emel untuk set katalaluan pengguna.');

            }


        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('register')->with('error', 'Pendaftaran Pengguna tidak berjaya! Sila lengkapkan maklumat data HRMIS anda.');
        }

    }

    public function ajaxCheckIc(Request $request)
    {
        try {
            $getFields = User::where('new_ic' , $request->new_ic)->whereNot('data_status', 0)->first();

            return response()->json($getFields, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ajaxProcessDataHrmis(Request $request)
    {
        // dd($request->new_ic);
        try {
            $new_ic = $request->new_ic;
            if($new_ic) $data = getDataFromHRMIS($new_ic);
            // if($data!=null){
            //     insertDataHRMIS($data);
            // }else{

            // }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function ajaxGetDataUsers(Request $request)
    {
        try {
            $getFields = User::select('users.id','users.name','users.new_ic','users.email',
                        'users.position_id','users.position_grade_code_id','users.position_grade_id','users.position_type_id','users.services_type_id')
                //->leftJoin('users_address_office','users_address_office.users_id','=','users.id')
                //->leftJoin('organization','organization.id','=','users_address_office.organization_id')
                //->leftJoin('services_type','services_type.id','=','users.services_type_id')
                //->leftJoin('district','district.id','=','users_address_office.district_id')
                // ->select('users.id','users.name','users.new_ic','users.email','position.position_name','position_grade_code.grade_type','position_grade.grade_no',
                //         'organization.name as organization_name','services_type.services_type','district_name')
                ->where('new_ic',$request->new_ic)->first();

            return response()->json($getFields, 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetDataPositionType(Request $request)
    {
        try {
            $getFields = PositionType::select('position_type.position_type')
                ->where('position_code',$request->kod_status_lantikan)->first();

            return response()->json($getFields, 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetDataServiceType(Request $request)
    {
        try {
            $getFields = ServiceType::select('services_type.services_type')
                ->where('services_type.code',$request->kod_kumpulan_agensi)->first();

            return response()->json($getFields, 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

}
