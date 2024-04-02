<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use App\Models\Officer;
use App\Http\Requests\OfficerRequest;
use App\Http\Resources\ListValidateDelete;
use Illuminate\Support\Facades\DB;

class OfficerController extends Controller
{
    public function index()
    {

        if(is_all_district()){
            $officerAll = Officer::join('users','officer.users_id','=','users.id')
                ->where('officer.data_status', 1)
                ->select('officer.*', 'users.name')
                ->orderBy('users.name')
                ->get();
        }
        else{
            $officerAll = Officer::join('users','officer.users_id','=','users.id')
                ->where([
                        ['officer.data_status', 1],
                        ['officer.district_id', districtId()]
                    ])
                ->select('officer.*', 'users.name')
                ->orderBy('users.name')
                ->get();
        }

        $officerCategoryAll = ListData::OfficerCategory();

        return view( getFolderPath().'.list',
        [
            'officerAll' => $officerAll,
            'officerCategoryAll' => $officerCategoryAll
        ]);
    }

    public function create()
    {

        $officerAll = ListData::User();
        $districtAll = ListData::District();
        $officerCategoryAll = ListData::OfficerCategory();
        $userOffice = getData::UserOffice( loginId() );
        $userAll = ListData::User( $userOffice->district_id );
        $officerGroupAll = ListData::OfficerGroup();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'officerAll' => $officerAll,
                'districtAll' => $districtAll,
                'officerCategoryAll' => $officerCategoryAll,
                'userOffice' => $userOffice,
                'userAll' => $userAll,
                'officerGroupAll' => $officerGroupAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(OfficerRequest $request)
    {

        try {
            $officer_category_id                = $request->input('officer_category') != null ? implode(',', $request->input('officer_category')) : "";

            $officer = new Officer;
            $officer->users_id                  = $request->officer;
            $officer->district_id               = $request->district;
            $officer->officer_group_id          = $request->officer_group;
            $officer->officer_category_id       = $officer_category_id;
            $officer->monitoring_district       = isset($request->monitoring_district) ? 1 : 0;
            $officer->action_by                 = loginId();
            $officer->action_on                 = currentDate();

            $saved = $officer->save();

            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $officer->user?->name);

            if($saved) return redirect()->route('officer.index')->with('success', 'Pegawai berjaya ditambah! ');

        } catch (\Exception $e) {

            // something went wrong
            return redirect()->route('officer.create')->with('error', 'Pegawai tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $officer = Officer::where('id', $id)->first();
        $userOffice = getData::UserOffice( loginId() );
        $userAll = ListData::User($userOffice->district_id);
        $districtAll =ListData::District();
        $officerCategoryAll = ListData::OfficerCategory();
        $officerGroupAll = ListData::OfficerGroup();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'userAll' => $userAll,
                'districtAll' => $districtAll,
                'officerCategoryAll' => $officerCategoryAll,
                'officer' => $officer,
                'officerGroupAll' => $officerGroupAll,
            ]);

        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(OfficerRequest $request)
    {
        $id = $request->id;

        try {
            $officer = Officer::findOrFail($id);
            
            $data_before = $officer->getRawOriginal();

            $officer->officer_group_id      = $request->officer_group;
            $officer_category_id            = $request->input('officer_category') != null ? implode(',', $request->input('officer_category')) : "";
            $officer->officer_category_id   = $officer_category_id;
            $officer->monitoring_district   = $request->monitoring_district;
            $officer->action_by             = loginId();
            $officer->action_on             = currentDate();
            $updated = $officer->save();

            $data_after = $officer->toArray();

            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);
    
            if ($updated) {
                setUserActivity("U", $officer->user?->name, $data_before_json, $data_after_json);
                return redirect()->route('officer.index')->with('success', 'Pegawai berjaya dikemaskini!');
            }
    
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('officer.edit', ['id'=>$request->id])->with('error', 'Pegawai tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

    }

    public function view(Request $request)
    {
        $id = $request->id;

        $officer = Officer::where('id', $id)->first();
        $officer_category_id_arr = stringToArray($officer->officer_category_id, ',');
        $officerCategoryAll = ListData::OfficerCategoryInSet($officer_category_id_arr);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'officer' => $officer,
                'officerCategoryAll' => $officerCategoryAll
            ]);

        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $officer = Officer::findOrFail($request->id);
            setUserActivity("D", $officer->user?->name);
            $officer->data_status   = 0;
            $officer->delete_by     = loginId();
            $officer->delete_on     = currentDate();
            $officer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('officer.index')->with('error', 'Pegawai tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('officer.index')->with('success', 'Pegawai berjaya dihapus!');
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateOfficer($id);
        return response()->json($data);
    }
}
