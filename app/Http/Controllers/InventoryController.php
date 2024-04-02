<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\QuartersCategory;
use Illuminate\Support\Facades\DB;
use App\HTTP\Requests\InventoryRequest;
use App\Http\Resources\ListValidateDelete;

class InventoryController extends Controller
{
    public function indexKategoriKuarters()
    {


        $quartersCategoryAll = QuartersCategory::select('quarters_category.id','quarters_category.name', DB::raw('(SELECT COUNT(inventory.id) FROM inventory where data_status =1 AND quarters_category.id = inventory.quarters_category_id)AS total_inventory'))
        ->where([['district_id','=',districtId()],['data_status', 1]])
        ->orderBy('name','ASC')
        ->get();

        return view( getFolderPath().'.list-quarters-category',
        [
            'quartersCategoryAll' => $quartersCategoryAll
        ]);
    }

    public function index(Request $request)
    {


        $quarters_cat_id = $request->quarters_cat_id;

        $senaraiInventori = Inventory::where([['quarters_category_id','=',$quarters_cat_id],['data_status', 1]])->orderBy('name')->get();

        return view( getFolderPath().'.list',
        [
            'senaraiInventori' => $senaraiInventori,
            'quarters_cat_id' => $quarters_cat_id,
        ]);
    }

    public function create(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'quarters_cat_id' => $quarters_cat_id,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }


    }

    public function store(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        $inventory = new Inventory;

        $inventory->quarters_category_id = $quarters_cat_id;
        $inventory->name                 = $request->name;
        $inventory->price                = $request->price;
        $inventory->action_by            = loginId();
        $inventory->action_on            = currentDate();

        $saved = $inventory->save();

        // Save User Activity
        setUserActivity("A", $inventory->name);

        if(!$saved)
        {
            return redirect()->route('inventory.create',['quarters_cat_id' => $quarters_cat_id])->with('error', 'Inventori tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('inventory.index',['quarters_cat_id' => $quarters_cat_id])->with('success', 'Inventori berjaya ditambah!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $quarters_cat_id = $request->quarters_cat_id;
        $inventory = Inventory::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'inventory' => $inventory,
                'quarters_cat_id' => $quarters_cat_id
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }


    }

    public function update(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        $inventory = Inventory::where('id', $request->id)->first();
        
        
        // User Activity - Set Data before changes
        $data_before = $inventory->getRawOriginal();

        $inventory->quarters_category_id = $quarters_cat_id;
        $inventory->name                 = $request->name;
        $inventory->price                = $request->price;
        $inventory->action_by            = loginId();
        $inventory->action_on            = currentDate();

        $saved = $inventory->save();

        // User Activity - Set Data after changes
        $data_after = $inventory->toArray();
        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);

        // User Activity - Save
        setUserActivity("U", $inventory->name, $data_before_json, $data_after_json);
        
        if(!$saved)
        {
            return redirect()->route('inventory.edit', ['quarters_cat_id' => $quarters_cat_id , 'id'=>$request->id])->with('error', 'Inventori tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('inventory.index', ['quarters_cat_id' => $quarters_cat_id ])->with('success', 'Inventori berjaya dikemaskini!');
        }
    }

    public function destroy(Request $request)
    {
        $quarters_cat_id = $request->quarters_cat_id;

        $inventory = Inventory::where([['id', $request->id],['quarters_category_id', $quarters_cat_id]])->first();

        // Save User Activity
        setUserActivity("D", $inventory->name);

        // dd($inventory);
        $inventory->data_status  = 0;
        $inventory->delete_by    = loginId();
        $inventory->delete_on    = currentDate();

        $deleted = $inventory->save();

        //$deleted = Inventory::find($request->id)->delete();

        if(!$deleted)
        {
            return redirect()->route('inventory.edit', ['quarters_cat_id' => $quarters_cat_id , 'id'=>$request->id])->with('error', 'Inventori tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('inventory.index',['quarters_cat_id' => $quarters_cat_id ])->with('success', 'Inventori berjaya dihapus!');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $quarters_cat_id = $request->quarters_cat_id;

        $inventory = Inventory::where([['id', $id],['quarters_category_id', $quarters_cat_id],['data_status',1]])->first();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'inventory' => $inventory,
                'quarters_cat_id' => $quarters_cat_id
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function validateDelete(Request $request){

        $id = $request->id;
        $data = ListValidateDelete::validateInventory($id);
        return response()->json($data);
    }

}
