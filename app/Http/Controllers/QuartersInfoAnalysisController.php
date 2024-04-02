<?php

namespace App\Http\Controllers;

use App\Models\Quarters;
use App\Models\QuartersCategory;
use App\Models\QuartersCondition;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuartersInfoAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $districtId = (!is_all_district()) ? districtId() : null;

        $is_carian = (bool) $request->kategori_kuarters;
        $carian_kategori = isset($request->kategori_kuarters) ? $request->kategori_kuarters : null;
        $carian_status = isset($request->status_kuarters) ? $request->status_kuarters : null;
        $carian_kekosongan = isset($request->kekosongan) ? $request->kekosongan :null;

        $quartersCategoryAll = QuartersCategory::getAllQuartersCategory($districtId);
        $conditionAll = QuartersCondition::getAllConditions();

        if(!$is_carian)
        {
            $statistic = QuartersCategory::getQuartersCategoryStatistic();

            if ($districtId) $statistic = $statistic->where('district_id', $districtId);

            $statistic = $statistic->get();

            $gtTotalQuarters = $statistic->sum('total_quarters');
            $gtTotalHasTenant = $statistic->sum('total_has_tenant');
            $gtTotalRosak = $statistic->sum('total_rosak');
            $gtTotalSelenggara = $statistic->sum('total_selenggara');
            $gtTotalAvailable = $statistic->sum('total_empty') + $statistic->sum('total_left');
        }
        else
        {
            $statistic = ($carian_status) ? Quarters::getStatisticByQuartersCategory($carian_kategori, $carian_status, $carian_kekosongan) : Quarters::getStatisticByQuartersCategory($carian_kategori);
        }

        return view( getFolderPath().'.index',
        [
            'quartersCategoryAll' => $quartersCategoryAll,
            'conditionAll' => $conditionAll,
            'is_carian' => $is_carian,
            'carian_kategori' => $carian_kategori,
            'carian_status' => $carian_status,
            'carian_kekosongan' => $carian_kekosongan,
            'statistic' => $statistic,
            'gtTotalQuarters' => isset($gtTotalQuarters) ? $gtTotalQuarters : '0',
            'gtTotalHasTenant' => isset($gtTotalHasTenant) ? $gtTotalHasTenant : '0' ,
            'gtTotalRosak' => isset($gtTotalRosak) ? $gtTotalRosak : '0',
            'gtTotalSelenggara' => isset($gtTotalSelenggara) ? $gtTotalSelenggara : '0',
            'gtTotalAvailable' => isset($gtTotalAvailable) ? $gtTotalAvailable : '0',
        ]);
    }
}
