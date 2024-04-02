<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;

class AgencyController extends Controller
{
    public function index()
    {


        $senaraiAgensi = Agency::where('data_status', 1)->orderBy('name')->get();

        return view(getFolderPath().'.list',
        [
            'senaraiAgensi' => $senaraiAgensi
        ]);
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $agensi = Agency::where('id', $id)->first();

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'agensi' => $agensi
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $agensi = Agency::where('id', $id)->first();

        if (checkPolicy("U")) {
            return view(getFolderPath() . '.edit', compact('agensi'));
        } else {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }
    }

    public function update(Request $request)
    {
    
        try {
            
            $id = $request->id;
            $agensi = Agency::where('id', $id)->first();

            $data_before = $agensi->toArray();

            $code = $request->code;

            $agensi->code = $code;
            $agensi->action_by = loginId();
            $agensi->action_on = currentDate();

            // Save the updated bank account record
            $updated = $agensi->save();

            $data_after = $agensi->toArray(); //get data after

            setUserActivity("U", $agensi->name, $data_before, $data_after);
    
            if ($updated) {
                return redirect()->route('agency.index')->with('success', 'Maklumat Agensi berjaya dikemaskini!');
            } else {
                return redirect()->route('agency.edit', ['id' => $id])->with('error', 'Maklumat Agensi tidak berjaya dikemaskini!');
            }
        } catch (\Exception $e) {
            // Something went wrong
            return redirect()->route('agency.edit', ['id' => $id])->with('error', 'Maklumat Agensi tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
    }
}
