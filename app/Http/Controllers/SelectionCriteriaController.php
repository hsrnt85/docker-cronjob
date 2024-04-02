<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SelectionCriteria;
use App\Models\CriteriaCategory;
use App\Models\SelectionSubCriteria;
use App\Http\Requests\SelectionCriteriaPostRequest;
use Illuminate\Support\Facades\DB;


class SelectionCriteriaController extends Controller
{
    public function index()
    {

        $selectionCriteriaAll = CriteriaCategory::join('selection_criteria', 'selection_criteria.c_category_id', '=', 'criteria_category.id')
        ->join('selection_sub_criteria', 'selection_sub_criteria.s_criteria_id', '=', 'selection_criteria.id')
        ->select('criteria_category.criteria_category', 'selection_criteria.criteria', 'selection_sub_criteria.sub_criteria', 'selection_sub_criteria.id as id')
        ->where('selection_sub_criteria.data_status',1)
        ->get();

       return view('modules.ApplicationReview.SelectionCriteria.list', compact('selectionCriteriaAll'));
    }

    public function create()
    {
        $selectionCriteriaAll = CriteriaCategory::where('data_status', 1)->get();

        return view('modules.ApplicationReview.SelectionCriteria.create', [
            'selectionCriteriaAll' => $selectionCriteriaAll
        ]); 
    }

    public function ajaxGetUser(Request $request)
    {
        // $user = User::where($request->id);
        $id = $request->id;
        $criteria = SelectionCriteria::whereHas('category', function($q) use($id){
            $q->where('id', $id);
        })->get();

        if($criteria->count() == 0)
        {
            return response()->json(['error' => 'Tiada Kriteria'], 404);
        }

        return response()->json(['data'=>$criteria], 201);
    }


    public function store(SelectionCriteriaPostRequest $request)
    {
        $selectionCriteria = new SelectionSubCriteria;

        $selectionCriteria->s_criteria_id = $request->criteria;
        $selectionCriteria->sub_criteria  = $request->statement;
        $selectionCriteria->mark          = $request->marks;
        $selectionCriteria->data_status   = 1;
        $selectionCriteria->action_by     = auth()->user()->id;
        $selectionCriteria->action_on     = date('Y-m-d H:i:s');
        
        $saved = $selectionCriteria->save();

        if(!$saved)
        {
            return redirect()->route('selectionCriteria.create')->with('error', 'Kriteria Pemarkahan tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('selectionCriteria.index')->with('success', 'Kriteria Pemarkahan berjaya ditambah!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        
        $selectionCriteria = SelectionSubCriteria::findOrFail($id);

        
        return view('modules.ApplicationReview.SelectionCriteria.edit',
        [
            'selectionCriteria' => $selectionCriteria,
        ]); 
    }

    public function update(SelectionCriteriaPostRequest $request)
    {
        DB::beginTransaction();

        try {
            $selectionCriteria = SelectionSubCriteria::findOrFail($request->id);

            $selectionCriteria->sub_criteria   = $request->statement;
            $selectionCriteria->mark           = $request->marks;
            $selectionCriteria->action_by      = auth()->user()->id;
            $selectionCriteria->action_on      = date('Y-m-d H:i:s');

            $selectionCriteria->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            // something went wrong
            return redirect()->route('selectionCriteria.edit', ['id' => $selectionCriteria->id])->with('error', 'Kriteria Pemarkahan tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
        
        return redirect()->route('selectionCriteria.index')->with('success', 'Kriteria Pemarkahan berjaya dikemaskini! ');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        DB::beginTransaction();

        try {
            $selectionCriteria = SelectionSubCriteria::find($id);

            $selectionCriteria->data_status       = 0;
            $selectionCriteria->delete_by         = auth()->user()->id;
            $selectionCriteria->delete_on         = date('Y-m-d H:i:s');
            $selectionCriteria->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('selectionCriteria.index')->with('error', 'Kriteria Pemarkahan tidak berjaya dipadam!' . ' ' . $e->getMessage());
        }
        
        return redirect()->route('selectionCriteria.index')->with('success', 'Kriteria Pemarkahan berjaya dipadam!');
    }

    public function view(Request $request)
    {
        $id = $request->id;
        
        $selectionCriteria = SelectionSubCriteria::findOrFail($id);

        
        return view('modules.ApplicationReview.SelectionCriteria.view',
        [
            'selectionCriteria' => $selectionCriteria,
        ]); 
    }

}
