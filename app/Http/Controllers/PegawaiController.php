<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Officer;
use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use App\Models\Position;
use App\Models\PositionGrade;
use App\Models\User;
use App\Http\Requests\PegawaiPostRequest;

use Illuminate\Support\Facades\DB;


class PegawaiController extends Controller
{
    public function index()
    {


        $officerAll = Officer::where('data_status', 1)->get();

        return view( getFolderPath().'.list',
        [
            'officerAll' => $officerAll
        ]);
    }


    public function create()
    {
        $userAll = User::where('data_status', 1)->get();
        $districtAll =ListData::District();
        $officerCategoryAll = ListData::OfficerCategory();

        return view( getFolderPath().'.create',[
            'userAll' => $userAll,
            'districtAll' => $districtAll,
            'officerCategoryAll' => $officerCategoryAll
        ]);
    }


    public function store(PegawaiPostRequest $request)
    {
        $user = User::findOrFail($request->pegawai);

        DB::beginTransaction();

        try {
            $officer = new Officer;
            $officer->district_id               = $request->district;
            $officer->officer_category_id       = $request->officer_category;
            $officer->data_status               = 1;
            $officer->action_by                 = loginId();
            $officer->action_on                 = currentDate();
            $officer->user()->associate($user);

            $saved = $officer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('officer.create')->with('error', 'Pegawai tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officer.index')->with('success', 'Pegawai berjaya ditambah! ' . $saved);
    }


    public function edit(Request $request)
    {
        $userAll = User::where('data_status', 1)->get();
        $districtAll =ListData::District();
        $officerCategoryAll = ListData::OfficerCategory();

        $id = $request->id;

        $officer = Officer::findOrFail($id);

        return view( getFolderPath().'.edit',
        [
            'userAll' => $userAll,
            'districtAll' => $districtAll,
            'officerCategoryAll' => $officerCategoryAll,
            'officer' => $officer
        ]);
    }


    public function update(PegawaiPostRequest $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $officer = Officer::findOrFail($id);

            $officer->district_id           = $request->district;
            $officer->officer_category_id   = $request->officer_category;
            $officer->action_by             = loginId();
            $officer->action_on             = currentDate();

            $saved = $officer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('officer.edit', ['id'=>$request->id])->with('error', 'Pegawai tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officer.index')->with('success', 'Pegawai berjaya dikemaskini!');
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $officer = Officer::findOrFail($request->id);
            $officer->data_status   = 0;
            $officer->delete_by     = loginId();
            $officer->delete_on     = currentDate();
            $officer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('officer.edit', ['id'=>$request->id])->with('error', 'Pegawai tidak berjaya dipadam!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officer.index')->with('success', 'Pegawai berjaya dipadam!');
    }
}
