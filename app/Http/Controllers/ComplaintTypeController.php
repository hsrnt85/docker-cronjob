<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplaintType;

class ComplaintTypeController extends Controller
{
    public function index()
    {
        $senaraiAduan = ComplaintType::where('data_status', 1)->get();

        return view('modules.SystemConfiguration.ComplaintType.list',
        [
            'senaraiAduan' => $senaraiAduan
        ]);
    }

    public function create()
    {
        return view('modules.SystemConfiguration.ComplaintType.create');
    }

    public function store(Request $request)
    {
        $complaintType = new ComplaintType;

        $complaintType->complaint_name = $request->complaint_name;
        $complaintType->data_status    = 1;
        $complaintType->action_by      = loginId();
        $complaintType->action_on      = currentDate();

        $saved = $complaintType->save();

        if(!$saved)
        {
            return redirect()->route('complaintType.create')->with('error', 'Jenis Aduan tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('complaintType.index')->with('success', 'Jenis Aduan berjaya ditambah!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $complaintType = ComplaintType::where('id', $id)->first();

        return view('modules.SystemConfiguration.ComplaintType.edit',
        [
            'complaintType' => $complaintType
        ]);
    }

    public function update(Request $request)
    {
        $complaintType = ComplaintType::where('id', $request->id)->first();

        $complaintType->complaint_name = $request->complaint_name;
        $complaintType->action_by     = loginId();
        $complaintType->action_on     = currentDate();

        $saved = $complaintType->save();

        if(!$saved)
        {
            return redirect()->route('complaintType.edit', ['id'=>$request->id])->with('error', 'Jenis Aduan tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('complaintType.index')->with('success', 'Jenis Aduan berjaya dikemaskini!');
        }
    }

    public function destroy(Request $request)
    {
        $complaintType = ComplaintType::where('id', $request->id)->first();

        $complaintType->data_status  = 0;
        $complaintType->delete_by    = loginId();
        $complaintType->delete_on    = currentDate();

        $deleted = $complaintType->save();

        if(!$deleted)
        {
            return redirect()->route('complaintType.edit', ['id'=>$request->id])->with('error', 'Jenis Aduan tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('complaintType.index')->with('success', 'Jenis Aduan berjaya dihapus!');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $complaintType = ComplaintType::where('id', $id)->first();

        return view('modules.SystemConfiguration.complaintType.view',
        [
            'complaintType' => $complaintType
        ]);
    }

}
