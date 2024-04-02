<?php

use App\Models\ConfigSubmenu;
use App\Models\UserActivityLog;

if (! function_exists('saveUserActivity')) {

    function setUserActivity($flag_activity, $record, $data_before="", $data_after="", $reason=""){
        
        //----------------------------------------------------------------------------------------------------------------
        // GET DATA MODULE/SUBMODULE BY ROUTE PREFIX
        //----------------------------------------------------------------------------------------------------------------
        $dataConfigSubmenu = ConfigSubmenu::config_submenu(getRoutePrefix());
        $module_id = $dataConfigSubmenu?->module_id;
        $module_name = $dataConfigSubmenu?->module_name;
        $submodule_id = $dataConfigSubmenu?->submodule_id; 
        $submodule_name = $dataConfigSubmenu?->submodule_name; 
        //----------------------------------------------------------------------------------------------------------------

        //----------------------------------------------------------------------------------------------------------------
        // SET ACTIVITY
        // Process -> A:Add, U:Update, V:View, D:Delete, P:Process...
        //----------------------------------------------------------------------------------------------------------------
        $activity = "";
        if($flag_activity == "IN"){
            $activity = "Log Masuk.";
        }else if($flag_activity == "OUT"){
            $activity = "Log Keluar.";
        }else if($flag_activity == "A"){
            $activity = "Daftar Rekod ".$record;
        }else if($flag_activity == "U"){
            $activity = "Kemaskini Rekod ".$record;
        }else if($flag_activity == "D"){
            $activity = "Hapus Rekod ".$record;
        }else if($flag_activity == "C"){
            $activity = "Batal Rekod ".$record;
        }else if($flag_activity == "P"){
            $activity = "Proses ".$record;
        }

        //----------------------------------------------------------------------------------------------------------------
        //DATA ITEM PROCESSING
        //----------------------------------------------------------------------------------------------------------------
        $data_before_final = "";
        $data_after_final = "";
        if($flag_activity == "U"){
            $data_before = collect($data_before)->toArray();//CONVERT TO ARRAY
            $data_after = collect($data_after)->toArray();//CONVERT TO ARRAY
	
            //GET CHANGES COLUMN DATA
            $data_before_final = checkMultiArrayDiff($data_before, $data_after);
            $data_after_final = checkMultiArrayDiff($data_after, $data_before);

            //SET TO JSON ARRAY
            $data_before_final = setArrayToString($data_before_final);
            $data_after_final = setArrayToString($data_after_final);
        }
        
        //----------------------------------------------------------------------------------------------------------------
        // INSERT ACTIVITY
        //----------------------------------------------------------------------------------------------------------------
        $dataLog = new UserActivityLog();
        $dataLog->district_id = districtId();
        $dataLog->users_id = loginId();
        $dataLog->name = loginData()->name;
        $dataLog->action_on = currentDate();
        $dataLog->module_id = $module_id?? "";
        $dataLog->module_name = $module_name?? "";
        $dataLog->submodule_id = $submodule_id?? "";
        $dataLog->submodule_name = $submodule_name?? "";
        $dataLog->activity = $activity;
        $dataLog->data_before = $data_before_final;
        $dataLog->data_after = $data_after_final;
        if($reason) $dataLog->$reason = $reason;
        $dataLog->save();
        //----------------------------------------------------------------------------------------------------------------
    }

    function checkMultiArrayDiff($arr1, $arr2){
        $arr = array_diff(array_map('serialize', $arr1), array_map('serialize', $arr2));
        $arrFinal = array_map('unserialize', $arr);
        return $arrFinal;
    }

    function setArrayToString( $arr ){

        $removeCol = ["data_status","action_by","action_on","delete_by","delete_on"];
        $separator = "=>";
        $array_str = NULL;
     
		$counter = 0;  
        if( is_array( $arr ) ){
            $arr = array_diff_key($arr, array_flip($removeCol)); 
            $bil = count($arr);
            foreach( $arr as $key => $value ){
                if( is_array( $value ) ){
                    $arrListItem = $value;
                    foreach($arrListItem as $valueListItem){
                        $arrItem = $valueListItem;
                        $counterItem = 0;
                        $bilItem = count($arrItem);
                        $arrItem = array_diff_key($arrItem, array_flip($removeCol));
                        foreach($arrItem as $keyItem => $valueItem){
                            $counterItem++;
                            $array_str .= "'".$keyItem."':'".$valueItem."'";
                            if($counterItem<$bilItem){ $array_str .= $separator; }
                        }
                    }
                }else{ 
                    $counter++;
                    $array_str .= "'".$key."':'".$value."'";
                    if($counter<$bil){ $array_str .= $separator; }
                }
            }
        }    
        
        return $array_str;
    }


}