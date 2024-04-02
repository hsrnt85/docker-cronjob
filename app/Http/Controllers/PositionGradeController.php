<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListData;
use App\Models\PositionGrade;
use App\Models\PositionGradeType;
use Illuminate\Http\Request;
use App\Http\Requests\PositionGradeRequest;
use Illuminate\Support\Facades\DB;

class PositionGradeController extends Controller
{
    public function index()
    {


        $positionGradeAll = PositionGrade::where('data_status', 1)->orderBy(DB::raw('CAST(grade_no AS unsigned)'))->get();

        return view( getFolderPath().'.list',
        [
            'positionGradeAll' => $positionGradeAll
        ]);
    }


    public function create()
    {
        $positionGradeTypeAll = ListData::PositionGradeType();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create', [
                'positionGradeTypeAll' => $positionGradeTypeAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function store(PositionGradeRequest $request)
    {
        $grade_no = $request->grade_no;

        $positionGrade = new PositionGrade;
        $positionGrade->grade_no    = $grade_no;
        $positionGrade->action_by   = loginId();
        $positionGrade->action_on   = currentDate();

        $saved = $positionGrade->save();

        if(!$saved)
        {
            return redirect()->route('positionGrade.create')->with('error', 'Gred Jawatan tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('positionGrade.index')->with('success', 'Gred Jawatan berjaya ditambah!');
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $positionGrade = PositionGrade::findOrFail($id);

        $positionGradeTypeAll = PositionGradeType::where('data_status', 1)->get();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'positionGrade' => $positionGrade,
                'positionGradeTypeAll' => $positionGradeTypeAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function update(PositionGradeRequest $request)
    {
        $id = $request->id;
        $grade_no = $request->grade_no;

        $positionGrade = PositionGrade::findOrFail($id);

        $positionGrade->grade_no    = $grade_no;
        $positionGrade->action_by   = loginId();
        $positionGrade->action_on   =  currentDate();

        $saved = $positionGrade->save();

        if(!$saved)
        {
            return redirect()->route('positionGrade.edit', ['id'=>$id])->with('error', 'Gred Jawatan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('positionGrade.index')->with('success', 'Gred Jawatan berjaya dikemaskini!');
        }

    }


    public function view(Request $request)
    {
        $id = $request->id;

        $positionGrade = PositionGrade::findOrFail($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'positionGrade' => $positionGrade,
            ]);

        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $positionGrade = PositionGrade::findOrFail($id);

            $positionGrade->data_status       = 0;
            $positionGrade->delete_by         = loginId();
            $positionGrade->delete_on         =  currentDate();
            $positionGrade->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('positionGrade.edit', ['id'=>$request->id])->with('error', 'Gred jawatan tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('positionGrade.index')->with('success', 'Gred jawatan berjaya dihapus!');
    }
}
