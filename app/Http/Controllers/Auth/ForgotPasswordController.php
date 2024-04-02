<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mail;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use App\Http\Requests\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{

    public function index() {

        Session::put('lang_file', 'user');

        return view('auth.forgot-password');

    }

    public function send_link(ForgotPasswordRequest $request)
    {
        try {
    
            $email = $request->email;
            $new_ic = $request->new_ic;

            $user = User::where(['data_status'=> 1, 'new_ic'=> $new_ic, 'email'=> $email])->first();

            if ($user) {

                $name = $user->name;
                $position = $user->position?->position_name .' '.$user->position_grade_code?->grade_type .''.$user->position_grade?->grade_no;

                $passwordReset = new PasswordReset;
                $token = PasswordReset::token();
                $passwordReset->users_id = $user->id;
                $passwordReset->token = $token;
                $passwordReset->save();
                DB::commit();

                //Email
                $link = $request->getSchemeAndHttpHost().'/'.'reset/katalaluan'.'/'.$token;

                $data_email = array('nama_pengguna'=>$name, 'emel_pengguna'=>$email, 'jawatan_pengguna'=>$position, 'token'=>$token, 'pautan'=>$link);

                Mail::send('email.auth.email-forgot-password', $data_email, function($message) use ($name, $email)
                {
                    $message->from(config('env.mail_username'), config('env.mail_sender'));
                    $message->to($email, $name)->subject('Makluman Reset Kata laluan Pengguna');

                });

                return redirect()->route('login')->with('success', 'Pautan untuk reset kata laluan telah dihantar ke emel '.$email.' ! ');

            }else{
                return redirect()->route('login')->with('error', 'Sila Masukkan NO. KAD PENGENALAN dan EMAIL yang sah untuk set semula kata laluan.! ');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', \Lang::get('mail.mail_error'));
        }
    }

}
