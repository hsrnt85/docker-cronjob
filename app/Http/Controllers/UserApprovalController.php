<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOffice;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Http\Requests\UserApprovalPostRequest;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Mail;
use Illuminate\Support\Facades\DB;

class UserApprovalController extends Controller
{
    public function index()
    {


        $senaraiUser = User::where('data_status', 3)->get();

        return view( getFolderPath().'.list',
        [
            'senaraiUser' => $senaraiUser,
        ]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $user = User::where('id', $id)->first();
        $userOffice = UserOffice::where('users_id', $user->id)->first();

        $rolesAll = Roles::where('data_status', 1)->get();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'user' => $user,
                'userOffice' => $userOffice,
                'rolesAll' => $rolesAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(UserApprovalPostRequest $request)
    { 
        try {

            DB::beginTransaction();
            $user = User::where('id', $request->id)->first();

            // User Activity - Set Data before changes
            $data_before = $user->getRawOriginal();//dd($data_before);        

            $name = $user->name; 
            $email = $user->email;

            $user->roles_id     = $request->roles;
            $user->action_by    = loginId();
            $user->action_on    = currentDate();

            $saved = $user->save();

            $position = $user->position?->position_name .' '.$user->position_grade_code?->grade_type .''.$user->position_grade?->grade_no;


            if(!$saved)
            {
                return redirect()->route('userApproval.edit', ['id'=>$request->id])->with('error', 'Pengguna tidak berjaya dikemaskini!');
            }
            else
            {
                //SAVE TOKEN
                $passwordReset = new PasswordReset;
                $token = PasswordReset::token();
                $passwordReset->users_id = $user->id;
                $passwordReset->token = $token;
                $saved = $passwordReset->save();

                //Email
                $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                $data_email = array('nama_pengguna'=>$name, 'emel_pengguna'=>$email, 'jawatan_pengguna'=>$position, 'pautan'=>$link);

                FacadesMail::send('email.auth.email-user-approval-reset-password', $data_email, function($message) use ($name, $email)
                {
                    $message->from(config('env.mail_username'), config('env.mail_sender'));
                    $message->to($email, $name)->subject('Makluman Pengesahan Pengguna');

                });

            
                $data_after = $user->toArray();

                $data_before_json = json_encode($data_before);
                $data_after_json = json_encode($data_after);
                //------------------------------------------------------------------------------------------------------------------
                // User Activity - Save
                setUserActivity("U", $name, $data_before_json, $data_after_json);


                return redirect()->route('userApproval.index')->with('success', 'Pengguna '.$name.' telah disahkan! Semakan maklumat pengguna boleh dilakukan di rekod pengguna sistem.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('userApproval.edit', ['id'=>$request->id])->with('error', 'Pengguna tidak berjaya dikemaskini!');
        }
    }

}
