<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuartersClass;
use App\Models\QuartersClassGrade;
use App\Models\PositionGrade;
use App\Models\ServicesType;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\QuartersClassRequest;
use App\Models\District;
use App\Http\Resources\ListValidateDelete;

class QuartersClassController extends Controller
{

    public function index()
    {

        $senaraiKelasKuarters = QuartersClass::where('data_status', 1)->orderBy('class_name')->get();

        return view( getFolderPath().'.list',
        [
            'senaraiKelasKuarters' => $senaraiKelasKuarters
        ]);
    }

    public function create()
    {
        $positionGradeAll = PositionGrade::where('data_status', 1)->get();
        $servicesTypeAll = ServicesType::where('data_status', 1)->get();
        $gredJawatanAll = PositionGrade::where('data_status', 1)->orderBy(DB::raw('CAST(grade_no AS unsigned)'))->get();
        $districtAll = District::where('data_status', 1)->get();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'positionGradeAll' => $positionGradeAll,
                'servicesTypeAll' => $servicesTypeAll,
                'gredJawatanAll' => $gredJawatanAll,
                'districtAll' => $districtAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view(Request $request)
    {
        $id = $request->id;

        $quartersClass = QuartersClass::where('id', $id)->first();
        $classGradeAll = QuartersClassGrade::where('q_class_id', $quartersClass->id)
                                ->where('data_status', 1)
                                ->get();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'quartersClass' => $quartersClass,
                'classGradeAll' => $classGradeAll
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
        $quartersClass = QuartersClass::where('id', $id)->first();
        $senaraiSewaAll = QuartersClassGrade::where('q_class_id', $id)
                        ->where('data_status', 1)
                        ->get();
        $gredJawatanAll = PositionGrade::where('data_status', 1)->orderBy(DB::raw('CAST(grade_no AS unsigned)'))->get();
        $servicesTypeAll = servicesType::where('data_status', 1)->get();
        $districtAll = District::where('data_status', 1)->get();

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
            [
                'quartersClass' => $quartersClass,
                'senaraiSewaAll' => $senaraiSewaAll,
                'gredJawatanAll' => $gredJawatanAll,
                'servicesTypeAll' => $servicesTypeAll,
                'districtAll' => $districtAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function store(QuartersClassRequest $request)
    {
        DB::beginTransaction();

        try {

            $p_grade_ids = $request->p_grade_id;
            if($p_grade_ids){

                $quartersClass = new QuartersClass;
                $quartersClass->class_name      = $request->class_name;
                $quartersClass->district_id     = $request->district;
                $quartersClass->data_status     = 1;
                $quartersClass->action_by       = loginId();
                $quartersClass->action_on       = currentDate();
                $quartersClass->save();

                foreach($p_grade_ids as $i => $p_grade_id){
                    $quartersClassGrade = new QuartersClassGrade;
                    $quartersClassGrade->q_class_id       = $quartersClass->id;
                    $quartersClassGrade->p_grade_id       = $p_grade_id;
                    $quartersClassGrade->services_type_id  = $request->input('services_type_id')[$i];
                    $quartersClassGrade->market_rental_amount = $request->input('market_rental_amount')[$i];
                    $quartersClassGrade->rental_fee       = $request->input('rental_fee')[$i];
                    $quartersClassGrade->action_by        = loginId();
                    $quartersClassGrade->action_on        = currentDate();
                    $quartersClassGrade->save();
                }

                DB::commit();

                //------------------------------------------------------------------------------------------------------------------
                // Save User Activity
                //------------------------------------------------------------------------------------------------------------------
                setUserActivity("A", $quartersClass->class_name);
                //------------------------------------------------------------------------------------------------------------------


                return redirect()->route('quartersClass.index')->with('success', 'Kelas Kuarters berjaya ditambah!');

            }else{
                return redirect()->route('quartersClass.create')->with('error', 'Sila Lengkapkan Maklumat Sewa!');
            }

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('quartersClass.create')->with('error', 'Kelas Kuarters tidak berjaya ditambah!' . $e->getMessage());
        }

    }

    public function update(QuartersClassRequest $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {

            $quartersClass = QuartersClass::findOrFail($id);
            $quartersClassGrade = QuartersClassGrade::where('q_class_id', $id)->where('data_status', 1)->get();
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data before changes
            $data_before = $quartersClass->getRawOriginal();//dd($data_before);
            $data_before['item']= $quartersClassGrade->toArray() ?? [];//dd($data_before);
            //------------------------------------------------------------------------------------------------------------------

            $quartersClass->class_name      = $request->class_name;
            $quartersClass->district_id     = $request->district;
            $quartersClass->action_by       = loginId();
            $quartersClass->action_on       = currentDate();
            $quartersClass->save();

            $id_quarters_class_grade_arr = $request->id_quarters_class_grade;

            foreach($id_quarters_class_grade_arr as $i => $id_quarters_class_grade){

                $p_grade_id = isset($request->p_grade_id[$i]) ? $request->p_grade_id[$i] : 0;
                $services_type_id  = isset($request->services_type_id[$i]) ? $request->services_type_id[$i] : 0;
                $market_rental_amount = isset($request->market_rental_amount[$i]) ? $request->market_rental_amount[$i] : 0;
                $rental_fee = isset($request->rental_fee[$i]) ? $request->rental_fee[$i] : 0;

                //UPDATE
                if($id_quarters_class_grade == 0)
                {
                    //INSERT
                    $quartersClassGrade = new QuartersClassGrade;
                    $quartersClassGrade->q_class_id       = $quartersClass->id;
                    $quartersClassGrade->p_grade_id       = $p_grade_id;
                    $quartersClassGrade->services_type_id  = $services_type_id ;
                    $quartersClassGrade->market_rental_amount = $market_rental_amount;
                    $quartersClassGrade->rental_fee       = $rental_fee;
                    $quartersClassGrade->action_by        = loginId();
                    $quartersClassGrade->action_on        = currentDate();
                    $quartersClassGrade->save();

                }else{

                    $quartersClassGrade = QuartersClassGrade::where('id', $id_quarters_class_grade)
                        ->update([
                            'p_grade_id' => $p_grade_id,
                            'services_type_id' => $services_type_id,
                            'market_rental_amount' => $market_rental_amount,
                            'rental_fee' => $rental_fee,
                            'action_by' => loginId(),
                            'action_on' => currentDate()
                        ]);
                }
            }

            DB::commit();

            //------------------------------------------------------------------------------------------------------------------
            $quartersClassGrade = QuartersClassGrade::where('q_class_id', $id)->where('data_status', 1)->get();
            // User Activity - Set Data after changes
            $data_after = $quartersClass->toArray();
            $data_after['item'] = $quartersClassGrade->toArray() ?? [];

            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            setUserActivity("U", $quartersClass->class_name, $data_before_json, $data_after_json);
            //------------------------------------------------------------------------------------------------------------------

            return redirect()->route('quartersClass.index')->with('success', 'Kelas Kuarters berjaya dikemaskini!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('quartersClass.edit', ['id'=>$request->id])->with('error', 'Kelas Kuarters tidak berjaya dikemaskini! '.$e);
        }

    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {

            $quartersClass = QuartersClass::findOrFail($id);

            setUserActivity("D", $quartersClass->class_name);

            $quartersClass->data_status  = 0;
            $quartersClass->delete_by    = loginId();
            $quartersClass->save();

            QuartersClassGrade::where('q_class_id', $quartersClass->id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('quartersClass.edit', ['id'=>$request->id])->with('error', 'Kelas Kuarters tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('quartersClass.index')->with('success', 'Kelas Kuarters berjaya dihapus!');
    }

    public function destroyByRow(Request $request)
    {
        $id = $request->id;
        $quarters_class_id = $request->id_by_row;

        DB::beginTransaction();

        try {

            QuartersClassGrade::where('id', $quarters_class_id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('quartersClass.edit', ['id'=>$id])->with('error', 'Maklumat sewa tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('quartersClass.edit', ['id'=>$id])->with('success', 'Maklumat sewa berjaya dihapus!');
    }

    public function gradeList(){
        $positionGradeAll = PositionGrade::select('id','grade_no')->where('data_status', 1)->orderBy(DB::raw('CAST(grade_no AS unsigned)'))->get();

        return response()->json($positionGradeAll);
    }

    public function servicesTypeList(){
        $servicesTypeAll = servicesType::select('id','services_type')->where('data_status', 1)->get();

        return response()->json($servicesTypeAll);
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateQuartersClass($id);
        return response()->json($data);
    }

}
