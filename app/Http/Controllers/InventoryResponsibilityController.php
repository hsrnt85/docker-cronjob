<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MaintenanceInventory;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\InventoryResponsibilityRequest;
use App\Http\Resources\ListValidateDelete;

class InventoryResponsibilityController extends Controller
{
    public function index()
    {

        $senaraiTanggungjawabInventori = MaintenanceInventory::where('data_status', 1)->orderBy('name')->get();

        return view( getFolderPath().'.list',
        [
            'senaraiTanggungjawabInventori' => $senaraiTanggungjawabInventori
        ]);
    }

    public function create()
    {
        $inventoryResponsibilityAll = MaintenanceInventory::where('data_status', 1)->get();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'inventoryResponsibilityAll' => $inventoryResponsibilityAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(InventoryResponsibilityRequest $request)
    {

        $inventoryResponsibility = new MaintenanceInventory;

        $inventoryResponsibility->name        = $request->name;
        $inventoryResponsibility->action_by    = loginId();
        $inventoryResponsibility->action_on    = currentDate();

        $saved = $inventoryResponsibility->save();

        //------------------------------------------------------------------------------------------------------------------
        // Save User Activity
        //------------------------------------------------------------------------------------------------------------------
        setUserActivity("A", $inventoryResponsibility->name);
        //------------------------------------------------------------------------------------------------------------------


        if(!$saved)
        {
            return redirect()->route('inventoryResponsibility.create')->with('error', 'Jabatan Bertanggungjawab (Inventori) tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('inventoryResponsibility.index')->with('success', 'Jabatan Bertanggungjawab (Inventori) berjaya ditambah!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $inventoryResponsibility = MaintenanceInventory::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'inventoryResponsibility' => $inventoryResponsibility
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(InventoryResponsibilityRequest $request)
    {

        $inventoryResponsibility = MaintenanceInventory::where('id', $request->id)->first();

        // User Activity - Set Data before changes
        $data_before = $inventoryResponsibility->getRawOriginal();
        
        $inventoryResponsibility->name        = $request->name;
        $inventoryResponsibility->action_by    = loginId();
        $inventoryResponsibility->action_on    = currentDate();

        $saved = $inventoryResponsibility->save();

        // User Activity - Set Data after changes
        $data_after = $inventoryResponsibility->toArray();
        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);

        // User Activity - Save
        setUserActivity("U", $inventoryResponsibility->name, $data_before_json, $data_after_json);


        if(!$saved)
        {
            return redirect()->route('inventoryResponsibility.edit', ['id'=>$request->id])->with('error', 'Jabatan Bertanggungjawab (Inventori) tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('inventoryResponsibility.index')->with('success', 'Jabatan Bertanggungjawab (Inventori) berjaya dikemaskini!');
        }
    }

    public function destroy(Request $request)
    {
        $inventoryResponsibility = MaintenanceInventory::where('id', $request->id)->first();

        setUserActivity("D", $inventoryResponsibility->name);

        $inventoryResponsibility->data_status  = 0;
        $inventoryResponsibility->delete_by    = loginId();
        $inventoryResponsibility->delete_on    = currentDate();

        $deleted = $inventoryResponsibility->save();

        if(!$deleted)
        {
            return redirect()->route('inventoryResponsibility.edit', ['id'=>$request->id])->with('error', 'Jabatan Bertanggungjawab (Inventori) tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('inventoryResponsibility.index')->with('success', 'Jabatan Bertanggungjawab (Inventori) berjaya dihapus!');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $inventoryResponsibility = MaintenanceInventory::where('id', $id)->first();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'inventoryResponsibility' => $inventoryResponsibility
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function validateDelete(Request $request){

        $id = $request->id;
        $data = ListValidateDelete::validateInventoryResponsibility($id);
        return response()->json($data);
    }


}


