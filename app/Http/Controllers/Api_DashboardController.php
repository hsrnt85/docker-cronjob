<?php

namespace App\Http\Controllers;
use App\Models\Api\Api_Complaint;
use App\Models\Api\Api_ComplaintMonitoring;
use App\Models\Api\Api_ComplaintAppointment;
use App\Models\Api\Api_RoutineInspection;
use App\Models\Api\Api_TenantLeaveController;
use Illuminate\Http\Request;

class Api_DashboardController extends Controller
{

    public function dashboardInspector(Request $request)
    {
        $user       = auth('sanctum')->user();
        $userId     = auth('sanctum')->user()->id;
        $officer    = $user->officer();
        $officer_id = ($officer) ? $officer->id : 0;

        $start  = $request->startDate;
        $end    = $request->endDate;


        $pemantauanKerosakanBaru     = Api_Complaint::getComplaintDamagePendingMonitor($userId, $start, $end, 'get');
        $pemantauanAwamBaru          = Api_Complaint::getComplaintViolationPendingMonitor($userId, $start, $end, 'get');

        $pemantauanKerosakanDitolak  = Api_Complaint::getAduanKerosakanDitolak($userId, $start, $end, 'get');
        $pemantauanAwamDitolak       = Api_Complaint::getAduanAwamDitolak($userId, $start, $end, 'get');

        $pemantauanDiselenggara      = Api_Complaint::getAduanDiselenggara($userId, $start, $end, 'get');
        $pemantauanAwamBerulang      = Api_ComplaintMonitoring::getPemantauanBerulang($userId, $start, $end, 'get');

        $pemantauanKerosakanSelesai  = Api_Complaint::getComplaintDamageCompleteMonitor($userId, $start, $end, 'get');
        $pemantauanAwamSelesai       =  Api_ComplaintMonitoring::getCompletedMonitoring($userId, $start, $end, 'get');

        $pemantauanBerkala           = Api_RoutineInspection::countRoutineInspectionActive($officer_id, $start, $end,);
        $pengesahanPemantauanBerkala = Api_RoutineInspection::countRoutineInspectionApproved($officer_id);

        return response()->json([

            'countPemantauanKerosakanBaru'=> count($pemantauanKerosakanBaru),
            'countPemantauanAwamBaru'     => count($pemantauanAwamBaru),
            'countPemantauanKerosakanDitolak'     => count($pemantauanKerosakanDitolak),
            'countPemantauanAwamDitolak'     => count($pemantauanAwamDitolak),
            'countPemantauanKerosakanSelesai' => count($pemantauanKerosakanSelesai),
            'countPemantauanAwamSelesai' => count($pemantauanAwamSelesai),
            'countPemantauanAwamBerulang' => count($pemantauanAwamBerulang),
            'countPemantauanDiselenggara' => count($pemantauanDiselenggara),
            'countPemantauanBerkala' => $pemantauanBerkala,
            'countPengesahanPemantauanBerkala' => $pengesahanPemantauanBerkala,

        ], 200);
    }

    public function dashboardTenants(Request $request)
    {
        $userId = auth('sanctum')->user()->id;

        $totalAduanBaru    = Api_Complaint::getActiveComplaintList($userId);
        $totalAduanSelesai = Api_Complaint::getCompletedComplaintList($userId, 'get');
        $totalAduanDitolak = Api_Complaint::getRejectedComplaint($userId, 'get');
        $temujanjiBaru     = Api_ComplaintAppointment::getPendingAppointmentByTenants($userId, 'get');
        $senaraiTemujanji  = Api_ComplaintAppointment::getLatestAppointmentByTenants($userId);
        $temujanjiBatal    =  Api_ComplaintAppointment::getCancelAppointmentList($userId);

        return response()->json([

            'countAduanBaru'      => count($totalAduanBaru),
            'countAduanSelesai'    => count($totalAduanSelesai),
            'countAduanDitolak'     => count($totalAduanDitolak),
            'countTemujanjiBaru'     => count($temujanjiBaru),
            'countSenaraiTemujanji'   => count($senaraiTemujanji),
            'countTemujanjiDibatalkan' => count($temujanjiBatal)

        ], 200);
    }

}
