<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\SpecialPermission;
use App\Models\User;

class SpecialPermissionReportController extends Controller
{
    public function specialPermissionList(Request $request)
    {
        $districts = District::all();
        $title = ''; 

        $carian_daerah = $request->input('carian_daerah', null);
        $search_new_ic = $request->input('new_ic', null);
        $search_name = $request->input('name', null);

        $specialPermissionsQuery = SpecialPermission::where('data_status', 1);

        if ($carian_daerah !== null) {
            // Retrieve the district name based on the district ID
            $districtName = District::where('id', $carian_daerah)->value('district_name');
            $title = "DAERAH: " . strtoupper($districtName);
            $specialPermissionsQuery->whereHas('user.addressOffice', function ($query) use ($carian_daerah) {
                $query->where('district_id', $carian_daerah);
            });
        } elseif ($search_new_ic !== null) {
            // Search by new_ic in users table
            $title = "NO. KAD PENGENALAN: " . strtoupper($search_new_ic);
            $specialPermissionsQuery->whereHas('user', function ($query) use ($search_new_ic) {
                $query->where('new_ic', 'like', "%$search_new_ic%");
            });
        } elseif ($search_name !== null) {
            // Search by name in users table
            $title = "NAMA: " . strtoupper($search_name);
            $specialPermissionsQuery->whereHas('user', function ($query) use ($search_name) {
                $query->where('name', 'like', "%$search_name%");
            });
        }

        $specialPermissions = $specialPermissionsQuery->get();
        $userIds = $specialPermissions->pluck('user_id')->unique()->toArray();

        $users = User::whereIn('id', $userIds)
            ->with('latest_user_info')
            ->with('services_type')
            ->with(['addressOffice.organization'])
            ->get();

        if ($request->input('muat_turun_pdf')) {
            $dataReturn = [
                'title' => $title,
                'specialPermissions' => $specialPermissions,
                'users' => $users,
            ];
            
            //------------------------------------------------------------------------------------------------------
            $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
            $tempPdf->setPaper('A4', 'landscape');
            $tempPdf->output();
            // Get the total page count
            $totalPages = $tempPdf->getCanvas()->get_page_count();
            //------------------------------------------------------------------------------------------------------

            $pdf = PDF::loadview(getFolderPath().'.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
            $pdf->setPaper('A4', 'landscape'); 
            return $pdf->stream('Laporan_Kebenaran_Khas_Kuarters_'.date("dmY-His").'.pdf');

        } else {
            return view(getFolderPath() . '.index', [
                'districts' => $districts,
                'carian_daerah' => $carian_daerah,
                'specialPermissions' => $specialPermissions,
                'users' => $users,
                'search_new_ic' => $search_new_ic,
                'search_name' => $search_name,
            ]);
        }
    }

}
