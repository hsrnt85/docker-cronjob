<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\UserActivityLog;
use App\Models\ConfigMenu;
use App\Models\ConfigSubmenu;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $menu = ConfigMenu::all();
        $submenu = ConfigSubmenu::all();
        $user = User::all();

        $search_date_from = ($request->date_from) ? $request->date_from : null;
        $search_date_to = ($request->date_to) ? $request->date_to : null;
        $name = ($request->name) ? ($request->name) : null;
        $module_id = ($request->module_id) ? ($request->module_id) : null;
        $submodule_id = ($request->submodule_id) ? ($request->submodule_id) : null;

        // dd($search_date_from, $search_date_to,$name,$module_id,$submodule_id);
      
        $query = UserActivityLog::query();

        // if ($search_date_from) {
        //     $query->where('action_on', '>=', $search_date_from);
        // }
        // if ($search_date_to) {
        //     $query->where('action_on', '<=', $search_date_to);
        // }
        
        if(!$search_date_from && !$search_date_to){
            $query = $query->whereDate('action_on', '>=', currentDateDb());
            $query = $query->whereDate('action_on', '<=', currentDateDb());
        }

        if ($search_date_from) {
            // Convert $search_date_from to the yyyy-mm-dd format for querying
            $search_date_from_query = Carbon::createFromFormat('d/m/Y', $search_date_from)->format('Y-m-d');
            $query = $query->whereDate('action_on', '>=', $search_date_from_query);
        }
        
        if ($search_date_to) {
            // Convert $search_date_to to the yyyy-mm-dd format for querying
            $search_date_to_query = Carbon::createFromFormat('d/m/Y', $search_date_to)->format('Y-m-d');
            $query = $query->whereDate('action_on', '<=', $search_date_to_query);
        }

        
        // Convert the date variables back to the d/m/Y format for display
        if ($search_date_from) {
            $search_date_from = Carbon::createFromFormat('Y-m-d', $search_date_from_query)->format('d/m/Y');
        }
        if ($search_date_to) {
            $search_date_to = Carbon::createFromFormat('Y-m-d', $search_date_to_query)->format('d/m/Y');
        }
        if ($name) { 
            $query = $query->where('users_id', $name);
        }
        if ($module_id) {
            $query = $query->where('module_id', $module_id);
        }
        if ($submodule_id) {
            $query = $query->where('submodule_id', $submodule_id);
        }
        
        $filteredUserActivityLog = $query->orderBy('action_on', 'DESC')->get();

        if ($request->input('muat_turun_pdf')) {
            
            $data = [
                'search_date_from' => $search_date_from,
                'search_date_to'   => $search_date_to,
                'userActivityLog' => $filteredUserActivityLog,
                'menu'   => $menu,
                'submenu' => $submenu,
                'module_id' => $module_id,
                'submodule_id' => $submodule_id,
                'user' => $user,
            ];

            $pdf = PDF::loadView(getFolderPath() . '.cetak-pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan_Kawalan_Audit_' . date("dmY-His") . '.pdf');
            
        } else {

            $selectedName = $name ? User::find($name)->name : null;
            $selectedModuleName = $module_id ? ConfigMenu::find($module_id)->menu : null;
            $selectedSubmoduleName = $submodule_id ? ConfigSubmenu::find($submodule_id)->submenu : null;

            return view(getFolderPath().'.index',  [
                'search_date_from' => $search_date_from,
                'search_date_to'   => $search_date_to,
                'userActivityLog' => $filteredUserActivityLog,
                'menu'   => $menu,
                'submenu' => $submenu,
                'module_id' => $module_id,
                'submodule_id' => $submodule_id,
                'user' => $user,
                'selectedName' => $selectedName,
                'selectedModuleName' => $selectedModuleName,
                'selectedSubmoduleName' => $selectedSubmoduleName,
            ]);
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        try {
            $userActivityLog = UserActivityLog::findOrFail($id);

            if (checkPolicy("V")) {
                return view(getFolderPath() . '.view', [
                    'userActivityLog' => $userActivityLog,
                ]);
            } else {
                return redirect()->route('dashboard')->with('error-permission', 'access.denied');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dashboard')->with('error', 'Log not found');
        }
    }
   
    public function ajaxGetSubmodule(Request $request)
    {
        try {
            $module_id = $request->input('module_id');  
            $submodules = ConfigSubmenu::where('config_menu_id', $module_id)->get();

            return response()->json(['submodules' => $submodules]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
