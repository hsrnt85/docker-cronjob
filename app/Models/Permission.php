<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Models\RolesAbilities;
use App\Models\ConfigMenu;
use App\Models\ConfigSubmenu;

class Permission extends Model
{

    public static function set_menu(){

        if (Session::has('menu')) {
            Session::forget('menu');
            Session::forget('menu_id');
            Session::forget('lbl_menu');
        }

        $menu_arr = array();
        $menus = RolesAbilities::join('config_menu','id','=','roles_abilities.config_menu_id')
                    ->where(['data_status'=> 1,'roles_id'=> loginData()->roles_id])
                    ->groupBy('config_menu_id')->orderBy('order')->get();

        foreach ($menus as $data){

            $configMenu = ConfigMenu::find($data->config_menu_id);

            $menu_arr[] = array(
                'menu_id' => $data->config_menu_id,
                'menu' => $configMenu->menu,
                'flag_dashboard' => $configMenu->flag_dashboard
            );

        }

        Session::put('menu', $menu_arr);

    }

    public static function set_submenu($config_menu_id)
    {

        if (Session::has('submenu')) {
            Session::forget('lbl_menu');
            Session::forget('submenu');
            Session::forget('smid');
            Session::forget('lbl_submenu');
            Session::forget('route_name');
            Session::forget('folder_path');
            Session::forget('lang_file');
            Session::forget('abilities');
        }

        $user_login_id = loginData()->roles_id;

        $submenu_arr = array();

        $configMenu = ConfigMenu::where(['data_status' => 1, 'id' => $config_menu_id])->first();
        $configSubmenu = ConfigSubmenu::where(['data_status' => 1, 'config_menu_id' => $config_menu_id])->orderBy('order','ASC')->get();

        foreach ($configSubmenu as $data){

            $config_submenu_id = $data->id;
            $submenu = $data->submenu;
            $route_name = $data->route_name;
            $folder_path = $data->folder_path;
            $lang_file = $data->lang_file;

            $rolesAbilities = RolesAbilities::select('abilities')->where(['roles_id' => $user_login_id,'config_submenu_id' => $config_submenu_id])->first();

            if($rolesAbilities){

                $submenu_arr[$data->route_prefix] = array(
                    'menu_id' => $data->config_menu_id,
                    'menu' => $configMenu->menu,
                    'submenu_id' => $config_submenu_id,
                    'submenu' => $submenu,
                    'route_name' => $route_name,
                    'folder_path' => $folder_path,
                    'lang_file' => $lang_file,
                    'abilities' => $rolesAbilities->abilities
                );
            }


        }

        Session::put('submenu', $submenu_arr);

        return $configMenu->flag_dashboard;

    }

}
