<?php

namespace App\Http\Controllers;

use App\Models\DistrictManagement;
use App\Http\Resources\ListData;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\DistrictManagementPostRequest;
use Illuminate\Support\Facades\DB;

class DistrictManagementController extends Controller
{
    //
    public function index()
    {
        $districtManagementAll = DistrictManagement::where('data_status', 1)->get();

        return view(getFolderPath().'.list',
        [
            'districtManagementAll' => $districtManagementAll
        ]);
    }


    public function create()
    {
        $districtAll =ListData::District();

        return view(getFolderPath().'.create', [
            'districtAll' => $districtAll
        ]);
    }

    public function store(DistrictManagementPostRequest $request)
    {
        DB::beginTransaction();

        try {
            $districtManagement = new DistrictManagement;
            $districtManagement->district_id    = $request->district;
            $districtManagement->users_id       = $request->user;
            $districtManagement->data_status    = 1;
            $districtManagement->action_by      = loginId();
            $districtManagement->action_on      = currentDate();

            $districtManagement->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('districtManagement.create')->with('error', 'Pengurusan daerah tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

        return redirect()->route('districtManagement.index')->with('success', 'Pengurusan daerah berjaya ditambah! ');
    }


    public function edit(Request $request)
    {
        $id = $request->id;

        $districtManagement = DistrictManagement::findOrFail($id);

        $districtAll =ListData::District();

        $userAll = User::whereHas('office', function($q) use($districtManagement){
            $q->where('district_id', $districtManagement->district_id);
        })->get();

        return view(getFolderPath().'.edit',
        [
            'districtManagement' => $districtManagement,
            'districtAll' => $districtAll,
            'userAll' => $userAll
        ]);
    }


    public function update(DistrictManagementPostRequest $request)
    {
        DB::beginTransaction();

        try {
            $districtManagement = DistrictManagement::findOrFail($request->id);

            $districtManagement->district_id    = $request->district;
            $districtManagement->users_id       = $request->user;
            $districtManagement->action_by      = loginId();
            $districtManagement->action_on      = currentDate();

            $districtManagement->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('districtManagement.edit', ['id' => $districtManagement->id])->with('error', 'Pengurusan daerah tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('districtManagement.index')->with('success', 'Pengurusan daerah berjaya dikemaskini! ');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $districtManagement = DistrictManagement::findOrFail($id);

            $districtManagement->data_status       = 0;
            $districtManagement->delete_by         = loginId();
            $districtManagement->delete_on         = currentDate();
            $districtManagement->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('districtManagement.index')->with('error', 'Pengurusan daerah tidak berjaya dipadam!' . ' ' . $e->getMessage());
        }

        return redirect()->route('districtManagement.index')->with('success', 'Pengurusan daerah berjaya dipadam!');
    }


    public function view(Request $request)
    {
        $id = $request->id;

        $districtManagement = DistrictManagement::findOrFail($id);

        return view(getFolderPath().'.view',
        [
            'districtManagement' => $districtManagement
        ]);
    }

    //  AJAX
    public function ajaxGetUser(Request $request)
    {
        $id = $request->id;

        $users = User::whereHas('office', function($q) use($id){
            $q->where('district_id', $id);
        })
        ->get();

        if($users->count() == 0)
        {
            return response()->json(['error' => 'Tiada pegawai'], 404);
        }

        return response()->json(['data'=>$users], 201);
    }

    public function ajaxGetUserData(Request $request)
    {
        $id     = $request->id;
        $user   = User::find($id);

        $data['jawatan'] = $user->position->position_name;
        $data['jabatan'] = $user->office->organization->name;
        $data['alamat1'] = $user->office->address_1;
        $data['alamat2'] = $user->office->address_2;
        $data['alamat3'] = $user->office->address_3;
        $data['no_tel']  = $user->office->phone_no_office;
        $data['email']   = $user->email;


        if($user->count() == 0)
        {
            return response()->json(['error' => 'Tiada pegawai'], 404);
        }

        return response()->json([
            'data' => $data
        ], 201);
    }

}
