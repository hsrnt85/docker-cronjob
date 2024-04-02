<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Officer;
use App\Models\User;
use App\Models\Api\Api_Tenant;
use App\Models\Quarters;

class Api_LoginController extends Controller
{
    public function authForInspection(Request $request) // Apps Penguatkuasa
    {
        $new_ic         = $request->new_ic;
        $password       = $request->password;
        $credentials    = array('new_ic'=>$new_ic, 'password'=>$password,'data_status'=>1);

        try {
            
            if (!auth()->attempt($credentials)) {

                return response()->json([
                    'status'    => false,
                    'message'   => 'NO. KAD PENGENALAN dan KATALALUAN tidak sah untuk log masuk ke dalam sistem',
                    'new_ic'    => $new_ic,
                    'password'  => $password
                ], 401);

            }else{
            
                $userForView = User::getUserForView(auth()->user()->new_ic);  
                $userId = $userForView['id']; 
                $isInspector = Officer::getPegawaiPemantauanByUserId($userId);  

                if(!$isInspector->isEmpty()){
               
                    return response()->json([
                        'status'             => true,
                        'message'            => 'Log masuk berjaya!',
                        'token'              => auth()->user()->createToken('API_TOKEN')->plainTextToken,
                        'user'               => $userForView,
                    ], 200);
                }else{
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Log masuk tidak berjaya!',
                    ], 401);
                }
  
            }

        }catch (\Throwable $th) {

            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);

        }
    }

    public function authForTenants(Request $request) // Apps Penghuni
    {
        $new_ic         = $request->new_ic; 
        $password       = $request->password;
        $credentials    = array('new_ic'=>$new_ic, 'password'=>$password, 'data_status'=>1);
       
        try { 
                          
                $isTenant = Api_Tenant::checkTenantUsingIc($new_ic); 

                if($isTenant){
                    if (!auth()->attempt($credentials)) {  // kata laluan salah

                        return response()->json([
                            'status'    => false,
                            'message'   => 'KATALALUAN tidak sah untuk log masuk ke dalam sistem',
                            'new_ic'    => $new_ic,
                            'password'  => $password
                        ], 401);
                    }
                    else{
                        $item = [];
                        $userForView = User::getUserForView($new_ic); 
                        $quarters = Quarters::getUserProfileKuartersInfo($isTenant->quarters_id);

                        $item['noUnit']             = $quarters->unit_no;
                        $item['address1']           = $quarters->address_1;
                        $item['address2']           = $quarters->address_2;
                        $item['address3']           = $quarters->address_3;
                        $item['district']           = $quarters->category->district->district_name;
                        $item['quarters_category']  = $quarters->category->name;
                        array_push($item);
                
                        return response()->json([
                            'status'             => true,
                            'message'            => 'Log masuk berjaya!',
                            'token'              => auth()->user()->createToken('API_TOKEN')->plainTextToken,
                            'user'               => $userForView,
                            'quarters'           => $item,
                        ], 200);
                    }
                }else{ 
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Hanya Penghuni sahaja yang dibenarkan masuk!',
                    ], 401);
                }

        }catch (\Throwable $th) {

            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);

        }
    
    }
}
