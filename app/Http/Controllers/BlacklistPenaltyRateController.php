<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlacklistPenaltyRate;
use App\Models\BlacklistPenaltyRateList;
use App\Models\Operator;
use App\HTTP\Requests\BlacklistPenaltyRatePostRequest;
use Illuminate\Support\Facades\DB;

class BlacklistPenaltyRateController extends Controller
{
    public function index()
    {
        $bprAll = BlacklistPenaltyRate::where('data_status', 1)->get();

        return view( getFolderPath().'.list',
            compact('bprAll')
        );
    }

    public function create()
    {
        $operatorAll = Operator::where('data_status', 1)->where('flag_blacklist_penalty', 1)->get();

        return view( getFolderPath().'.create',
            compact('operatorAll')
        );
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $bpr = new BlacklistPenaltyRate;
            $bpr->effective_date = convertDatepickerDb($request->eff_date);
            $bpr->description    = $request->description;
            $bpr->action_by      = loginId();
            $bpr->action_on      = currentDate();
            $bpr->save();

            foreach($request->range_from as $ind => $range_from)
            {
                $bprList = new BlacklistPenaltyRateList;
                $bprList->blacklist_penalty_rate_id = $bpr->id;
                $bprList->range_from = $range_from;
                $bprList->operator_id = $request->operator_id[$ind];
                $bprList->range_to = isset($request->range_to[$ind]) ? $request->range_to[$ind] : null;
                $bprList->rate = $request->rate[$ind];
                $bprList->action_by = loginId();
                $bprList->action_on = currentDate();
                $bprList->save();


            }
            $record = convertDateSys($bpr->effective_date).' : '. $bpr->description;
            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $record);
            //------------------------------------------------------------------------------------------------------------------

            DB::commit();

            

        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenaltyRate.create')->with('error', 'Kadar denda tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }
        
        return redirect()->route('blacklistPenaltyRate.index')->with('success', 'Kadar denda berjaya ditambah! ');
    }

    public function edit(BlacklistPenaltyRate $bpr)
    {
        $operatorAll = Operator::where('data_status', 1)->where('flag_blacklist_penalty', 1)->get();
        $rates = $bpr->rates;

        return view( getFolderPath().'.edit',
            compact('bpr', 'rates', 'operatorAll')
        );
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $existingData = BlacklistPenaltyRate::where('id', $request->bpr_id)->first();


            BlacklistPenaltyRate::where('id', $request->bpr_id)
            ->update([
                'effective_date' => convertDatepickerDb($request->eff_date),
                'description' => $request->description,
                'action_by' => loginId(),
                'action_on' => currentDate(),
            ]);

            

            foreach($request->is_edit as $ind => $is_edit)
            {
                if($is_edit == 1)
                {
                    $existingRateData = BlacklistPenaltyRateList::where('id', $request->rate_id[$ind])->first();

                    BlacklistPenaltyRateList::where('id', $request->rate_id[$ind])
                    ->update([
                        'range_from' => $request->range_from[$ind],
                        'operator_id' => $request->operator_id[$ind],
                        'range_to' => isset($request->range_to[$ind]) ? $request->range_to[$ind] : null,
                        'rate' => $request->rate[$ind],
                        'action_by' => loginId(),
                        'action_on' => currentDate(),
                    ]);

                    // User Activity - Set Data before changes
                    $data_before = [
                        'existingRateData' => $existingRateData,
                    ];
                    
                    // User Activity - Set Data after changes
                    $data_after = [
                        'range_from' => $request->range_from[$ind],
                        'operator_id' => $request->operator_id[$ind],
                        'range_to' => isset($request->range_to[$ind]) ? $request->range_to[$ind] : null,
                        'rate' => $request->rate[$ind],
                    ];

                    $data_before_json = json_encode($data_before);
                    $data_after_json = json_encode($data_after);
                    $record = convertDateSys($existingRateData->blacklist_penalty_rate?->effective_date).' : '.$existingRateData->blacklist_penalty_rate?->description;

                    setUserActivity("U", $record, $data_before_json, $data_after_json);
                }
                else
                {
                    $bprList = new BlacklistPenaltyRateList;
                    $bprList->blacklist_penalty_rate_id = $request->bpr_id;
                    $bprList->range_from = $request->range_from[$ind];
                    $bprList->operator_id = $request->operator_id[$ind];
                    $bprList->range_to = isset($request->range_to[$ind]) ? $request->range_to[$ind] : null;
                    $bprList->rate = $request->rate[$ind];
                    $bprList->action_by = loginId();
                    $bprList->action_on = currentDate();
                    $bprList->save();

                    // User Activity - Save
                    $record = convertDateSys($bprList->blacklist_penalty_rate?->effective_date).' : '.$bprList->blacklist_penalty_rate?->description;
                    setUserActivity(
                        "A", // Activity flag for add
                        $record, // Activity description
                        [], // No data before for addition
                        [
                            'range_from' => $bprList->range_from,
                            'operator_id' => $bprList->operator_id,
                            'range_to' => $bprList->range_to,
                            'rate' => $bprList->rate,
                        ] // Data after addition
                    );
                }
            }
            
            DB::commit();

        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenaltyRate.edit', $request->bpr_id)->with('error', 'Kadar denda tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
        
        return redirect()->route('blacklistPenaltyRate.index')->with('success', 'Kadar denda berjaya dikemaskini! ');
    }

    public function view(BlacklistPenaltyRate $bpr)
    {
        return view( getFolderPath().'.view',
            compact('bpr')
        );
    }

    public function destroyRate(Request $request)
    {
        $bprItem = BlacklistPenaltyRateList::find($request->id);

        DB::beginTransaction();

        try {

            BlacklistPenaltyRateList::where('id', $request->id)
            ->update([
                'data_status' => 0
            ]);
            
            DB::commit();

        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenaltyRate.edit', $bprItem->blacklist_penalty_rate_id)->with('error', 'Kadar denda tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }
        
        return redirect()->route('blacklistPenaltyRate.edit', $bprItem->blacklist_penalty_rate_id)->with('success', 'Kadar denda berjaya dihapus! ');
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $blacklistPenaltyRate = BlacklistPenaltyRate::findOrFail($request->id);

            BlacklistPenaltyRate::where('id', $request->id)
            ->update([
                'data_status' => 0
            ]);

            BlacklistPenaltyRateList::where('blacklist_penalty_rate_id', $request->id)
            ->update([
                'data_status' => 0
            ]);

            $record = convertDateSys($blacklistPenaltyRate->effective_date).' : '. $blacklistPenaltyRate->description;
            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("D", $record);
            //------------------------------------------------------------------------------------------------------------------
            
            DB::commit();

        } catch (\Exception $e) {
            // something went wrong
            DB::rollback();
            return redirect()->route('blacklistPenaltyRate.index')->with('error', 'Kadar denda tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }
        
        return redirect()->route('blacklistPenaltyRate.index')->with('success', 'Kadar denda berjaya dihapus! ');
    }
}
