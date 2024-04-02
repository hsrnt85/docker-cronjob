<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


use App\Models\UserPolicyAbilities;
use App\Models\ConfigMenu;
use App\Models\ConfigSubmenu;
use App\Models\Permission;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Permission::set_menu();

        return view('dashboard');
    }

    public function submenu(Request $request)
    {
        $config_menu_id = $request->mid;

        $flag_dashboard = Permission::set_submenu($config_menu_id);

        setModule($config_menu_id);

        if($flag_dashboard == 1){
            return view('dashboard');
        }else{
            return view('subModule');
        }

    }

    public function dashboard()
    {

        $config_menu_id = 1;
        Permission::set_submenu($config_menu_id);

        return view('dashboard');
    }

}
