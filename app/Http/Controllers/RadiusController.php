<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radius;
use App\Http\Requests\RadiusRequest;

class RadiusController extends Controller
{
    public function index()
    {


        $senaraiRadius = Radius::where('data_status', 1)->orderBy('date_start')->orderBy('radius')->get();

        return view( getFolderPath().'.list',

        [
            'senaraiRadius' => $senaraiRadius
        ]);
    }

    public function create()
    {
        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create');
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(RadiusRequest $request)
    {
        $radius = new Radius;

        $radius->radius       = $request->radius;
        $radius->date_start   = $request->date;
        $radius->action_by    = loginId();
        $radius->action_on    = currentDate();

        $saved = $radius->save();

        //------------------------------------------------------------------------------------------------------------------
        // Save User Activity
        //------------------------------------------------------------------------------------------------------------------
        setUserActivity("A", $radius->radius);
        //------------------------------------------------------------------------------------------------------------------

        if(!$saved)
        {
            return redirect()->route('radius.create')->with('error', 'Radius tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('radius.index')->with('success', 'Radius berjaya ditambah!');
        }

    }

    public function view(Request $request)
    {
        $id = $request->id;
        $radius = Radius::where('id', $id)->first();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'radius' => $radius
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
        $radius = Radius::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'radius' => $radius
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(RadiusRequest $request)
    {
        $radius = Radius::where('id', $request->id)->first();

        // User Activity - Set Data before changes
        $data_before = $radius->getRawOriginal();//dd($data_before);

        $radius->radius       = $request -> radius;
        $radius->date_start   = $request -> date;
        $radius->action_by    = loginId();
        $radius->action_on    = currentDate();

        $saved = $radius->save();

        
        // User Activity - Set Data after changes
        $data_after = $radius->toArray();
        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);
        
        // User Activity - Save
        setUserActivity("U", $radius->radius, $data_before_json, $data_after_json);
        
        if(!$saved)
        {
            return redirect()->route('radius.edit', ['id'=>$request->id])->with('error', 'Radius tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('radius.index')->with('success', 'Radius berjaya dikemaskini!');
        }

    }

    public function destroy(Request $request)
    {
        $radius = Radius::where('id', $request->id)->first();

        setUserActivity("D", $radius->radius);

        $radius->data_status  = 0;
        $radius->delete_by    = loginId();
        $radius->delete_on    = currentDate();

        $deleted = $radius->save();

        //$deleted = Radius::find($request->id)->delete();

        if(!$deleted)
        {
            return redirect()->route('radius.edit', ['id'=>$request->id])->with('error', 'Radius tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('radius.index')->with('success', 'Radius berjaya dihapus!');
        }
    }

}
