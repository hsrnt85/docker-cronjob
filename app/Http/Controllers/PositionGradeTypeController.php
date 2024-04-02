<?php

namespace App\Http\Controllers;

use App\Models\PositionGradeType;
use Illuminate\Http\Request;
use App\Http\Requests\PositionGradeTypeRequest;
use Illuminate\Support\Facades\DB;

class PositionGradeTypeController extends Controller
{
    public function index()
    {


        $positionGradeTypeAll = PositionGradeType::where('data_status', 1)
                                ->orderBy('grade_type')
                                ->get();

        return view( getFolderPath().'.list',
        [
            'positionGradeTypeAll' => $positionGradeTypeAll
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


    public function store(PositionGradeTypeRequest $request)
    {
        $grade_type = $request->grade_type;

        $check = PositionGradeType::where([
            ['grade_type', '=', $grade_type],
            ['data_status', '=', '1']
        ])->first();

        if($check)
        {
            return redirect()->route('positionGradeType.create')->with('error', 'Kod Jawatan sudah wujud!');

        }

        else{
            $positionGradeType = new PositionGradeType;
            $positionGradeType->grade_type  = $grade_type;
            $positionGradeType->data_status = 1;
            $positionGradeType->action_by   = auth()->user()->id;
            $positionGradeType->action_on   = date('Y-m-d H:i:s');

            $saved = $positionGradeType->save();

            if(!$saved)
            {
                return redirect()->route('positionGradeType.create')->with('error', 'Kod Jawatan tidak berjaya ditambah!');
            }
            else
            {
                return redirect()->route('positionGradeType.index')->with('success', 'Kod Jawatan berjaya ditambah!');
            }
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $positionGradeType = PositionGradeType::findOrFail($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'positionGradeType' => $positionGradeType
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

        $positionGradeType = PositionGradeType::findOrFail($id);

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'positionGradeType' => $positionGradeType
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }


    public function update(PositionGradeTypeRequest $request)
    {
        $id = $request->id;
        $grade_type = $request->grade_type;

        $positionGradeType = PositionGradeType::findOrFail($id);

        $positionGradeType->grade_type  = $request->grade_type;
        $positionGradeType->action_by   = auth()->user()->id;
        $positionGradeType->action_on   = date('Y-m-d H:i:s');

        $saved = $positionGradeType->save();

        if(!$saved)
        {
            return redirect()->route('positionGradeType.edit', ['id'=>$request->id])->with('error', 'Kod jawatan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('positionGradeType.index')->with('success', 'Kod jawatan berjaya dikemaskini!');
        }

    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $positionGradeType = PositionGradeType::findOrFail($id);

            $positionGradeType->data_status       = 0;
            $positionGradeType->delete_by         = auth()->user()->id;
            $positionGradeType->delete_on         = date('Y-m-d H:i:s');
            $positionGradeType->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('positionGradeType.edit', ['id'=>$request->id])->with('error', 'Kod jawatan tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('positionGradeType.index')->with('success', 'Kod jawatan berjaya dihapus!');
    }
}
