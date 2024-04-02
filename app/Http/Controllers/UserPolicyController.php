<?php

namespace App\Http\Controllers;

use App\Models\ConfigMenu;
use App\Models\ConfigSubmenu;
use App\Models\Roles;
use App\Models\RolesAbilities;
use App\Models\User;
use App\Models\UserPolicy;
use Illuminate\Http\Request;
use App\Http\Requests\UserPolicyRequest;
use App\Http\Resources\ListValidateDelete;
use Illuminate\Support\Facades\Session;

class UserPolicyController extends Controller
{

    public function index()
    {


        $roles = Roles::where('data_status', 1)->get();

        return view(getFolderPath().'.list',
        [
            'roles' => $roles
        ]);

    }

    public function create()
    {

        $rolesAbilities = RolesAbilities::select('config_menu_id','config_submenu_id','abilities')->get();
        $configMenu = ConfigMenu::select('id','menu')->where('data_status', 1)->orderBy('order')->get();
        $configSubmenu = ConfigSubmenu::select('id','config_menu_id','submenu','action')
            ->where('data_status', 1)
            ->whereNotNull('action')
            ->orderBy('config_menu_id', 'asc')
            ->orderBy('order', 'asc')
            ->get();
        $user = User::where(['data_status'=> 1, 'flag'=>1])->get();//'roles_id'=> '0',

        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create',
            [
                'rolesAbilities' => $rolesAbilities,
                'configMenu' => $configMenu,
                'configSubmenu' => $configSubmenu,
                'user' => $user,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(UserPolicyRequest $request)
    {
        $name  = $request->name;

        //SAVE ROLES
        $roles = new Roles;
        $roles->name = $name;
        $roles->is_district = isset($request->is_district) ? 1 : 0;
        $roles->action_by = loginId();
        $roles->action_on = currentDate();
        $saved = $roles->save();

        //SAVE ROLES ABILITIES
        $total_record = $request->total_record;

        for($i=0;$i<$total_record;$i++){

            $rolesAbilities = new RolesAbilities;

            $idArr = $request->input('ids'.$i) != null ? explode('-', $request->input('ids'.$i)) : "";
            $menu_id = $idArr[0];
            $submenu_id = $idArr[1];

            $rolesAbilities->roles_id = $roles->id;
            $rolesAbilities->config_menu_id = $menu_id;
            $rolesAbilities->config_submenu_id = $submenu_id;
            $rolesAbilities->abilities = $request->input('abilities'.$i) != null ? implode('-', $request->input('abilities'.$i)) : "";

            $saved = $rolesAbilities->save();

        }

        //SAVE ABILTIES
        $total_record = $request->total_record;
        for($i=0;$i<$total_record;$i++){

            $rolesAbilities = new RolesAbilities;

            $idArr = $request->input('ids'.$i) != null ? explode('-', $request->input('ids'.$i)) : "";
            $menu_id = $idArr[0];
            $submenu_id = $idArr[1];

            $abilities = $request->input('abilities'.$i) != null ? implode('-', $request->input('abilities'.$i)) : "";

            if($abilities != null && $abilities !=""){
                $rolesAbilities->roles_id = $roles->id;
                $rolesAbilities->config_menu_id = $menu_id;
                $rolesAbilities->config_submenu_id = $submenu_id;
                $rolesAbilities->abilities = $abilities;

                $saved = $rolesAbilities->save();
            }

        }

        //SAVE USER
        $users_ids = $request->users_ids;
        if($users_ids){

            foreach($users_ids as $users_id){

                $user = User::select('id')->where('id', $users_id)->first();

                $user->roles_id = $roles->id;
                $user->action_by = loginId();
                $user->action_on = currentDate();
                $user->save();

            }
        }

        // Save User Activity
        setUserActivity("A", $name);
        


        if(!$saved)
        {
            return redirect()->route('userPolicy.create')->with('error', 'Pendaftaran Peranan tidak berjaya!');
        }
        else
        {
            return redirect()->route('userPolicy.index')->with('success', 'Pendaftaran Peranan berjaya.');
        }
    }

    public function edit(Request $request)
    {
        $roles_id = $request->id;
        $roles = Roles::where('id', $roles_id)->first();
        $rolesAbilities = RolesAbilities::select('config_menu_id','config_submenu_id','abilities')->where('roles_id', $roles_id)->get();
        $configMenu = ConfigMenu::select('id','menu')->where('data_status', 1)->orderBy('order')->get();
        $configSubmenu = ConfigSubmenu::select('id','config_menu_id','submenu','action')
            ->where('data_status', 1)
            ->whereNotNull('action')
            ->orderBy('config_menu_id', 'asc')
            ->orderBy('order', 'asc')
            ->get();
        $user = User::where(['data_status'=> 1,'flag'=>1])->get();

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
        [
            'roles' => $roles,
            'rolesAbilities' => $rolesAbilities,
            'configMenu' => $configMenu,
            'configSubmenu' => $configSubmenu,
            'user' => $user,
        ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(UserPolicyRequest $request)
    {
        $roles_id = $request->id;
        $roles = Roles::select('id')->where('id', $roles_id)->first();

        // User Activity - Set Data before changes
        $data_before = $roles->getRawOriginal();
        $data_before['item'] = $roles->toArray();

        $name = $request->name;
        $roles->name = $name;
        $roles->is_district = isset($request->is_district) ? 1 : 0;
        $roles->action_by = loginId();
        $roles->action_on = currentDate();
        $roles->save();

        RolesAbilities::where('roles_id', $roles_id)->delete();

        //SAVE USER ABILTIES
        $total_record = $request->total_record;
        for($i=0;$i<$total_record;$i++){

            $rolesAbilities = new RolesAbilities;

            $idArr = $request->input('ids'.$i) != null ? explode('-', $request->input('ids'.$i)) : "";
            $menu_id = $idArr[0];
            $submenu_id = $idArr[1];
            $abilities = $request->input('abilities'.$i) != null ? implode('-', $request->input('abilities'.$i)) : "";

            if($abilities != null && $abilities !=""){
                $rolesAbilities->roles_id = $roles->id;
                $rolesAbilities->config_menu_id = $menu_id;
                $rolesAbilities->config_submenu_id = $submenu_id;
                $rolesAbilities->abilities = $abilities;

                $rolesAbilities->save();
            }


        }

        //SAVE USER
        $users_ids = $request->users_ids;
        if($users_ids){

            foreach($users_ids as $users_id){

                $user = User::select('id')->where('id', $users_id)->first();

                $user->roles_id = $roles_id;
                $user->action_by = loginId();
                $user->action_on = currentDate();
                $user->save();

            }
        }

        // User Activity - Set Data after changes
        $data_after = $roles;
        $data_after['item'] = $roles->toArray();

        $data_before_final = json_encode($data_before);
        $data_after_final = json_encode($data_after);

        // User Activity - Save with data before and after changes
        setUserActivity("U", $name, $data_before_final, $data_after_final);

        return redirect()->route('userPolicy.index')->with('success', 'Peranan telah dikemaskini!');

    }

    public function view(Request $request)
    {
        $roles_id = $request->id;
        $roles = Roles::where('id', $roles_id)->first();
        $rolesAbilities = RolesAbilities::select('config_menu_id','config_submenu_id','abilities')->where('roles_id', $roles_id)->get();
        $configMenu = ConfigMenu::select('id','menu')->where('data_status', 1)->orderBy('order')->get();
        $configSubmenu = ConfigSubmenu::select('id','config_menu_id','submenu','action')->where('data_status', 1)->whereNotNull('action')->orderBy('config_menu_id', 'asc')->get();
        $user = User::where(['data_status'=> 1, 'roles_id'=>$roles_id])->get();

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'roles' => $roles,
                'rolesAbilities' => $rolesAbilities,
                'configMenu' => $configMenu,
                'configSubmenu' => $configSubmenu,
                'user' => $user,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function destroy(Request $request)
    {
        $roles_id = $request->id;
        $roles = Roles::where('id', $roles_id)->first();
        $name = $roles->name;

        setUserActivity("D", $name);

        $roles->data_status  = 0;
        $roles->delete_by    = loginId();
        $roles->delete_on    = currentDate();
        $deleted = $roles->save();

        RolesAbilities::where('roles_id', $roles->id)->delete();

        if(!$deleted)
        {
            return redirect()->route('userPolicy.index', ['id'=>$request->id])->with('error', 'Peranan tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('userPolicy.index')->with('success', 'Peranan telah dihapus!');
        }
    }


    public function ajaxGetSubmenu(Request $request){

        $config_menu_id = $request->id;
        $configSubmenu = ConfigSubmenu::select('id','submenu','action')->where(['data_status'=> 1, 'config_menu_id'=>$config_menu_id])->get();

        return response()->json($configSubmenu);
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateUserPolicy($id);
        return response()->json($data);
    }
    


}
