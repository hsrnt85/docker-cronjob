<?php

namespace App\Http\Controllers;

use App\Models\RoutineInspection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;


class RoutineInspectionScheduleController extends Controller
{
    public function index()
    {
        $hello = 'hello';

        return view( getFolderPath().'.index',
        [
            'hello' => $hello
        ]);
    }

    public function ajaxGetSenaraiPemantauan(Request $request)
    {
        $dateStr = substr($request->start, 0, 24);
        $dateStr = Carbon::parse($dateStr);
        $formattedDate = $dateStr->format('Y-m-d');
        $month = $dateStr->format('m');
        $inspectionAll = RoutineInspection::getAllInspectionByMonth($month);
        // $inspectionAll = RoutineInspection::getAllInspectionByMonth2($month);

        return response()->json([
            'formattedDate' => $formattedDate,
            'inspectionAll' => $inspectionAll,
        ], 200);
    }
}
