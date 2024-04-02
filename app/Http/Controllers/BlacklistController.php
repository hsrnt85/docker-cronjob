<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blacklist;
use Illuminate\Support\Facades\DB;
use App\HTTP\Requests\BlacklistRequest;


class BlacklistController extends Controller
{

    public function index()
    {
        $blacklistAll = Blacklist::where('data_status', 1)->get();

        return view(getFolderPath().'.list',
        [
            'blacklistAll' => $blacklistAll
        ]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $blacklist = Blacklist::findOrFail($id);

        return view(getFolderPath().'.edit',
        [
            'blacklist' => $blacklist
        ]);
    }

    public function update(BlacklistRequest $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $blacklist = Blacklist::findOrFail($id);

            $blacklist->range  = $request->range;
            $blacklist->action_by   = loginId();
            $blacklist->action_on   = currentDate();

            $blacklist->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('blacklist.edit', ['id' => $request->id])->with('error', 'Tempoh senarai hitam tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('blacklist.index')->with('success', 'Tempoh senarai hitam berjaya dikemaskini!');
    }
}
