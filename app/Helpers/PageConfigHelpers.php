<?php

    use Illuminate\Support\Facades\Route;

    function getRoutePrefix(){
        return request()->route()->getPrefix();
    }

    function getPageConfig(){
        $pages = "";
        $routePrefix = getRoutePrefix();
        if($routePrefix){
            $pages = config('pages.'.$routePrefix);
        }
        return $pages;
    }

    function getFolderPath(){
        $folderPath = "";
        if(getPageConfig()!==null){
            $folderPath = getPageConfig()['folder_path'];
        }
        return $folderPath;
    }

    function getLangFile(){
        $lang_file = "";
        if(getPageConfig()!==null){
            $langFile = isset(getPageConfig()['lang_file']) ? getPageConfig()['lang_file'] : $lang_file;
        }
        return $langFile;
    }

    function getBreadcrumbTitle($level=''){
        $pageTitle = "";

        if(getPageConfig()){
            $routeName = Route::currentRouteName();

            if($level==''){ $pageTitle = getPageConfig()['title']; }
            else{
                $initTitle = "";
                if(is_array(getPageConfig()[$routeName])){//nested level
                    $initTitle = isset(getPageConfig()[$routeName]['l'.$level]) ? getPageConfig()[$routeName]['l'.$level] : "";
                }else{
                    $initTitle = getPageConfig()[$routeName];
                }

                if($initTitle) $pageTitle = (isset(getPageConfig()['title_l'.$level])) ? $initTitle.' '.getPageConfig()['title_l'.$level] : '';
            }
        }
        return $pageTitle;
    }

    function getPageTitle($level=''){
        $pageTitle = "";

        if(getPageConfig()){
            $routeName = Route::currentRouteName();

            if($level==''){ $pageTitle = getPageConfig()['title']; }
            else{
                $initTitle = (is_array(getPageConfig()[$routeName])) ? getPageConfig()[$routeName]['l'.$level] : getPageConfig()[$routeName];
                $pageTitle = (isset(getPageConfig()['title_l'.$level])) ? $initTitle.' '.getPageConfig()['title_l'.$level] : '';
            }
        }
        return $pageTitle;
    }

    function getRoute($level=''){
        $pageRoute = "#";
        $routeName = Route::currentRouteName();
        if(getPageConfig()){
            if($level!=''){
                if(is_array(getPageConfig()[$routeName])){//nested level
                    $paramArr = isset(getPageConfig()[$routeName]['data']) ? getPageConfig()[$routeName]['data'] : "";
                    $pageRoute = route(getPageConfig()['route_l'.$level], $paramArr );               
                }else{
                    $pageRoute = route(getPageConfig()['route_l'.$level]);                 
                }                        
            }
        }
        return $pageRoute;
    }

    function setMessage($field){
        $msg = __(getLangFile().'.'.$field);
        return $msg;
    }
    //--------------------------------------------------------------------------------------

    function setModule($menu_id){

        if(isset($menu_id)){

            foreach(Session::get('menu') as $data){//dd($data);
                if($data['menu_id'] == $menu_id){
                    Session::put('menu_id', $data['menu_id']);
                    Session::put('lbl_menu', $data['menu']);
                }

            }
        }
    }

    function setSubmodule(){
        if(!empty(Session::get('submenu'))){

            foreach(Session::get('submenu') as $data){

                Session::put('menu_id', $data['menu_id']);
                Session::put('lbl_menu', $data['menu']);

                if(isset($_GET['smid'])){
                    $submenu_id = $_GET['smid'];
                    Session::put('smid', $submenu_id);
                }
                if(!empty(Session::get('smid'))){

                    $submenu_id = Session::get('smid');
                    if($data['submenu_id'] == $submenu_id){
                        Session::put('lbl_submenu', $data['submenu']);
                        Session::put('route_name', $data['route_name']);
                        Session::put('folder_path', $data['folder_path']);
                        Session::put('lang_file', $data['lang_file']);
                    }
                }

            }

        }

    }

    function getSessionFolderPath(){
        $folder_path = "";
        if (Session::has('folder_path')) {
            $folder_path = Session::get('folder_path');
        }
        return $folder_path;
    }

    function getSessionLangFile(){
        $lang_file = "";
        if (Session::has('lang_file')) {
            $lang_file = Session::get('lang_file');
        }
        return $lang_file;
    }

    function setSessionMessage($field){
        $msg = __(getSessionLangFile().'.'.$field);
        return $msg;
    }
  
    //--------------------------------------------------------------------------------------

    function checkPolicy($ability){

        $submenu_arr = Session::get('submenu');
        $prefix = getRoutePrefix();

        if (isset($submenu_arr[$prefix])){
            return in_array($ability,  explode('-',$submenu_arr[$prefix]['abilities'] )) ? "1" : "";
        }

    }
?>
