<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuartersApplicationAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $districtId = (!is_all_district()) ?  districtId() : null;

        $tahun   = ($request->tahun) ? $request->tahun : Carbon::now()->format('Y');

        $tahunDBAll = Application::getDistinctYear();

        $months = collect([
            (object) ['bm' => 'Januari', 'month' => 1, 'applications_count' => 0],
            (object) ['bm' => 'Februari', 'month' => 2, 'applications_count' => 0],
            (object) ['bm' => 'Mac', 'month' => 3, 'applications_count' => 0],
            (object) ['bm' => 'April', 'month' => 4, 'applications_count' => 0],
            (object) ['bm' => 'Mei', 'month' => 5, 'applications_count' => 0],
            (object) ['bm' => 'Jun', 'month' => 6, 'applications_count' => 0],
            (object) ['bm' => 'Julai', 'month' => 7, 'applications_count' => 0],
            (object) ['bm' => 'Ogos', 'month' => 8, 'applications_count' => 0],
            (object) ['bm' => 'September', 'month' => 9, 'applications_count' => 0],
            (object) ['bm' => 'October', 'month' => 10, 'applications_count' => 0],
            (object) ['bm' => 'November', 'month' => 11, 'applications_count' => 0],
            (object) ['bm' => 'Disember', 'month' => 12, 'applications_count' => 0],
        ]);


        $monthlyApplications = Application::getApplicationMonthlyCount($tahun);

        $mergedResults = $months->map(function ($month) use ($monthlyApplications) {
            $foundMonth = $monthlyApplications->firstWhere('month', $month->month);

            if(isset($foundMonth->applications_count)) $month->applications_count = $foundMonth->applications_count;
            
            return $month;
        });

        $gtJumlah = $mergedResults->sum('applications_count');

        return view( getFolderPath().'.index',
        [
            'tahun' => $tahun,
            'tahunDBAll' => $tahunDBAll,
            'mergedResults' => $mergedResults,
            'gtJumlah' => $gtJumlah,
        ]);
    }
}
