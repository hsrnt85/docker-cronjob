<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\ListData;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\ListValidateDelete;
use App\Models\UserSpouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function index()
    {
        if(is_all_district()){
            $senaraiUser = User::whereIn('data_status', [1,2])->get();
        }
        else{
            $senaraiUser = User::select('users.*','users_address_office.district_id')
                ->whereIn('users.data_status', [1,2])
                ->where('users_address_office.district_id', districtId())
                ->join('users_address_office','users_address_office.users_id','=','users.id')
                ->get();
        }

        return view( getFolderPath().'.list',
        [
            'senaraiUser' => $senaraiUser
        ]);
    }

    public function create()
    {
        $positionAll = ListData::Position();
        $positionGradeCodeAll = ListData::PositionGradeType();
        $positionGradeAll = ListData::PositionGrade();
        $districtAll =ListData::District();
        $positionTypeAll = ListData::PositionType();
        $servicesTypeAll = ListData::ServicesType();
        $organizationAll = ListData::Organization();
        $postcodeAll = ListData::Postcode();
        $rolesAll = ListData::Roles();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'positionAll' => $positionAll,
                'positionGradeCodeAll' => $positionGradeCodeAll,
                'positionGradeAll' => $positionGradeAll,
                'positionTypeAll' => $positionTypeAll,
                'districtAll' => $districtAll,
                'postcodeAll' => $postcodeAll,
                'servicesTypeAll' => $servicesTypeAll,
                'organizationAll' => $organizationAll,
                'rolesAll' => $rolesAll,

            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function store(Request $request)
    {
        // dd($_REQUEST);
        DB::beginTransaction();

        try {
            // $name  = $request->name;
            // $email = $request->email;
            $new_ic = $request->new_ic;
            // dd($new_ic);
            
            $dataUser = User::whereNot('data_status', 0)->where('new_ic', $new_ic)->first();//->exists();

            if (!$dataUser) {

                $dataHRMIS  = getDataFromHRMIS($new_ic);
                $user       = insertDataHRMIS($dataHRMIS);

                User::where(['data_status'=> 2,'new_ic'=> $new_ic])->update(['roles_id' => $request->roles ]);

                $userSpouse = UserSpouse::where([
                    'data_status' => 1,
                    'users_id' => $user->id,
                ])
                ->first();

                // EPNJ Process
                $userEPNJ = getDataFromEPNJ($user?->new_ic);
                if($userEPNJ) insertDataEPNJ($userEPNJ);
                
                if($userSpouse!=null){
                    $userSpouseEPNJ = getDataFromEPNJ($userSpouse->new_ic);
                    if($userSpouseEPNJ) insertDataEPNJ($userSpouseEPNJ);
                } 

                $passwordReset = new PasswordReset;
                $token = PasswordReset::token();
                $passwordReset->users_id = $user->id;
                $passwordReset->token = $token;
                $passwordReset->save();

                DB::commit();

                //Email
                $name = $user->name;  
                $email = $user->email;
                $position = $user->position?->position_name .' '.$user->position_grade_code?->grade_type .''.$user->position_grade?->grade_no;
                $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                $data_email = array('nama_pengguna'=>$name, 'no_kp_baru'=>$new_ic, 'jawatan_pengguna'=>$position, 'emel_pengguna'=>$email, 'token'=>$token, 'pautan'=>$link);
            
                //------------------------------------------------------------------------------------------------------------------
                // Save User Activity
                //------------------------------------------------------------------------------------------------------------------
                setUserActivity("A", $name);
                //------------------------------------------------------------------------------------------------------------------
            
                Mail::send('email.auth.email-new-user', $data_email, function($message) use ($name, $email)
                {
                    $message->from(config('env.mail_username'), config('env.mail_sender'));
                    $message->to($email, $name)->subject('Makluman Pendaftaran Pengguna Baru');

                });

                return redirect()->route('user.index')->with('success', 'Pendaftaran Pengguna berjaya! Emel pengesahan telah dihantar kepada '.$email.' untuk pengaktifan akaun.');
            }
            else{
                return redirect()->route('user.create')->with('error', 'Pendaftaran pengguna tidak berjaya!');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('user.create')->with('error', 'Pendaftaran Pengguna tidak berjaya! ' . ' ' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->first();

        $positionAll = ListData::Position();
        $positionGradeCodeAll = ListData::PositionGradeType();
        $positionGradeAll = ListData::PositionGrade();
        $districtAll =ListData::District();
        $positionTypeAll = ListData::PositionType();
        $servicesTypeAll = ListData::ServicesType();
        $organizationAll = ListData::Organization();
        $rolesAll = ListData::Roles();
        $systemPlatformAll = ListData::SystemPlatform();
        $activeStatusAll = ListData::ActiveStatus();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
        [
            'user' => $user,
            'positionAll' => $positionAll,
            'positionGradeCodeAll' => $positionGradeCodeAll,
            'positionGradeAll' => $positionGradeAll,
            'positionTypeAll' => $positionTypeAll,
            'districtAll' => $districtAll,
            'servicesTypeAll' => $servicesTypeAll,
            'organizationAll' => $organizationAll,
            'rolesAll' => $rolesAll,
            'systemPlatformAll' => $systemPlatformAll,
            'activeStatusAll' => $activeStatusAll
        ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(Request $request)
    {
        try {

            DB::beginTransaction();
            $user_id = $request->id;
            $name = $request->name; 
            $email = $request->email;

            //SAVE USER
            $user = User::where('id', $user_id)->first();

            $user->flag = $request->system_platform;
            $user->roles_id = $request->roles;
            $user->data_status =  $request->status;
            $user->action_by   = loginId();
            $user->action_on   = currentDate();
            $saved = $user->save();

            //SAVE USER OFFICE 
            // User Activity - Set Data before changes
            $data_before = $user->getRawOriginal(); 
            $data_before['item'] = $user->toArray() ?? []; //dd($data_before);


            if(!$saved)
            {
                return redirect()->route('user.edit', ['id'=>$user_id])->with('error', 'Pengguna tidak berjaya dikemaskini!');
            }
            else
            {
                // User Activity - Set Data after changes
                $data_after = $user->fresh();  //dd($data_after);
                $data_after['item'] = $data_after->toArray() ?? [];

                $data_before_json = json_encode($data_before);
                $data_after_json = json_encode($data_after);

                setUserActivity("U", $user->name, $data_before_json, $data_after_json);
                return redirect()->route('user.index')->with('success', 'Pengguna telah dikemaskini!');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('user.edit', ['id'=>$user_id])->with('error', 'Pengguna tidak berjaya dikemaskini!');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'user' => $user
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view_by_user()
    {

        if (Session::has('submenu')) {
            Session::forget('submenu');
            Session::forget('smid');
        }

        $folderPath = 'modules.SystemAdmin.User';
        return view( $folderPath.'.view-by-user');
    }

    public function destroy(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        setUserActivity("D", $user->name);

        $user->data_status  = 0;
        $user->delete_by    = loginId();
        $user->delete_on    = currentDate();

        $deleted = $user->save();

        if(!$deleted)
        {
            return redirect()->route('user.edit', ['id'=>$request->id])->with('error', 'User tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('user.index')->with('success', 'Pengguna telah dihapus!');
        }
    }

    public function send_link(Request $request)
    {
        try {
            $id = $request->id;
            $user = User::where(['data_status'=> 1, 'id'=> $id])->first();

            if ($user) {

                $name = $user->name;
                $email = $user->email;
                $new_ic = $user->new_ic;
                $position = $user->position?->position_name .' '.$user->position_grade_code?->grade_type .''.$user->position_grade?->grade_no;

                $passwordReset = new PasswordReset;
                $token = PasswordReset::token();
                $passwordReset->users_id = $user->id;
                $passwordReset->token = $token;
                $passwordReset->save();

                //Email
                $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                $data_email = array('nama_pengguna'=>$name, 'emel_pengguna'=>$email, 'jawatan_pengguna'=>$position, 'token'=>$token, 'pautan'=>$link);

                Mail::send('email.auth.email-forgot-password', $data_email, function($message) use ($name, $email)
                {
                    $message->from(config('env.mail_username'), config('env.mail_sender'));
                    $message->to($email, $name)->subject('Makluman Reset Kata laluan Pengguna');

                });

                return redirect()->route('user.index')->with('success', 'Pautan untuk reset kata laluan untuk pengguna telah dihantar ke emel '.$email.' ! ');

            }else{

                return redirect()->route('user.index')->with('error', 'Reset kata laluan tidak berjaya ! ');

            }

        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', \Lang::get('mail.mail_error'));
        }
    }

    public function ajaxCheckIcAdmin(Request $request)
    {
        try {
            $getFields = User::where('new_ic' , $request->new_ic)->whereNot('data_status', 0)->first();
            // here you could check for data and throw an exception if not found e.g.
            // if(!$getFields) {
            //     throw new \Exception('Data not found');
            // }
            return response()->json($getFields, 200);
           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateUser($id);
        return response()->json($data);
    }

}
