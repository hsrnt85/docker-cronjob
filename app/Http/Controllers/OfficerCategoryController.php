<?php

namespace App\Http\Controllers;

use App\Models\OfficerCategory;
use Illuminate\Http\Request;
use App\Http\Requests\OfficerCategoryPostRequest;
use Illuminate\Support\Facades\DB;


class OfficerCategoryController extends Controller
{
    public function index()
    {
        $officerCategoryAll = OfficerCategory::where('data_status', 1)->get();

        return view('modules.SystemConfiguration.OfficerCategory.list',
        [
            'officerCategoryAll' => $officerCategoryAll
        ]);
    }


    public function create()
    {
        return view('modules.SystemConfiguration.OfficerCategory.create');
    }


    public function store(OfficerCategoryPostRequest $request)
    {
        DB::beginTransaction();

        try {
            $officerCategory = new OfficerCategory;
            $officerCategory->category_name     = $request->category_name;
            $officerCategory->data_status       = 1;
            $officerCategory->action_by         = loginId();
            $officerCategory->action_on         = currentDate();

            $officerCategory->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('officerCategory.create')->with('error', 'Kategori pegawai tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officerCategory.index')->with('success', 'Kategori pegawai berjaya ditambah! ');
    }


    public function edit(Request $request)
    {
        $id = $request->id;

        $officerCategory = OfficerCategory::findOrFail($id);

        return view('modules.SystemConfiguration.OfficerCategory.edit',
        [
            'officerCategory' => $officerCategory
        ]);
    }


    public function update(OfficerCategoryPostRequest $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $officerCategory = OfficerCategory::findOrFail($id);

            $officerCategory->category_name     = $request->category_name;
            $officerCategory->data_status       = 1;
            $officerCategory->action_by         = loginId();
            $officerCategory->action_on         = currentDate();
            $officerCategory->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('officerCategory.edit', ['id'=>$request->id])->with('error', 'Kategori pegawai tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officerCategory.index')->with('success', 'Kategori pegawai berjaya dikemaskini!');
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $officerCategory = OfficerCategory::findOrFail($request->id);

            $officerCategory->data_status       = 0;
            $officerCategory->delete_by         = loginId();
            $officerCategory->delete_on         = currentDate();
            $officerCategory->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('officerCategory.edit', ['id'=>$request->id])->with('error', 'Kategori pegawai tidak berjaya dipadam!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officerCategory.index')->with('success', 'Kategori pegawai berjaya dipadam!');
    }
}
