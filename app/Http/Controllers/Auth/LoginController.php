<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index()
    {
        Session::put('lang_file', 'user');

        return view('auth.login');
    }

    public function auth(Request $request)
    {
        $new_ic = $request->new_ic;
        $password = $request->password;
        $credentials = array('new_ic'=>$new_ic, 'password'=>$password,'data_status'=>1);

        if (auth()->attempt($credentials)) {

            if(auth()->user()->flag === 1){
                
                // Save User Activity
                setUserActivity("IN", loginData()?->name);

                return redirect(RouteServiceProvider::HOME);
            }else{

                auth()->logout();

                return redirect()->route('login')->with([
                    'error' => 'Harap maaf. Anda tidak dibenarkan untuk akses ke dalam sistem.',
                ]);
            }

        }else{

            return redirect()->route('login')->with([
                'error' => 'Sila Masukkan NO. KAD PENGENALAN dan KATA LALUAN yang sah untuk log masuk ke dalam sistem.',
            ]);

        }

    }

    public function logout()
    {
        auth()->logout();
        session()->flush();

        return redirect()->route('login')->with([
            'success' => 'Pengguna telah log Keluar.',
        ]);
    }
}
