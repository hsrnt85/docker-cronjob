<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\HTTP\Requests\PositionRequest;

class PositionController extends Controller
{
    public function index()
    {


        $senaraiJawatan = Position::where('data_status', 1)->orderBy('position_name')->get();

        return view(getFolderPath().'.list',
        [
            'senaraiJawatan' => $senaraiJawatan
        ]);
    }

    public function create()
    {
        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create');
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(PositionRequest $request)
    {
        $position_name = $request->position_name;

        $position = new Position;
        $position->position_name = $position_name;
        $position->data_status   = 1;
        $position->action_by     = loginId();
        $position->action_on     = currentDate();

        $saved = $position->save();

        if(!$saved)
        {
            return redirect()->route('position.create')->with('error', 'Jawatan tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('position.index')->with('success', 'Jawatan berjaya ditambah!');
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $position = Position::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
            [
                'position' => $position
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(PositionRequest $request)
    {
        $position_name = $request->position_name;
        $id = $request->id;

        $position = Position::findOrFail($id);

        $position->position_name = $position_name;
        $position->data_status   = 1;
        $position->action_by     = loginId();
        $position->action_on     = currentDate();

        $saved = $position->save();

        if(!$saved)
        {
            return redirect()->route('position.edit', ['id'=>$id])->with('error', 'Jawatan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('position.index')->with('success', 'Jawatan berjaya dikemaskini!');
        }

    }

    public function destroy(Request $request)
    {
        $position = Position::where('id', $request->id)->first();

        $position->data_status  = 0;
        $position->delete_by    = loginId();
        $position->delete_on    = currentDate();

        $deleted = $position->save();

        if(!$deleted)
        {
            return redirect()->route('position.edit', ['id'=>$request->id])->with('error', 'Jawatan tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('position.index')->with('success', 'Jawatan berjaya dihapus!');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $position = Position::where('id', $id)->first();


        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'position' => $position
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

}
