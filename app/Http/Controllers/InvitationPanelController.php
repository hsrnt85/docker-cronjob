<?php

namespace App\Http\Controllers;

use App\Models\InvitationPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\InvitationPanelRequest;
use App\Http\Resources\ListValidateDelete;

class InvitationPanelController extends Controller
{
    public function index()
    {


        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        if($district_id)
        {
            $invitationPanelAll = InvitationPanel::where('data_status', 1)->where('district_id', $district_id)->get();
        }
        else
        {
            $invitationPanelAll = InvitationPanel::where('data_status', 1)->get();
        }

        return view( getFolderPath().'.list',
        [
            'invitationPanelAll' => $invitationPanelAll
        ]);
    }

    public function create()
    {
        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create');
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(InvitationPanelRequest $request)
    {
        $district_id = districtId();

        $invitationPanel = new InvitationPanel;
        $invitationPanel->district_id  = $district_id;
        $invitationPanel->name  = $request->name;
        $invitationPanel->email = $request->email;
        $invitationPanel->position = $request->position;
        $invitationPanel->department = $request->department;
        $invitationPanel->action_by   = loginId();
        $invitationPanel->action_on   = currentDate();
        $saved = $invitationPanel->save();

        //------------------------------------------------------------------------------------------------------------------
        // Save User Activity
        //------------------------------------------------------------------------------------------------------------------
        setUserActivity("A", $invitationPanel->name);
        //------------------------------------------------------------------------------------------------------------------


        if(!$saved)
        {
            return redirect()->route('invitationPanel.create')->with('error', 'Pendaftaran Panel Luar tidak berjaya!');
        }
        else
        {
            return redirect()->route('invitationPanel.index')->with('success', 'Pendaftaran Panel Luar berjaya!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $invitationPanel = InvitationPanel::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'invitationPanel' => $invitationPanel
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(Request $request)
    {
        $district_id = districtId();

        $invitationPanel = InvitationPanel::where('id', $request->id)->first();

        //------------------------------------------------------------------------------------------------------------------
        // User Activity - Set Data before changes
        $data_before = $invitationPanel->getRawOriginal();
        $data_before['item'] = [$invitationPanel->toArray()]; // Wrap the array in another array
        //------------------------------------------------------------------------------------------------------------------

        $invitationPanel->district_id  = $district_id;
        $invitationPanel->name  = $request->name;
        $invitationPanel->email = $request->email;
        $invitationPanel->position = $request->position;
        $invitationPanel->department = $request->department;
        $invitationPanel->action_by    = loginId();
        $invitationPanel->action_on    = currentDate();

        // Saving the changes
        DB::beginTransaction();

        try {
            $saved = $invitationPanel->save();

            if (!$saved) {
                throw new \Exception('Panel Luar tidak berjaya dikemaskini!');
            }

            // Committing the transaction
            DB::commit();

            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            $data_after = $invitationPanel->fresh();
            $data_after['item'] = [$data_after->toArray()]; // Wrap the array in another array
            //------------------------------------------------------------------------------------------------------------------

            // User Activity - Save
            setUserActivity("U", $invitationPanel->name, $data_before, $data_after);

            return redirect()->route('invitationPanel.index')->with('success', 'Panel Luar telah dikemaskini!');
        } catch (\Exception $e) {
            // Rolling back the transaction
            DB::rollback();

            return redirect()->route('invitationPanel.edit', ['id' => $request->id])->with('error', $e->getMessage());
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $invitationPanel = InvitationPanel::find($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'invitationPanel' => $invitationPanel
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function destroy(Request $request)
    {
        $invitationPanel = InvitationPanel::where('id', $request->id)->first();

        setUserActivity("D", $invitationPanel->name);

        $invitationPanel->data_status  = 0;
        $invitationPanel->delete_by    = loginId();
        $invitationPanel->delete_on    = currentDate();

        $deleted = $invitationPanel->save();

        if(!$deleted)
        {
            return redirect()->route('invitationPanel.edit', ['id'=>$request->id])->with('error', 'User tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('invitationPanel.index')->with('success', 'Panel Luar telah dihapus!');
        }
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateInvitationPanel($id);
        return response()->json($data);
    }
}
