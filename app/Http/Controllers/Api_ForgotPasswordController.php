<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;
use Mail;

class Api_ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {

        try {
            $email = $request->email;
            $new_ic = $request->new_ic;

            $user = User::where(['data_status'=> 1, 'new_ic'=> $new_ic, 'email'=> $email])->first();

            if ($user) {

                $name = $user->name;
                $passwordReset = new PasswordReset;
                $token = PasswordReset::token();
                $passwordReset->users_id = $user->id;
                $passwordReset->token = $token;
                $passwordReset->save();
                DB::commit();

                $position = $user->position?->position_name .' '.$user->position_grade_code?->grade_type .''.$user->position_grade?->grade_no;

                //Email
                $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                $data_email = array('nama_pengguna'=>$name, 'jawatan_pengguna'=>$position, 'emel_pengguna'=>$email, 'token'=>$token, 'pautan'=>$link);

                Mail::send('email.email-forgot-password', $data_email, function($message) use ($name, $email)
                {
                    $message->from(config('env.mail_username'), config('env.mail_sender'));
                    $message->to($email, $name)->subject('Makluman Reset Kata laluan Pengguna');

                });

                return response()->json([
                    'status' => true,
                    'message' => 'Pautan untuk reset kata laluan telah dihantar ke emel '.$email.' !',
                ], 200);

            }else{

                return response()->json([
                    'status' => false,
                    'message' => "Sila Masukkan NO. KAD PENGENALAN dan KATA LALUAN yang sah untuk log masuk ke dalam sistem!",
                ], 500);
            }
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => \Lang::get('mail.mail_error'),
            ], 500);
        }
    }
}
