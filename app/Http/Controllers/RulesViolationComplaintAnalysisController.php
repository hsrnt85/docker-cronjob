<?php

namespace App\Http\Controllers;

use App\Models\QuartersCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RulesViolationComplaintAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $districtId = (!is_all_district()) ?  districtId() : null;

        $from   = ($request->carian_tarikh_aduan_dari) ? $request->carian_tarikh_aduan_dari : Carbon::now()->startOfMonth()->format('Y-m-d');
        $to     = ($request->carian_tarikh_aduan_hingga) ? $request->carian_tarikh_aduan_hingga : Carbon::now()->endOfMonth()->format('Y-m-d');
        $categoryId = ($request->carian_id_kategori) ? $request->carian_id_kategori : null;

        $complaintStatisticByQuartersCategory = QuartersCategory::getComplaintStatistic($from, $to, $categoryId, $districtId);
        $quartersCategoryAll = QuartersCategory::getAllQuartersCategory($districtId);

        $gtComplaints = $complaintStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('total_complaints');
        });

        $gtNewComplaints = $complaintStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('new_complaints');
        });

        $gtActiveComplaints = $complaintStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('active_complaints');
        });

        $gtRejectedComplaints = $complaintStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('rejected_complaints');
        });

        $gtDoneComplaints = $complaintStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('done_complaints');
        });

        $gtRecurringComplaints = $complaintStatisticByQuartersCategory->sum(function ($category) {
            return $category->quarters->sum('recurring_complaints');
        });

        // {"labels": ["Red", "Blue", "Yellow"], "data": [10, 20, 30]}

        $chartData = [];
        $chartData["bg"] = ['#bada55', '#f69471', '#00b3b3', '#a67391', '#99b3cc'];
        $chartData["labels"] = ['Dalam Pengesahan', 'Dalam Pemantauan', 'Aduan Ditolak', 'Pemantauan Berulang', 'Aduan Selesai'];
        $chartData["data"] = [$gtNewComplaints, $gtActiveComplaints, $gtRejectedComplaints, $gtRecurringComplaints, $gtDoneComplaints];

        return view( getFolderPath().'.index',
        [
            'from' => $from,
            'to' => $to,
            'categoryId' => $categoryId,
            'complaintStatisticByQuartersCategory' => $complaintStatisticByQuartersCategory,
            'gtComplaints' => $gtComplaints,
            'gtNewComplaints' => $gtNewComplaints,
            'gtActiveComplaints' => $gtActiveComplaints,
            'gtRejectedComplaints' => $gtRejectedComplaints,
            'gtDoneComplaints' => $gtDoneComplaints,
            'gtRecurringComplaints' => $gtRecurringComplaints,
            'quartersCategoryAll' => $quartersCategoryAll,
            'chartData' => json_encode($chartData)
        ]);
    }
}
