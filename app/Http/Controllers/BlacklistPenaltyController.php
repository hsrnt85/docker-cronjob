<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlacklistPenaltyPostRequest;
use App\Models\BlacklistPenalty;
use App\Models\BlacklistPenaltyRate;
use App\Models\BlacklistPenaltyRateList;
use App\Models\BlacklistReason;
use App\Models\QuartersCategory;
use App\Models\Tenant;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlacklistPenaltyController extends Controller
{
    public function index()
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $categoryAll = QuartersCategory::getDistinctQuartersCategoryForTenant($district_id);

        return view(
            getFolderPath() . '.list',
            [
                'categoryAll' => $categoryAll
            ]
        );
    }

    public function penalty_list(QuartersCategory $category)
    {
        if (!checkPolicy("V")) {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }

        $tenantsPenalty = BlacklistPenalty::getAllBlacklistPenaltyByQuartersCategory($category->id);

        return view(
            getFolderPath() . '.list_penalty',
            [
                'tenantsPenalty' => $tenantsPenalty,
                'category' => $category,
            ]
        );
    }

    public function create(QuartersCategory $category, Request $request)
    {
        if (!checkPolicy("A")) {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }

        // $currentTenantAll = Tenant::getAllCurrentTenantsByCategory($category->id);
        $blacklistRates = BlacklistPenaltyRate::where('data_status', 1)->orderBy('effective_date', 'desc')->first()->rates->where('data_status', 1);
        $blacklistReasonAll = BlacklistReason::where('data_status', 1)->get();

        $tenant = null;

        if ($request->gtid) {
            $tenant = Tenant::getSingleTenant($category->id, $request->gtid);
        }

        return view(getFolderPath() . '.create', [
            'category' => $category,
            // 'currentTenantAll' => $currentTenantAll,
            'blacklistRates' => $blacklistRates,
            'blacklistReasonAll' => $blacklistReasonAll,
            'tenant' => $tenant,
        ]);
    }

    public function store(BlacklistPenaltyPostRequest $request)
    {
        $district           = QuartersCategory::where('id', $request->q_category_id)->first()->district;
        $tenant             = Tenant::getSingleTenant($request->q_category_id, $request->tenant_id);
        $initialDate        = convertDatepickerDb($request->penalty_date);
        $subsequentMonths   = $this->_getMonthListFromDate(Carbon::createFromFormat('Y-m-d', $initialDate));

        DB::beginTransaction();
        try {

            // First month
            $curr_running_no    = $this->_getCurrentRunningNo();
            $ref_no             = $this->_generateRefNo($curr_running_no, $district->district_code);
            $monthsApart        = $this->_calculateMonthsApart($initialDate, $initialDate);
            $selectedRate       = BlacklistPenaltyRate::getRateBasedOnMonthsApart($monthsApart, convertDatepickerDb($request->penalty_date));

            $bp = new BlacklistPenalty;
            $bp->penalty_ref_no                 = $ref_no;
            $bp->running_no                     = $curr_running_no;
            $bp->tenants_id                     = $request->tenant_id;
            $bp->execution_date                 = $initialDate;
            $bp->penalty_date                   = $initialDate;
            $bp->market_rental_fee              = $tenant->market_rental_amount;
            $bp->blacklist_penalty_rate_list_id = $selectedRate->id;
            $bp->penalty_amount                 = ($selectedRate->rate / 100) * $tenant->market_rental_amount;
            $bp->meeting_remarks                = upperText($request->meeting_remarks);
            $bp->action_by                      = loginId();
            $bp->action_on                      = currentDate();
            $bp->save();

            // Subsequent months
            foreach ($subsequentMonths as $date) {
                $curr_running_no    = $this->_getCurrentRunningNo();
                $ref_no             = $this->_generateRefNo($curr_running_no, $district->district_code);
                $monthsApart        = $this->_calculateMonthsApart($initialDate, $date);
                $selectedRate       = BlacklistPenaltyRate::getRateBasedOnMonthsApart($monthsApart, convertDatepickerDb($request->penalty_date));

                $bp = new BlacklistPenalty;
                $bp->penalty_ref_no                 = $ref_no;
                $bp->running_no                     = $curr_running_no;
                $bp->tenants_id                     = $request->tenant_id;
                $bp->execution_date                 = $initialDate;
                $bp->penalty_date                   = $date;
                $bp->market_rental_fee              = $tenant->market_rental_amount;
                $bp->blacklist_penalty_rate_list_id = $selectedRate->id;
                $bp->penalty_amount                 = ($selectedRate->rate / 100) * $tenant->market_rental_amount;
                $bp->meeting_remarks                = upperText($request->meeting_remarks);
                $bp->action_by                      = loginId();
                $bp->action_on                      = currentDate();
                $bp->save();

                //------------------------------------------------------------------------------------------------------------------
                // Save User Activity
                //------------------------------------------------------------------------------------------------------------------
                setUserActivity("A", $bp->penalty_ref_no);
                //------------------------------------------------------------------------------------------------------------------
            }



            $tenantUpdate = Tenant::where('id', $tenant->id)
                ->update([
                    'blacklist_date' => convertDatepickerDb($request->blacklist_date),
                    'blacklist_reason_id' => ($request->reason != 9999) ? $request->reason : null,
                    'blacklist_reason_others' => ($request->reason == 9999) ? $request->other_reason : null,
                    'action_by' => loginId(),
                    'action_on' => currentDate(),
                ]);

            DB::commit();
        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenalty.create', $request->q_category_id)->with('error', 'Denda tidak berjaya ditambah!');
        }

        return redirect()->route('blacklistPenalty.penaltyList', $request->q_category_id)->with('success', 'Denda berjaya ditambah! ');
    }

    public function view(QuartersCategory $category, BlacklistPenalty $bp)
    {
        if (!checkPolicy("V")) {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }

        Carbon::setLocale('ms_MY');

        // dd(Carbon::getAvailableLocales());

        return view(getFolderPath() . '.view', [
            'category' => $category,
            'bp' => $bp,
            'tenant' => $bp->tenant,
            'initialPenaltyDate' => $bp->tenant->initialPenalty->penalty_date
        ]);
    }

    public function edit(QuartersCategory $category, BlacklistPenalty $bp)
    {
        if (!checkPolicy("U")) {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }

        $currentTenantAll = Tenant::getAllCurrentTenantsByCategory($bp->tenant->quarters_category_id);
        $blacklistRates = BlacklistPenaltyRate::where('data_status', 1)->orderBy('effective_date', 'desc')->first()->rates->where('data_status', 1);
        $blacklistReasonAll = BlacklistReason::where('data_status', 1)->get();

        return view(getFolderPath() . '.edit', [
            'category' => $category,
            'blacklistRates' => $blacklistRates,
            'blacklistReasonAll' => $blacklistReasonAll,
            'bp' => $bp,
            'tenant' => $bp->tenant,
        ]);
    }

    public function update(BlacklistPenaltyPostRequest $request)
    {
        $tenant = Tenant::getSingleTenant($request->quarters_category_id, $request->tid);
        // $rate   = BlacklistPenaltyRateList::find($request->rate);

        $data_before = $tenant->getRawOriginal();
        $data_before['item'] = $tenant->toArray() ?? [];

        DB::beginTransaction();

        try {

            // $update = BlacklistPenalty::where('id', $request->id)->update([
            //     'penalty_date' => convertDateDb($request->penalty_date),
            //     'blacklist_penalty_rate_list_id' => $request->rate,
            //     'penalty_amount' => ($rate->rate / 100) * $tenant->market_rental_amount,
            //     'remarks' => $request->description,
            //     'action_by' => loginId(),
            //     'action_on' => currentDate()
            // ]);

            $update = Tenant::where('id', $tenant->id)
                ->update([
                    'blacklist_reason_id' => $request->reason,
                    'action_by' => loginId(),
                    'action_on' => currentDate(),
                ]);

            $data_after = $tenant;
            $data_after['item'] = $tenant->toArray() ?? [];

            // User Activity - Save
            setUserActivity("U", $tenant->name, $data_before, $data_after);


            DB::commit();
        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenalty.edit', $request->id)->with('error', 'Denda tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('blacklistPenalty.penaltyList', $request->quarters_category_id)->with('success', 'Denda berjaya dikemaskini! ');
    }



    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {

            $update = BlacklistPenalty::where('id', $request->id)->update([
                'data_status' => 0,
                'delete_by' => loginId(),
                'delete_on' => currentDate()
            ]);

            setUserActivity("D", $update->penalty_ref_no);

            DB::commit();
        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenalty.edit', $request->id)->with('error', 'Denda tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('blacklistPenalty.penaltyList', $request->quarters_category_id)->with('success', 'Denda berjaya dihapus! ');
    }

    //  AJAX
    public function ajaxGetRate(Request $request)
    {
        $monthsApart    = $this->_calculateMonthsApart($request);
        $selectedRate   = BlacklistPenaltyRate::getRateBasedOnMonthsApart($monthsApart, convertDatepickerDb($request->penalty_date));

        if (!$selectedRate) {
            return response()->json(['error' => 'Tiada Kadar Denda'], 404);
        }

        return response()->json(['data' => $selectedRate], 201);
    }

    public function ajaxCheckTenantIC(Request $request)
    {
        try {

            $new_ic = $request->new_ic;      $q_cat_id = $request->quarters_cat;

            $currentTenant = Tenant::getCurrentTenantsByCategoryandIc($new_ic, $q_cat_id);

            return response()->json([   'tenant' => $currentTenant,
                                        'quarters_category' => $currentTenant->quarters_category->name ?? "",
                                    ], 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }


    // Private
    private function _getCurrentRunningNo()
    {
        $latest_record = BlacklistPenalty::orderBy('id', 'desc')->first();

        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generateRefNo($running_no, $district_code)
    {
        $ref_no = str_pad($running_no, 4, "0", STR_PAD_LEFT);

        $ref_no = 'DHK' . $district_code . currentYearTwoDigit() . currentMonth() . $ref_no;

        return $ref_no;
    }

    private function _calculateMonthsApart($initialDate, $penaltyDate)
    {
        $diffByMonth = ($initialDate && $penaltyDate) ? getDateDiffByMonth($initialDate, $penaltyDate) : 0;

        return $diffByMonth;
    }

    private function _getMonthListFromDate(Carbon $start)
    {
        $start->setDay(1);
        $start->addMonth();

        $months = [];

        foreach (CarbonPeriod::create($start, '1 month', Carbon::today()) as $month) {
            $months[] = $month->format('Y-m-d');
        }

        return $months;
    }
}
