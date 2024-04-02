<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {


        $senaraiJabatan = Department::where('data_status', 1)->orderBy('department_name')->get();

        return view(getFolderPath().'.list',
        [
            'senaraiJabatan' => $senaraiJabatan
        ]);
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $jabatan = Department::where('id', $id)->first();


        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'jabatan' => $jabatan
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

}
