<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\QuartersCategory;
use App\Models\Quarters;
use App\Models\Complaint;

class DamageComplaintAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $districtId = (!is_all_district()) ?  districtId() : null;

        $from   = ($request->carian_tarikh_aduan_dari) ? $request->carian_tarikh_aduan_dari : Carbon::now()->startOfMonth()->format('Y-m-d');
        $to     = ($request->carian_tarikh_aduan_hingga) ? $request->carian_tarikh_aduan_hingga : Carbon::now()->endOfMonth()->format('Y-m-d');
        $categoryId = ($request->carian_id_kategori) ? $request->carian_id_kategori : null;

        $quartersCategoryAll = QuartersCategory::getAllQuartersCategory($districtId);

        $damageStatisticByQuartersCategory = QuartersCategory::getDamageStatistic($from, $to, $categoryId, $districtId);

        $gtJumlah = $damageStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('jumlah');
        });
        
        $gtDalamPemantauan = $damageStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('dalam_pemantauan');
        });

        $gtDalamPenyelenggaraan = $damageStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('dalam_penyelenggaraan');
        });

        $gtDitolak = $damageStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('ditolak');
        });

        $gtSelesai = $damageStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('selesai');
        });

        $chartData = [];
        // $chartData["bg"] = ['Blue', 'Orange', 'Grey', 'Yellow'];
        $chartData["bg"] = ['#bada55', '#f69471', '#00b3b3', '#a67391'];
        $chartData["labels"] = ['Dalam Pemantauan', 'Dalam Penyelenggaraan', 'Aduan Ditolak', 'Aduan Selesai'];
        $chartData["data"] = [$gtDalamPemantauan, $gtDalamPenyelenggaraan, $gtDitolak, $gtSelesai];

        return view( getFolderPath().'.index',
        [
            'from' => $from,
            'to' => $to,
            'categoryId' => $categoryId,
            'quartersCategoryAll' => $quartersCategoryAll,
            'damageStatisticByQuartersCategory' => $damageStatisticByQuartersCategory,
            'gtJumlah' => $gtJumlah,
            'gtDalamPemantauan' => $gtDalamPemantauan,
            'gtDalamPenyelenggaraan' => $gtDalamPenyelenggaraan,
            'gtDitolak' => $gtDitolak,
            'gtSelesai' => $gtSelesai,
            'chartData' => json_encode($chartData),
        ]);
    }
}
