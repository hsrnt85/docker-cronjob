<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ListData;
use App\Models\ActiveStatus;
use App\Models\Quarters;
use App\Models\QuartersCategory;
use App\Models\QuartersCondition;
use App\Models\Inventory;
use App\Models\MaintenanceInventory;
use App\Models\QuartersInventory;
use App\Models\UserOffice;
use App\Models\QuartersImage;
use App\Models\QuartersCategoryInventory;
use App\Http\Requests\QuartersRequest;
use Illuminate\Support\Facades\DB;


class QuartersController extends Controller
{
    public function indexKategoriKuarters()
    {

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $quartersCategory = QuartersCategory::select('id','name','landed_type_id','district_id')->where('data_status', 1)->orderBy('name','ASC');

        if($district_id)
        {
                $quartersCategory = $quartersCategory->where('district_id', $district_id);
        }

        $quartersCategoryAll = $quartersCategory->get();

        return view( getFolderPath().'.list-quarters-category',
        [
            'quartersCategoryAll' => $quartersCategoryAll
        ]);
    }

    public function indexKuarters(Request $request)
    {


        $quarters_cat_id = $request->quarters_cat_id;

        $category_name = QuartersCategory::select('name')->where(['data_status'=> 1, 'id'=>$quarters_cat_id ])->first();
        $activeStatusAll = ActiveStatus::where('data_status', '=', 1)->get();
        $quartersAll = Quarters::with('category')->where([['data_status', '>', 0] , 'quarters_cat_id'=>$quarters_cat_id ])->orderBy('unit_no', 'ASC')->orderBy('address_1','ASC')->get();

        return view( getFolderPath().'.list-quarters',
        [
            'activeStatusAll' => $activeStatusAll,
            'quartersAll' => $quartersAll,
            'quarters_cat_id' => $quarters_cat_id,
            'category_name' => $category_name,
        ]);
    }

    public function create(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        if($quarters_cat_id==0){
            $districtAll = ListData::District();//districtId()
            $quartersCategoryAll = QuartersCategory::where(['data_status'=> 1, 'district_id'=>districtId() ])->get();
        }else{
            $quartersCategoryAll = QuartersCategory::where(['data_status'=> 1, 'id'=>$quarters_cat_id ])->first();
            $districtAll = ListData::District( $quartersCategoryAll->district_id );
        }

        $inventoryAll = Inventory::where([['quarters_category_id', $quarters_cat_id],['data_status', 1]])->get();
        $maintenanceInventoryAll = MaintenanceInventory::where('data_status', 1)->get();
        $quartersCategoryInventoryAll = QuartersCategoryInventory::where('data_status', 1)->get();
        $userOffice = UserOffice::where('data_status', 1)->where('users_id',loginId())->get();
        $userOffice2 = UserOffice::where('data_status', 1)->where('users_id',loginId())->first();
        $quartersConditionAll = QuartersCondition::where('data_status', 1)->get();

        // if(checkPolicy("A"))
        // {
            return view( getFolderPath().'.create',
            [
                'districtAll' => $districtAll,
                'inventoryAll' => $inventoryAll,
                'maintenanceInventoryAll' => $maintenanceInventoryAll,
                'quartersCategoryInventoryAll' => $quartersCategoryInventoryAll,
                'quartersConditionAll' => $quartersConditionAll,
                'userOffice' => $userOffice,
                'quartersCategoryAll' => $quartersCategoryAll,
                'userOffice2' => $userOffice2,
                'quarters_cat_id' => $quarters_cat_id,
            ]);
        // }
        // else
        // {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }
    }


    public function store(QuartersRequest $request)
    {
        // dd($request->all());
        $quarters_cat_id = $request->quarters_cat_id;

        DB::beginTransaction();

        try {

            $quartersCategory = QuartersCategory::findOrFail($request->quarters_category);

            for($i = 0; $i < $request-> total; $i++)
            {
                $quarters = new Quarters;
                $quarters->category()->associate($quartersCategory);
                // $quarters->no_ptd       = $request->no_ptd;
                $quarters->address_1    = $request->address_1;
                $quarters->address_2    = $request->address_2;
                $quarters->address_3    = $request->address_3;
                $quarters->land_tax     = $request->land_tax;
                $quarters->property_tax = $request->property_tax;
                $quarters->m_utility_id = isset($request->iwk) ? 1 : null; // 1 = iwk
                $quarters->iwk_fee      = $request->iwk_fee;
                $quarters->maintenance_fee  = $request->maintenance_fee;
                $quarters->quarters_condition_id = $request->quarters_condition;
                $quarters->room_no      = $request->room_no;
                $quarters->bathroom_no  = $request->bathroom_no;
                $quarters->action_by    = loginId();
                $quarters->action_on    = currentDate();
                $quarters->save();

                $quarters_image_request = isset($request->quarters_picture) ? $request->quarters_picture: "";

                if($quarters_image_request)
                {
                    foreach($quarters_image_request as $attachment)
                    {
                        $path = $attachment->store('quarters_info', 'assets-upload');

                        $quartersImage                    = new QuartersImage;
                        $quartersImage ->quarters_id      = $quarters->id;
                        $quartersImage ->path_image       = $path;
                        $quartersImage ->status_data      = 1;
                        $quartersImage ->action_by        = loginId();
                        $quartersImage ->action_on        = currentDate();
                        $quartersImage ->save();
                    }
                }

                if($request->inventory)
                {
                    foreach($request->inventory as $ikey => $ivalue)
                    {
                        $quartersInventory = new QuartersInventory;
                        $quartersInventory->q_id                                       = $quarters->id;
                        $quartersInventory->i_id                                       = $ikey;
                        $quartersInventory->quantity                                   = $request->quantity[$ikey];
                        $quartersInventory->m_inventory_id                             = $request->responsibility[$ikey];
                        $quartersInventory->data_status                                = 1;
                        $quartersInventory->action_by                                  = loginId();
                        $quartersInventory->action_on                                  = currentDate();
                        $quartersInventory->save();
                    }
                }
            }

            DB::commit();

            // Save User Activity
            setUserActivity("A", "Kategori Kuarters (lokasi) : " . $quartersCategory->name);

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('quarters.create', ['quarters_cat_id' => $quarters_cat_id])->with('error', 'Kuarters tidak berjaya ditambah!');
        }

        return redirect()->route('quarters.addUnitNo', ['quarters_cat_id' => $quarters_cat_id])->with('success', 'Kuarters berjaya ditambah!');
    }


    public function addUnitNo(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        $quartersAll = Quarters::where('data_status', 1);
        if($quarters_cat_id>0){
            $quartersAll->where('quarters_cat_id', $quarters_cat_id);
        }
        $quartersAll->where(function ($query) {
            $query->where('unit_no', '=', null)->orWhere('unit_no', '=', '');
        })->get();

        return view( getFolderPath().'.add-unit-no-list',
        [
            'quartersAll' => $quartersAll,
            'quarters_cat_id' => $quarters_cat_id,
        ]);
    }


    public function storeUnitNo(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        $count = 0;

        DB::beginTransaction();

        try {

            foreach($request->unit_no as $key => $unit_no)
            {
                if($unit_no != null)
                {
                    $quarters = Quarters::findOrFail($key);

                    $quarters->unit_no          = $unit_no;
                    $quarters->land_tax         = $request->land_tax[$key];
                    $quarters->property_tax     = $request->property_tax[$key];
                    $quarters->action_by        = loginId();
                    $quarters->action_on        = currentDate();
                    $saved = $quarters->save();

                    $count = $count + $saved;
                }
            }

            DB::commit();

            // Save User Activity
            setUserActivity("A", "Unit Kuarters : " .$quarters->unit_no);

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('quarters.addUnitNo', ['quarters_cat_id' => $quarters_cat_id])->with('error', 'No unit kuarters tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

        return redirect()->route('quarters.index', ['quarters_cat_id' => $quarters_cat_id])->with('success', $count . ' No unit kuarters berjaya ditambah!');
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $quarters_cat_id = $request->quarters_cat_id;

        $quarters = Quarters::findOrFail($id);

        $quartersInventoryAll = QuartersInventory::where('q_id', $quarters->id)
                                ->where('data_status', 1)
                                ->get();

        $quartersImageAll = QuartersImage::where('quarters_id', $quarters->id)->where('status_data', 1)->get();

        // if(checkPolicy("V"))
        // {
            return view( getFolderPath().'.view',
            [
                'quarters' => $quarters,
                'quarters_cat_id' => $quarters_cat_id,
                'quartersInventoryAll' => $quartersInventoryAll,
                'quartersImageAll' => $quartersImageAll,
                'cdn' => config('env.upload_ftp_url')
            ]);
        // }
        // else
        // {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        //}
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $quarters_cat_id = $request->quarters_cat_id;

        $quartersCategoryAll = QuartersCategory::where('data_status', 1)->get();
        $districtAll =ListData::District();
        $inventoryAll = Inventory::where([['quarters_category_id',$quarters_cat_id],['data_status', 1]])->get();
        $quartersCategoryInventoryAll = QuartersCategoryInventory::where([['quarters_category_id', $quarters_cat_id],['data_status',1]])->get();
        $maintenanceInventoryAll = MaintenanceInventory::where('data_status', 1)->get();

        $quarters = Quarters::findOrFail($id);
        $quartersInventoryAll = $quarters->inventories->where('pivot.data_status', 1);
        $quartersConditionAll = QuartersCondition::where('data_status', 1)->get();
        $quartersImageAll = QuartersImage::where('quarters_id', $quarters->id)->where('status_data', 1)->get();

        $userOffice = UserOffice::where('data_status', 1)->where('users_id',loginId())->get();
        $userOffice2 = UserOffice::where('data_status', 1)->where('users_id',loginId())->first();

        // if(checkPolicy("U"))
        // {
            return view( getFolderPath().'.edit',
            [
                'quarters' => $quarters,
                'quarters_cat_id' => $quarters_cat_id,
                'quartersInventoryAll' => $quartersInventoryAll,
                'quartersCategoryAll' => $quartersCategoryAll,
                'quartersConditionAll' => $quartersConditionAll,
                'districtAll' => $districtAll,
                'inventoryAll' => $inventoryAll,
                'quartersCategoryInventoryAll' => $quartersCategoryInventoryAll,
                'quartersImageAll' => $quartersImageAll,
                'maintenanceInventoryAll' => $maintenanceInventoryAll,
                'userOffice2' => $userOffice2,
                'userOffice' => $userOffice,
                'cdn' => config('env.upload_ftp_url')
            ]);
        // }
        // else
        // {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        //}
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $quarters_cat_id = $request->quarters_cat_id;

        DB::beginTransaction();

        try {

            $quartersCategory = QuartersCategory::findOrFail($request->quarters_category);
            $quarters = Quarters::findOrFail($id);

            // User Activity - Set Data before changes
            $data_before = $quarters->getRawOriginal(); //dd($data_before);
            $data_before['item']= $quartersCategory->toArray() ?? []; //dd($data_before);

            $quarters->category()->associate($quartersCategory);
            $quarters->unit_no          = $request->unit_no;
            // $quarters->no_ptd           = $request->no_ptd;
            $quarters->address_1        = $request->address_1;
            $quarters->address_2        = $request->address_2;
            $quarters->address_3        = $request->address_3;
            $quarters->land_tax         = $request->land_tax;
            $quarters->property_tax     = $request->property_tax;
            $quarters->m_utility_id     = isset($request->iwk) ? 1 : null; // 1 = iwk
            $quarters->iwk_fee          = $request->iwk_fee;
            $quarters->maintenance_fee  = $request->maintenance_fee;
            $quarters->quarters_condition_id = $request->quarters_condition;
            $quarters->room_no          = $request->room_no;
            $quarters->bathroom_no      = $request->bathroom_no;
            $quarters->action_by        = loginId();
            $quarters->action_on        = currentDate();
            $quarters->save();

            $quarters_image_request = isset($request->quarters_picture) ? $request->quarters_picture: "";

            if($quarters_image_request)
            {
                foreach($quarters_image_request as $attachment)
                {
                    $path = $attachment->store('quarters_info', 'assets-upload');

                    $quartersImage                    = new QuartersImage;
                    $quartersImage ->quarters_id      = $quarters->id;
                    $quartersImage ->path_image       = $path;
                    $quartersImage ->status_data      = 1;
                    $quartersImage ->action_by        = loginId();
                    $quartersImage ->action_on        = currentDate();
                    $quartersImage ->save();
                }
            }

            // Uncheck inventory
            if($request->inventory){
                QuartersInventory::where('data_status', 1)
                                    ->where('q_id', $quarters->id)
                                    ->whereNotIn('i_id', array_keys($request->inventory)) // id inventory checked
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => loginId(),
                                        'delete_on' => currentDate()
                                    ]);

                // Update inventory new value
                foreach($request->inventory as $ivalue => $ikey)
                {
                    $quartersInventory = QuartersInventory::where('q_id', $quarters->id)
                                        ->where('i_id', $ivalue)
                                        ->where('data_status', 1)
                                        ->first();

                    if($quartersInventory != null) // update
                    {
                        $quartersInventory->quantity                               = isset($request->quantity[$ivalue]) ? $request->quantity[$ivalue] : 0;
                        $quartersInventory->m_inventory_id                         = isset($request->responsibility[$ivalue]) ? $request->responsibility[$ivalue] : 0;
                        $quartersInventory->action_by                              = loginId();
                        $quartersInventory->action_on                              = currentDate();
                        $quartersInventory->save();
                    }

                    if($quartersInventory == null) // insert
                    {
                        $quartersInventory = new QuartersInventory;
                        $quartersInventory->q_id                                   = $quarters->id;
                        $quartersInventory->i_id                                   = $ivalue;
                        $quartersInventory->quantity                               = isset($request->quantity[$ivalue]) ? $request->quantity[$ivalue] : 0;
                        $quartersInventory->m_inventory_id                         = isset($request->responsibility[$ivalue]) ? $request->responsibility[$ivalue] : 0;
                        $quartersInventory->action_by                              = loginId();
                        $quartersInventory->action_on                              = currentDate();
                        $quartersInventory->save();
                    }
                }

            }

            DB::commit();

            // User Activity - Set Data after changes
            $data_after = $quarters->toArray();
            $data_after['item'] = $quartersCategory->toArray() ?? [];

            // User Activity - Save
            setUserActivity("U", "Maklumat Kuarters Unit: " . $quarters->unit_no. ", " .$quartersCategory->name,  $data_before, $data_after);


        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('quarters.index', ['quarters_cat_id' => $quarters_cat_id])->with('error', 'Kuarters tidak berjaya dikemaskini!');
        }

        return redirect()->route('quarters.index', ['quarters_cat_id' => $quarters_cat_id])->with('success', ' Kuarters berjaya dikemaskini!');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;
        $quarters_cat_id = $request->quarters_cat_id;

        DB::beginTransaction();

        try {
            $quarters = Quarters::findOrFail($id);

            $quarters->data_status  = 0;
            $quarters->delete_by    = loginId();
            $quarters->delete_on    = currentDate();
            $quarters->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('quarters.edit', ['id'=>$request->id])->with('error', 'Kuarters tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('quarters.index', ['quarters_cat_id' => $quarters_cat_id])->with('success', 'Kuarters berjaya dihapus!');
    }

    public function destroyImage(Request $request)
    {

        $quartersImage = QuartersImage::where('status_data', 1)->where('id', $request->attachment_id)->first();

        $quartersImage->status_data  = 0;
        $quartersImage->delete_by    = loginId();
        $quartersImage->delete_on    = currentDate();

        $deleted = $quartersImage->save();

        if(!$deleted)
        {
            return redirect()->route('quarters.edit', ['id'=>$request->id, 'quarters_cat_id' => $request->quarters_cat_id])->with('error', 'Gambar Kuarters tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('quarters.edit', ['id'=>$request->id, 'quarters_cat_id' => $request->quarters_cat_id])->with('success', 'Gambar Kuarters berjaya dihapus!');
        }
    }

    public function saveQuartersStatus(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        $quarters_id   = $request->input('id_quarters') ? $request->input('id_quarters') : NULL;

        foreach($quarters_id as $i => $quarter_id){

            DB::beginTransaction();

            $quarters = Quarters::where('id', $quarter_id)->first();

            $data_status   = isset($request->data_status[$i]) ? $request->data_status[$i] : "2";

            $inactive_remarks = isset($request->input('inactive_remarks')[$i]) ? $request->input('inactive_remarks')[$i] : '';

            $quarters->data_status = $data_status;
            $quarters->inactive_remarks = $inactive_remarks;
            $quarters->action_by        = loginId();
            $quarters->action_on        = currentDate();
            $quarters->save();

            DB::commit();

        }

        return redirect()->route('quarters.index', ['quarters_cat_id' => $quarters_cat_id])->with('success', ' Kuarters berjaya dikemaskini!');

    }

    //  AJAX
    public function ajaxGetCategoryQuarters(Request $request)
    {
        $id = $request->id;

        $quartersCategory = QuartersCategory::where(['data_status'=> 1,'district_id'=>$id])->get();

        if($quartersCategory->count() == 0)
        {
            return response()->json(['error' => 'Tiada Kategori Kuarters (Lokasi)'], 404);
        }

        return response()->json(['data'=>$quartersCategory], 201);
    }

    public function ajaxGetCategoryQuartersData(Request $request)
    {
        $quarters_category_id  = $request->id;
        $quartersCategory  = QuartersCategory::find($quarters_category_id);

        $data['landed_type_id'] = $quartersCategory->landed_type->id;
        $data['landed_type'] = $quartersCategory->landed_type->type;

        //$data['quartersClassAll'] = QuartersClass::where('data_status', 1)->where('q_cat_id', $quarters_category_id)->get();

        return response()->json([
            'data' => $data
        ], 201);
    }

}
