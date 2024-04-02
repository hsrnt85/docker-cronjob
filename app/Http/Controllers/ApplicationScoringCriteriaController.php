<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListData;
use Illuminate\Http\Request;
use App\Models\ScoringMappingHrmis;
use App\Models\ScoringScheme;
use App\Models\ScoringCriteria;
use App\Models\ScoringSubCriteria;
use App\Http\Requests\ApplicationScoringCriteriaRequest;
use Illuminate\Support\Facades\DB;

class ApplicationScoringCriteriaController extends Controller
{
    public function index()
    {


        $scoringSchemeAll = ScoringScheme::where('data_status', 1)->orderBy('execution_date', 'DESC')->orderBy('id', 'DESC')->get();

        return view( getFolderPath().'.list',
            compact('scoringSchemeAll')
        );
    }

    public function create()
    {

        $scoringMappingHrmisAll = ListData::ScoringMappingHrmis();
        $scoringCriteriaAll = ListData::ScoringCriteria();
        $servicesTypeAll = ListData::ServicesType();
        $positionTypeAll = ListData::PositionType();
        $maritalStatusAll = ListData::MaritalStatus();
        $operatorAll = ListData::Operator();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            compact('scoringMappingHrmisAll',
                    'scoringCriteriaAll',
                    'servicesTypeAll',
                    'positionTypeAll',
                    'maritalStatusAll',
                    'operatorAll',
            )
        );
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(ApplicationScoringCriteriaRequest $request)
    {
        //dd($_REQUEST);
        DB::beginTransaction();

        try {
            //ScoringScheme
            $scoringScheme = new ScoringScheme;
            $scoringScheme->description    = $request->description;
            $scoringScheme->execution_date = $request->execution_date;
            $scoringScheme->action_by     = loginId();
            $scoringScheme->action_on   = currentDate();
            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $scoringScheme->description);
            //------------------------------------------------------------------------------------------------------------------
            $scoringScheme->save();

            //ScoringSubCriteria
            $criteria_name_arr = $request->criteria_name;
            foreach($criteria_name_arr as $i_criteria => $criteria_name){

                $scoringCriteria = new ScoringCriteria;
                $scoringCriteria->scoring_scheme_id = $scoringScheme->id;
                $scoringCriteria->criteria_name = $criteria_name;
                $scoringCriteria->calculation_method = isset($request->calculation_method[$i_criteria]) ? 1 : 0;
                $scoringCriteria->scoring_mapping_hrmis_id = isset($request->scoring_mapping_hrmis[$i_criteria]) ? $request->scoring_mapping_hrmis[$i_criteria] : 0;
                $scoringCriteria->action_by     = loginId();
                $scoringCriteria->action_on   = currentDate();
                $scoringCriteria->save();

                $mark_arr = $request->mark[$i_criteria];
                foreach($mark_arr as $i_subcriteria => $mark){

                    if($mark > 0){

                        $scoringSubCriteria = new ScoringSubCriteria;
                        $scoringSubCriteria->scoring_criteria_id = $scoringCriteria->id;

                        $scoringSubCriteria->item_id       = isset($request->item_id[$i_criteria][$i_subcriteria]) ? $request->item_id[$i_criteria][$i_subcriteria]: 0;
                        $scoringSubCriteria->remarks       = isset($request->remarks[$i_criteria][$i_subcriteria]) ? $request->remarks[$i_criteria][$i_subcriteria]: '';
                        $scoringSubCriteria->range_from    = isset($request->range_from[$i_criteria][$i_subcriteria]) ? $request->range_from[$i_criteria][$i_subcriteria] : 0;
                        $scoringSubCriteria->operator_id   = isset($request->operator_id[$i_criteria][$i_subcriteria]) ? $request->operator_id[$i_criteria][$i_subcriteria] : 0;
                        $scoringSubCriteria->range_to      = isset($request->range_to[$i_criteria][$i_subcriteria]) ? $request->range_to[$i_criteria][$i_subcriteria] : 0;
                        $scoringSubCriteria->mark          = $mark;

                        $scoringSubCriteria->action_by     = loginId();
                        $scoringSubCriteria->action_on     = currentDate();
                        $scoringSubCriteria->save();

                    }

                }

            }

            DB::commit();
            return redirect()->route('applicationScoringCriteria.index')->with('success', 'Kriteria Pemarkahan berjaya ditambah!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('applicationScoringCriteria.create')->with('error', 'Kriteria Pemarkahan tidak berjaya ditambah!');
        }
    }

    public function edit(Request $request)
    {
        $scoring_scheme_id = $request->id;

        $scoringMappingHrmisAll = ListData::ScoringMappingHrmis();
        $scoringScheme = ScoringScheme::find($scoring_scheme_id);
        $scoringCriteriaAll = ListData::ScoringCriteria($scoring_scheme_id);
        $scoringSubCriteriaAll = ListData::ScoringSubCriteria();

        $servicesTypeAll = ListData::ServicesType();
        $positionTypeAll = ListData::PositionType();
        $maritalStatusAll = ListData::MaritalStatus();
        $operatorAll = ListData::Operator();

        $subCriteriaArr = [];

        foreach($scoringCriteriaAll as $i_criteria => $scoringCriteria)
        {
            $scoring_criteria_id = $scoringCriteria->id;
            $scoringSubCriteriaAll = ScoringSubCriteria::where(['data_status'=> 1, 'scoring_criteria_id'=> $scoring_criteria_id])->get();

            foreach($scoringSubCriteriaAll as $i_subcriteria => $scoringSubCriteria)
            {
                $subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id'] = $scoringSubCriteria->id;
                $subCriteriaArr[$i_criteria][$i_subcriteria]['item_id'] = $scoringSubCriteria->item_id;
                $subCriteriaArr[$i_criteria][$i_subcriteria]['remarks'] = $scoringSubCriteria->remarks;
                $subCriteriaArr[$i_criteria][$i_subcriteria]['range_from'] = $scoringSubCriteria->range_from;
                $subCriteriaArr[$i_criteria][$i_subcriteria]['operator_id'] = $scoringSubCriteria->operator_id;
                $subCriteriaArr[$i_criteria][$i_subcriteria]['range_to'] = $scoringSubCriteria->range_to;
                $subCriteriaArr[$i_criteria][$i_subcriteria]['mark'] = $scoringSubCriteria->mark;
            }

        }

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
                compact('scoringMappingHrmisAll',
                        'scoringScheme',
                        'scoringCriteriaAll',
                        'subCriteriaArr',
                        'servicesTypeAll',
                        'positionTypeAll',
                        'maritalStatusAll',
                        'operatorAll',
                )
            );
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }


    }

    public function update(ApplicationScoringCriteriaRequest $request)
    {
        //dd($_REQUEST);
        $data_before = []; // Initialize an empty array

        try {
            DB::beginTransaction();

            // UPDATE - SCORING SCHEME
            $scoring_scheme_id = $request->scoring_scheme_id;
            $scoringScheme = ScoringScheme::find($scoring_scheme_id);
            $data_before = ['scoringScheme' => $scoringScheme->toArray(),];
            $scoringScheme->description = $request->description;
            $scoringScheme->execution_date = $request->execution_date;
            $scoringScheme->action_by = loginId();
            $scoringScheme->action_on = currentDate();
            $scoringScheme->save();

            //ScoringCriteria
            $scoring_criteria_id_arr = $request->scoring_criteria_id;
            foreach($scoring_criteria_id_arr as $i_criteria => $scoring_criteria_id){

                $flag_delete_criteria = isset($request->flag_delete_criteria[$i_criteria]) ? $request->flag_delete_criteria[$i_criteria] : 0;

                // CHECK FLAG PROCESS CRITERIA=> 0=NEW/UPDATE, 1= DELETE
                if($flag_delete_criteria == 0){

                    if($scoring_criteria_id == 0){
                        // INSERT - NEW CRITERIA
                        $scoringCriteria = new ScoringCriteria;
                        $scoringCriteria->scoring_scheme_id = $scoring_scheme_id;
                        $scoringCriteria->criteria_name = isset($request->criteria_name[$i_criteria]) ? $request->criteria_name[$i_criteria] : 0;
                        $scoringCriteria->calculation_method = isset($request->calculation_method[$i_criteria]) ? 1 : 0;
                        $scoringCriteria->scoring_mapping_hrmis_id = isset($request->scoring_mapping_hrmis[$i_criteria]) ? $request->scoring_mapping_hrmis[$i_criteria] : 0;
                        $scoringCriteria->action_by = loginId();
                        $scoringCriteria->action_on = currentDate();
                        $scoringCriteria->save();

                        $mark_arr = $request->mark[$i_criteria];
                        foreach($mark_arr as $i_subcriteria => $mark){

                            // INSERT - NEW SUB-CRITERIA
                            $scoringSubCriteria = new ScoringSubCriteria;
                            $scoringSubCriteria->scoring_criteria_id = $scoringCriteria->id;
                            $scoringSubCriteria->item_id       = isset($request->item_id[$i_criteria][$i_subcriteria]) ? $request->item_id[$i_criteria][$i_subcriteria]: 0;
                            $scoringSubCriteria->remarks       = isset($request->remarks[$i_criteria][$i_subcriteria]) ? $request->remarks[$i_criteria][$i_subcriteria]: '';
                            $scoringSubCriteria->range_from    = isset($request->range_from[$i_criteria][$i_subcriteria]) ? $request->range_from[$i_criteria][$i_subcriteria] : 0;
                            $scoringSubCriteria->operator_id   = isset($request->operator_id[$i_criteria][$i_subcriteria]) ? $request->operator_id[$i_criteria][$i_subcriteria] : 0;
                            $scoringSubCriteria->range_to      = isset($request->range_to[$i_criteria][$i_subcriteria]) ? $request->range_to[$i_criteria][$i_subcriteria] : 0;
                            $scoringSubCriteria->mark          = $mark;
                            $scoringSubCriteria->action_by     = loginId();
                            $scoringSubCriteria->action_on     = currentDate();
                            $scoringSubCriteria->save();

                        }

                    }else{

                        // UPDATE - IF CRITERIA EXIST
                        $scoringCriteria = ScoringCriteria::find($scoring_criteria_id);
                        $scoringCriteria->criteria_name = isset($request->criteria_name[$i_criteria]) ? $request->criteria_name[$i_criteria] : 0;
                        $scoringCriteria->calculation_method = isset($request->calculation_method[$i_criteria]) ? $request->calculation_method[$i_criteria] : 0;
                        $scoringCriteria->scoring_mapping_hrmis_id = isset($request->scoring_mapping_hrmis[$i_criteria]) ? $request->scoring_mapping_hrmis[$i_criteria] : 0;
                        $scoringCriteria->action_by     = loginId();
                        $scoringCriteria->action_on = currentDate();
                        $scoringCriteria->save();

                        $mark_arr = $request->mark[$i_criteria];
                        foreach($mark_arr as $i_subcriteria => $mark){

                            $scoring_subcriteria_id = isset($request->scoring_subcriteria_id[$i_criteria][$i_subcriteria]) ? $request->scoring_subcriteria_id[$i_criteria][$i_subcriteria] : 0;
                            $flag_delete_subcriteria = isset($request->flag_delete_subcriteria[$i_criteria][$i_subcriteria]) ? $request->flag_delete_subcriteria[$i_criteria][$i_subcriteria] : 0;
                            $item_id = isset($request->item_id[$i_criteria][$i_subcriteria]) ? $request->item_id[$i_criteria][$i_subcriteria]: 0;
                            $remarks = isset($request->remarks[$i_criteria][$i_subcriteria]) ? $request->remarks[$i_criteria][$i_subcriteria]: '';
                            $range_from = isset($request->range_from[$i_criteria][$i_subcriteria]) ? $request->range_from[$i_criteria][$i_subcriteria] : 0;
                            $operator_id = isset($request->operator_id[$i_criteria][$i_subcriteria]) ? $request->operator_id[$i_criteria][$i_subcriteria] : 0;
                            $range_to = isset($request->range_to[$i_criteria][$i_subcriteria]) ? $request->range_to[$i_criteria][$i_subcriteria] : 0;

                            // CHECK FLAG PROCESS SUBCRITERIA=> 0=NEW/UPDATE, 1= DELETE
                            if($flag_delete_subcriteria == 0){

                                if($scoring_subcriteria_id == 0){
                                    // INSERT - IF NEW SUB-CRITERIA
                                    $scoringSubCriteria = new ScoringSubCriteria;
                                    $scoringSubCriteria->scoring_criteria_id = $scoringCriteria->id;

                                    $scoringSubCriteria->item_id       = $item_id;
                                    $scoringSubCriteria->remarks       = $remarks;
                                    $scoringSubCriteria->range_from    = $range_from;
                                    $scoringSubCriteria->operator_id   = $operator_id;
                                    $scoringSubCriteria->range_to      = $range_to;
                                    $scoringSubCriteria->mark          = $mark;
                                    $scoringSubCriteria->action_by     = loginId();
                                    $scoringSubCriteria->action_on     = currentDate();
                                    $scoringSubCriteria->save();

                                }elseif($scoring_subcriteria_id > 0){
                                    // UPDATE - SUB-CRITERIA
                                    $scoringSubCriteria = ScoringSubCriteria::find($scoring_subcriteria_id);

                                    $scoringSubCriteria->item_id       = $item_id;
                                    $scoringSubCriteria->remarks       = $remarks;
                                    $scoringSubCriteria->range_from    = $range_from;
                                    $scoringSubCriteria->operator_id   = $operator_id;
                                    $scoringSubCriteria->range_to      = $range_to;
                                    $scoringSubCriteria->mark          = $mark;
                                    $scoringSubCriteria->action_by     = loginId();
                                    $scoringSubCriteria->action_on     = currentDate();
                                    $scoringSubCriteria->save();
                                }

                            }else{

                                //DELETE SUBCRITERIA
                                $scoringSubCriteria = ScoringSubCriteria::find($scoring_subcriteria_id);
                                $scoringSubCriteria->data_status = 0;
                                $scoringSubCriteria->delete_by  = loginId();
                                $scoringSubCriteria->delete_on  = currentDate();
                                $scoringSubCriteria->save();

                            }
                        }
                    }

                }else{

                    //DELETE CRITERIA
                    $scoringCriteria = ScoringCriteria::find($scoring_criteria_id);
                    $scoringCriteria->data_status = 0;
                    $scoringCriteria->delete_by  = loginId();
                    $scoringCriteria->delete_on  = currentDate();
                    $scoringCriteria->save();

                }
            }
            $data_before['scoringCriteria'] = $scoringCriteria->toArray();

            DB::commit();

            // User Activity - Set Data after changes
            $data_after = [
                'scoringScheme' => $scoringScheme->fresh()->toArray(),
                'scoringCriteria' => $scoringCriteria->fresh()->toArray(),
            ];

            // Convert data arrays to JSON
            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);

            // User Activity - Save
            setUserActivity("U", $scoringScheme->description, $data_before_json, $data_after_json);

            return redirect()->route('applicationScoringCriteria.index')->with('success', 'Kriteria Pemarkahan berjaya dikemaskini!');

            // return redirect()->route('applicationScoringCriteria.edit', ['id' => $scoring_scheme_id])->with('success', 'Kriteria Pemarkahan berjaya dikemaskini! ');

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('applicationScoringCriteria.index')->with('error', 'Kriteria Pemarkahan tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
            //return redirect()->route('applicationScoringCriteria.edit', ['id' => $scoring_scheme_id])->with('error', 'Kriteria Pemarkahan tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }


    }

    public function view(Request $request)
    {
        $scoring_scheme_id = $request->id;

        $scoringMappingHrmisAll = ScoringMappingHrmis::where('data_status', 1)->get();
        $scoringScheme = ScoringScheme::find($scoring_scheme_id);
        $scoringCriteriaAll = ScoringCriteria::where(['data_status'=>1,'scoring_scheme_id'=>$scoring_scheme_id])->get();
        $scoringSubCriteriaAll = ScoringSubCriteria::where('data_status', 1)->get();

        $servicesTypeAll = ListData::ServicesType();
        $positionTypeAll = ListData::PositionType();
        $maritalStatusAll = ListData::MaritalStatus();
        $operatorAll = ListData::Operator();

        $subCriteriaArr = [];
        foreach($scoringCriteriaAll as $scoringCriteria)
        {
            $scoring_criteria_id = $scoringCriteria->id;
            $scoringSubCriteriaAll = ScoringSubCriteria::where(['data_status'=> 1, 'scoring_criteria_id'=> $scoring_criteria_id])->get();

            foreach($scoringSubCriteriaAll as $i_subcriteria => $scoringSubCriteria)
            {
                $subCriteriaArr[$scoring_criteria_id][$i_subcriteria]['item_id'] = $scoringSubCriteria->item_id;
                $subCriteriaArr[$scoring_criteria_id][$i_subcriteria]['remarks'] = $scoringSubCriteria->remarks;
                $subCriteriaArr[$scoring_criteria_id][$i_subcriteria]['range_from'] = $scoringSubCriteria->range_from;
                $subCriteriaArr[$scoring_criteria_id][$i_subcriteria]['operator_id'] = $scoringSubCriteria->operator_id;
                $subCriteriaArr[$scoring_criteria_id][$i_subcriteria]['range_to'] = $scoringSubCriteria->range_to;
                $subCriteriaArr[$scoring_criteria_id][$i_subcriteria]['mark'] = $scoringSubCriteria->mark;
            }

        }

        if(checkPolicy("V"))
        {
            //dd($subCriteriaArr);
                return view( getFolderPath().'.view',
                compact('scoringMappingHrmisAll',
                        'scoringScheme',
                        'scoringCriteriaAll',
                        'subCriteriaArr',
                        'servicesTypeAll',
                        'positionTypeAll',
                        'maritalStatusAll',
                        'operatorAll',
                )
            );
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
            $scoring_scheme_id = $request->id;

            $scoringScheme = ScoringScheme::find($scoring_scheme_id);

            setUserActivity("D", $scoringScheme->description);

            $scoringScheme->data_status = 0;
            $scoringScheme->delete_by  = loginId();
            $scoringScheme->delete_on  = currentDate();
            $scoringScheme->save();

            $scoringCriteria = ScoringCriteria::where('scoring_scheme_id', $scoring_scheme_id)->get();
            foreach ($scoringCriteria as $dataScoringCriteria){
                $dataScoringCriteria->data_status = 0;
                $dataScoringCriteria->delete_by  = loginId();
                $dataScoringCriteria->delete_on  = currentDate();
                $dataScoringCriteria->save();

                ScoringSubCriteria::where('scoring_criteria_id',$dataScoringCriteria->id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

			}

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('applicationScoringCriteria.index')->with('error', 'Kriteria Pemarkahan tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationScoringCriteria.index')->with('success', 'Kriteria Pemarkahan berjaya dihapus!');
    }

    public function destroyByCriteria(Request $request)
    {
        $id = $request->scoring_scheme_id;
        $scoring_criteria_id = $request->criteria_id;

        DB::beginTransaction();

        try {

            ScoringCriteria::where('id',$scoring_criteria_id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('applicationScoringCriteria.edit', ['id'=>$id])->with('error', 'Kriteria tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationScoringCriteria.edit', ['id'=>$id])->with('success', 'Kriteria berjaya dihapus!');
    }

    public function destroyBySubcriteria(Request $request)
    {
        $id = $request->scoring_scheme_id;
        $scoring_subcriteria_id = $request->subcriteria_id;

        DB::beginTransaction();

        try {

            ScoringSubCriteria::where('id',$scoring_subcriteria_id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('applicationScoringCriteria.edit', ['id'=>$id])->with('error', 'Kenyataan Kriteria tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationScoringCriteria.edit', ['id'=>$id])->with('success', 'Kenyataan Kriteria berjaya dihapus!');
    }

}
