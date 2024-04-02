<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{

    public function index($token) {

        Session::put('lang_file', 'user');

        $exist = PasswordReset::where('token', $token)->first();

        if ($exist) {
            $reset = PasswordReset::where(['data_status'=> 1, 'token'=> $token])->where('action_on', '>', now()->subDays(3))->first();

            if ($reset) {
                $user = User::where('id', $reset->users_id)->first();

                return view('auth.reset-password', compact('user', 'token'));
            }

            return view('auth.auth-info',['flagPageError' => "1"]);
        }

        return view('auth.auth-info',['flagPageError' => "2"]);
    }

	public function store($token, ResetPasswordRequest $request)
    {
        $password_reset = PasswordReset::where('token', $token)->firstOrFail();
        $users_id = $password_reset->users_id;

        // $user = User::where(['data_status'=>1, 'id'=>$password_reset->users_id])->first();
        $user = User::where(['data_status'=>1, 'id'=>$users_id])
        ->orWhere(function($query) use ($users_id) {
            $query->where('data_status', 2)
                  ->where('id', '=', $users_id);
        })
        ->first();

        if($user){

            $user->update([
                'password' => bcrypt($request->password),
                'data_status' => 1,
            ]);

            $password_reset->update([
                'data_status'=> 0,
            ]);

            return redirect()->route('login')->with([
                'success' => 'Kata laluan telah dikemaskini. Sila log masuk menggunakan kata laluan yang baru.',
            ]);

        }else{

            return redirect()->route('login')->with([
                'error' => 'Kata laluan tidak dikemaskini. Sila log masuk menggunakan kata laluan yang baru.',
            ]);

        }
    }

}
