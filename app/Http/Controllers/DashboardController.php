<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Quarters;
use App\Models\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function ajaxGetQuartersNoByCondition(Request $request)
    {
        $flag = $request->flag;

       // $condition_id_2 = (isset($request->condition_id_2)) ? $request->condition_id_2 : null;

        $district_id = null;

        $quartersCount   = Quarters::getDashboardQuartersNoByCondition($district_id, $flag);

        return response()->json([
            'data' => $quartersCount
        ], 201);

    }

    public function ajaxGetQuartersByCondition()
    {

        $district_id = (!is_all_district()) ?  districtId() : null;

        $quartersCount   = Quarters::getDashboardQuartersByCondition($district_id);

        return response()->json([
            'data' => $quartersCount
        ], 201);

    }

    public function ajaxGetQuartersTotal(Request $request)
    {
        $condition_id    = $request->condition_id;

        $district_id = (!is_all_district()) ?  districtId() : null;

        $quartersCount   = Quarters::getAllQuartersTotal($condition_id, $district_id);

        return response()->json([
            'data' => $quartersCount
        ], 201);

    }

    public function ajaxGetQuartersAvailability(Request $request)
    {
        $district_id = (!is_all_district()) ?  districtId() : null;

        $emptyQuartersCount     = Quarters::getAvailableUnitAll($district_id);

        return response()->json([
            'data' => $emptyQuartersCount,
        ], 201);
    }

    public function ajaxGetQuartersWithTenant(Request $request)
    {
        $district_id = (!is_all_district()) ?  districtId() : null;

        $tenantAllCount = Tenant::getAllCurrentTenantsCount($district_id);

        return response()->json([
            'data' => $tenantAllCount,
        ], 201);
    }

    public function ajaxGetComplaint(Request $request)
    {
        $complaint_type = $request->complaint_type;
        $complaint_status_id = $request->complaint_status_id;

        $district_id = (!is_all_district()) ?  districtId() : null;

        $tenantAllCount = Complaint::getDashboardComplaint($district_id, $complaint_type, $complaint_status_id);

        return response()->json([
            'data' => $tenantAllCount,
        ], 201);
    }
}
