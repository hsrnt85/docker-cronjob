<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuartersCategory;
use App\Http\Resources\ListData;
use App\Models\LandedType;
use App\Models\QuartersClass;
use App\Models\QuartersCategoryClass;
use App\Models\Inventory;
use App\Models\QuartersCategoryInventory;
use App\Models\QuartersClassGrade;
use App\Models\ServicesType;
use App\Models\PositionGrade;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\QuartersCategoryRequest;
use App\Http\Resources\ListValidateDelete;

class QuartersCategoryController extends Controller
{

    public function index()
    {
        $district_id = (!is_all_district()) ?  districtId() : null;

        $senaraiKategoriKuarters = QuartersCategory::where('data_status', 1);
        if($district_id>0){
            $senaraiKategoriKuarters = $senaraiKategoriKuarters->where('district_id', districtId());
        }
        $senaraiKategoriKuarters = $senaraiKategoriKuarters->orderBy('name')->get();

        return view(getFolderPath().'.list',
        [
            'senaraiKategoriKuarters' => $senaraiKategoriKuarters
        ]);
    }

    public function create()
    {
        $districtAll =ListData::District();
        $landedTypeAll = LandedType::where('data_status', 1)->get();
        $quartersClassAll = QuartersClass::where('data_status', 1)->orderBy('class_name')->get();

        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create',
            [
                'districtAll' => $districtAll,
                'landedTypeAll' => $landedTypeAll,
                'quartersClassAll' => $quartersClassAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(QuartersCategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $quartersCategory = new QuartersCategory;

            $quartersCategory->name             = $request->name;
            $quartersCategory->district_id      = districtId();//$request->district;
            $quartersCategory->landed_type_id   = $request->landedType;
            $quartersCategory->action_by        = loginId();
            $quartersCategory->action_on        = currentDate();
            $quartersCategory->save();

            foreach($request->categoryClass as $ivalue)
            {
                $quartersCatClass = new QuartersCategoryClass;
                $quartersCatClass->q_cat_id         = $quartersCategory->id;
                $quartersCatClass->q_class_id       = $ivalue;
                $quartersCatClass->action_by        = loginId();
                $quartersCatClass->action_on        = currentDate();
                $quartersCatClass->save();
            }

            DB::commit();

            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $quartersCategory->name);
            //------------------------------------------------------------------------------------------------------------------

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('quartersCategory.create')->with('error', 'Kategori Kuarters (Lokasi) tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }
        return redirect()->route('quartersCategory.index')->with('success', 'Kategori Kuarters (Lokasi) berjaya ditambah!');
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $quartersCategory = QuartersCategory::where('id', $id)->first();
        $districtAll =ListData::District();
        $landedTypeAll = LandedType::where('data_status', 1)->get();
        $quartersClassAll = QuartersClass::where('data_status', 1)->orderBy('class_name')->get();

        $quartersCat = QuartersCategory::findOrFail($id);
        $quartersClassByCatId = $quartersCat->quartersClass->where('pivot.data_status', 1);
        $inventoryAll = Inventory::where([['quarters_category_id', $id],['data_status', 1]])->get();
        $quartersInventoryAll = QuartersCategoryInventory::where('quarters_category_id', $id)
        ->where('data_status', 1)
        ->get();
        //dd($quartersClass);

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
            [
                'quartersCat' => $quartersCat,
                'quartersClassByCatId' => $quartersClassByCatId,
                'quartersCategory' => $quartersCategory,
                'districtAll' => $districtAll,
                'landedTypeAll' => $landedTypeAll,
                'quartersClassAll' => $quartersClassAll,
                'inventoryAll' => $inventoryAll,
                'quartersInventoryAll' => $quartersInventoryAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(QuartersCategoryRequest $request)
    {
        $quartersCatId = $request->id;

        DB::beginTransaction();

        try {
            $quartersCategory = QuartersCategory::findOrFail($quartersCatId);
            $quartersCategoryClass = QuartersCategoryClass::where('q_cat_id', $quartersCatId)->where('data_status', 1)->get();
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data before changes
            $data_before = $quartersCategory->getRawOriginal();//dd($data_before);
            $data_before['item']= $quartersCategoryClass->toArray() ?? [];//dd($data_before);
            //------------------------------------------------------------------------------------------------------------------

            $quartersCategory->category()->associate($quartersCatId);
            $quartersCategory->name             = $request->name;
            $quartersCategory->landed_type_id   = $request->landedType;
            $quartersCategory->action_by        = loginId();
            $quartersCategory->action_on        = currentDate();
            $quartersCategory->save();

            // Uncheck class
            QuartersCategoryClass::where('data_status', 1)
                                    ->where('q_cat_id', $quartersCatId)
                                    ->whereNotIn('q_class_id', array_keys($request->categoryClass)) // id categoryClass checked
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            // Update categoryClass new value
            foreach($request->categoryClass as $q_class_id)
            {
                $quartersClass = new QuartersCategoryClass;
                $quartersClass->q_cat_id             = $quartersCategory->id;
                $quartersClass->q_class_id           = $q_class_id;
                $quartersClass->action_by            = loginId();
                $quartersClass->action_on            = currentDate();
                $quartersClass->save();
            }

            DB::commit();

            $quartersCategoryClass = QuartersCategoryClass::where('q_cat_id', $quartersCatId)->where('data_status', 1)->get();
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            $data_after = $quartersCategory;
            $data_after['item'] = $quartersCategoryClass->toArray() ?? [];
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            setUserActivity("U", $quartersCategory->name, $data_before, $data_after); 
            //------------------------------------------------------------------------------------------------------------------

            return redirect()->route('quartersCategory.index')->with('success', 'Kategori Kuarters (Lokasi) berjaya dikemaskini!');

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('quartersCategory.index')->with('error', 'Kategori Kuarters (Lokasi) tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

    }

    public function view(Request $request)
    {
        $id = $request->id;

        $quartersCategory = QuartersCategory::where('id', $id)->first();
        $quartersCategoryClassAll = QuartersCategoryClass::where('q_cat_id', $quartersCategory->id)
                                ->where('data_status', 1)
                                ->get();
        $quartersInventoryAll = Inventory::where([['quarters_category_id', $id],['data_status', 1]])
                                ->get();

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'quartersCategory' => $quartersCategory,
                'quartersCategoryClassAll' => $quartersCategoryClassAll,
                'quartersInventoryAll' => $quartersInventoryAll
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
            $quartersCategory = QuartersCategory::findOrFail($id);

            setUserActivity("D", $quartersCategory->name);

            $quartersCategory->data_status  = 0;
            $quartersCategory->delete_by    = loginId();
            $quartersCategory->delete_on    = currentDate();
            $quartersCategory->save();

            QuartersCategoryClass::where('q_cat_id', $quartersCategory->id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            QuartersCategoryInventory::where('quarters_category_id', $quartersCategory->id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('quartersCategory.edit', ['id'=>$request->id])->with('error', 'Kategori Kuarters (Lokasi) tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('quartersCategory.index')->with('success', 'Kategori Kuarters (Lokasi) berjaya dihapus!');
    }

    public function destroyByRow(Request $request)
    {
        $id = $request->id;
        $quarters_class_id = $request->id_by_row;

        DB::beginTransaction();

        try {

            QuartersCategoryInventory::where('id', $quarters_class_id)
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('quartersCategory.edit', ['id'=>$id])->with('error', 'Maklumat Inventori tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('quartersCategory.edit', ['id'=>$id])->with('success', 'Maklumat Inventori berjaya dihapus!');
    }

    public function inventoryList(){
        $positionGradeAll = Inventory::select('id','name')->where('data_status', 1)->get();

        return response()->json($positionGradeAll);
    }

    public function classGradeList(Request $request){

        $category_class = $request -> category_class;

        $classGradeAll = QuartersClassGrade::join('services_type', 'services_type.id', '=', 'quarters_class_grade.services_type_id')
        ->join('position_grade', 'position_grade.id', '=', 'quarters_class_grade.p_grade_id')
        ->where([
            ['quarters_class_grade.q_class_id','=',$category_class],
            ['quarters_class_grade.data_status', 1],
            ['services_type.data_status', 1],
            ['position_grade.data_status', 1]
            ])
        ->get();

        return response()->json($classGradeAll);

    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateQuartersCategory($id);
        return response()->json($data);
    }

}
