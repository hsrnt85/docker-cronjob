<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FinanceOfficer;
use App\Models\FinanceOfficerCategory;
use Illuminate\Http\Request;
use App\Http\Resources\ListData;
use App\Http\Resources\GetData;
use App\Http\Requests\FinanceOfficerRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ListValidateDelete;

class FinanceOfficerController extends Controller
{
    public function index()
    {

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $financeOfficer = FinanceOfficer::where('data_status', 1);
        if($district_id)  $financeOfficer = $financeOfficer->where('district_id', $district_id);

        $financeOfficerAll = $financeOfficer->get();
        $financeCategoryAll = ListData::FinanceOfficerCategory();

        return view(getFolderPath().'.list',
        [
            'financeOfficerAll' => $financeOfficerAll,
            'financeOfficerCategory' => $financeCategoryAll,
        ]);
    }

    public function create()
    {
        $userOffice = getData::UserOffice( loginId() );
        // $userAll = ListData::User( $userOffice->district_id );

        $userAll = User::where('data_status', 1)
        ->doesntHave('finance_officer', 'and', function ($q) {
            $q->where('data_status', 1);
        })
        ->get();

        $financeCategoryAll = ListData::FinanceOfficerCategory();

        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create',
            [
                'userOffice' => $userOffice,
                'userAll' => $userAll,
                'financeOfficerCategory' => $financeCategoryAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(FinanceOfficerRequest $request)
    {
        try {

            $implode_category  = $request->input('officer_category') != null ? implode(',', $request->input('officer_category')) : "";

            $officer = new FinanceOfficer;
            $officer->users_id                      = $request->officer;
            $officer->district_id                   = $request->district;
            $officer->finance_officer_category_id   = $implode_category;
            $officer->action_by                     = loginId();
            $officer->action_on                     = currentDate();

            $saved = $officer->save();

            // Save User Activity
            $user_name = $officer->user?->name;
            setUserActivity("A", $user_name);


            if($saved) return redirect()->route('financeOfficer.index')->with('success', 'Unit Kewangan berjaya ditambah! ');

        } catch (\Exception $e) {

            // something went wrong
            return redirect()->route('financeOfficer.create')->with('error', 'Unit Kewangan tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $financeOfficer = FinanceOfficer::where('id', $id)->first();
        $financeCategoryAll = ListData::FinanceOfficerCategory();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'financeOfficer' => $financeOfficer,
                'financeCategoryAll' => $financeCategoryAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(FinanceOfficerRequest $request)
    {
        $id = $request->id;

        try {
            $officer = FinanceOfficer::findOrFail($id);

            $data_before = $officer->getRawOriginal();

            $officer->finance_officer_category_id = $request->input('officer_category') != null ? implode(',', $request->input('officer_category')) : "";
            $officer->action_by = loginId();
            $officer->action_on = currentDate();
            $updated = $officer->save();

            $data_after = $officer;

            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);

            if ($updated) {

                $user_name = $officer->user?->name;

                setUserActivity("U", $user_name, $data_before_json, $data_after_json);

                return redirect()->route('financeOfficer.index')->with('success', 'Unit Kewangan berjaya dikemaskini!');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('financeOfficer.edit', ['id'=>$request->id])->with('error', 'Unit Kewangan tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
    }


    public function view(Request $request)
    {
        $id = $request->id;

        $financeOfficer = FinanceOfficer::where('id', $id)->first();

        $finance_category_arr = stringToArray($financeOfficer->finance_officer_category_id, ',');
        $financeOfficerCategory = FinanceOfficerCategory::where('data_status', 1)->whereIn('id', $finance_category_arr)->get();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'financeOfficer' => $financeOfficer,
                'financeOfficerCategory' => $financeOfficerCategory,
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
            $officer = FinanceOfficer::findOrFail($request->id);

             // Save User Activity
             $user_name = $officer->user?->name;
             setUserActivity("D", $user_name);

            $officer->data_status   = 0;
            $officer->delete_by     = loginId();
            $officer->delete_on     = currentDate();
            $officer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('financeOfficer.index')->with('error', 'Unit Kewangan tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('financeOfficer.index')->with('success', 'Unit Kewangan berjaya dihapus!');
    }

    public function ajaxGetPosition(Request $request)
    {
        $officer = $request->input('officer');
        $result = User::where(['id'=> $officer, 'data_status' => 1])->first();
        $output = array('position' => $result->position?->position_name);

        if ($output) { return response()->json($output, 201);
        } else {
            return response()->json(['error' => 'Tiada jawatan'], 404);
        }
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateFinanceOfficer($id);
        return response()->json($data);
    }
}
