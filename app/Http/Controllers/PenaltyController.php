<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuartersCategory;
use App\Models\TenantsPenalty;
use App\Models\Tenant;
use App\Http\Requests\PenaltyRequest;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class PenaltyController extends Controller
{
    public function index()
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $categoryAll = QuartersCategory::getDistinctQuartersCategoryForTenant($district_id);

        return view( getFolderPath().'.list',
        [
            'categoryAll' => $categoryAll
        ]);
    }

    public function list_penalty(QuartersCategory $category)
    {

        $tenantsPenalty = TenantsPenalty::getAllPenaltyByQuartersCategory($category->id);

        return view( getFolderPath().'.list_penalty',
        [
            'tenantsPenalty' => $tenantsPenalty,
            'category' => $category
        ]);
    }

    public function create(QuartersCategory $category)
    {
        if(checkPolicy("A"))
            { return view(getFolderPath().'.create', ['category' => $category]); }
        else
            { return redirect()->route('dashboard')->with('error-permission','access.denied'); }
    }

    public function store(PenaltyRequest $request)
    {

        $q_category_id = $request->q_category_id;

        $curr_running_no    = $this->_getcurrentrunningno();
        $district           = QuartersCategory::where('id', $q_category_id)->first()->district;
        $ref_no             = $this->_generaterefno($curr_running_no, $district->district_code);

        try {

            $penalty = new TenantsPenalty;
            $penalty->penalty_ref_no         = $ref_no;
            $penalty->running_no             = $curr_running_no;
            $penalty->tenants_id             = $request->tenant_id;
            $penalty->penalty_date           = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->penalty_date));
            $penalty->remarks                = $request->remarks;
            $penalty->penalty_amount         = $request->penalty_amount;
            $penalty->data_status            = 1;
            $penalty->action_by              = loginId();
            $penalty->action_on              = currentDate();

            $saved = $penalty->save();

            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            $record =  $penalty->penalty_ref_no.' : '.$penalty->tenants?->name;
            setUserActivity("A", $record);
            //------------------------------------------------------------------------------------------------------------------


            if($saved) return redirect()->route('penalty.penaltyList', ['category' => $q_category_id])->with('success', 'Denda berjaya ditambah! ');

        } catch (\Exception $e) {

            // something went wrong
            return redirect()->route('penalty.create', ['category'=> $q_category_id ])->with('error', 'Denda tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }


    }

    public function edit(Request $request, QuartersCategory $category)
    {
        $tenantsPenalty = TenantsPenalty::findOrFail($request->id);

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'tenantsPenalty' => $tenantsPenalty,
                'category' => $category,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(PenaltyRequest $request)
    {
        $id = $request->id;
        $q_category_id = $request->q_category_id;

        try {
                $tenantsPenalty = TenantsPenalty::find($id);

                $data_before = [
                    'penalty_date' => $tenantsPenalty->getRawOriginal('penalty_date'),
                    'remarks' => $tenantsPenalty->getRawOriginal('remarks'),
                    'penalty_amount' => $tenantsPenalty->getRawOriginal('penalty_amount'),
                ];
                $data_before['item'] = $tenantsPenalty->toArray();

                $tenantsPenalty->penalty_date   = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->penalty_date));
                $tenantsPenalty->remarks        = $request->remarks;
                $tenantsPenalty->penalty_amount = $request->penalty_amount;
                $tenantsPenalty->action_by      = loginId();
                $tenantsPenalty->action_on      = currentDate();

                // Capture data after changes (CODE B)
                $data_after = $tenantsPenalty->toArray();

                $updated = $tenantsPenalty->save();

                // User Activity Logging (using setUserActivity)
                if ($updated) {
                    $data_before_json = json_encode($data_before);
                    $data_after_json = json_encode($data_after);
                    $record =  $tenantsPenalty->penalty_ref_no.' : '.$tenantsPenalty->tenants?->name;

                    setUserActivity("U", $record, $data_before_json, $data_after_json);

                    return redirect()->route('penalty.penaltyList', ['category' => $q_category_id])->with('success', 'Denda berjaya dikemaskini!');
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return redirect()->route('penalty.edit', ['category'=> $q_category_id ,'id'=>$id])->with('error', 'Denda tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
            }
    }

    public function view(Request $request, QuartersCategory $category)
    {

        $tenantsPenalty = TenantsPenalty::where(['id' => $request->id, 'data_status' => 1])->first();

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view', ['tenantsPenalty' => $tenantsPenalty, 'category' => $category]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function ajaxCheckTenantIC(Request $request)
    {
        try {

            $new_ic = $request->new_ic;      
            $q_cat_id = $request->quarters_category_id;

            $tenant = Tenant::where(['new_ic'=> $new_ic, 'quarters_category_id' => $q_cat_id, 'data_status' => 1 ])->orderBy('id', 'desc')->first();

            $isTenant= Tenant::where(['new_ic'=> $new_ic, 'quarters_category_id' => $q_cat_id, 'data_status' => 1 ])
            ->where(function($query) {
                $query->whereNull('leave_status_id')
                      ->orWhere('leave_status_id', '=', 0);     // belum keluar
            })
            ->orderBy('id', 'desc')->first();

            $tenantWasLeave = Tenant::where(['new_ic'=> $new_ic, 'quarters_category_id' => $q_cat_id, 'data_status' => 1 ])
            ->where('leave_status_id' , 1)  // keluar
            ->orderBy('id', 'desc')->first();

            return response()->json([   'tenant' => $tenant,
                                        'isTenant' => $isTenant,
                                        'tenantWasLeave' => $tenantWasLeave,
                                        'quarters_category' => $tenant->quarters_category->name ?? "",
                                    ], 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $q_category_id = $request->quarters_cat_id;
        $tenantsPenalty = TenantsPenalty::where(['id' => $request->id, 'data_status' => 1])->first();

        $record =  $tenantsPenalty->penalty_ref_no.' : '.$tenantsPenalty->tenants?->name;
        setUserActivity("D", $record);

        $tenantsPenalty->data_status  = 0;
        $tenantsPenalty->delete_by    = loginId();
        $tenantsPenalty->delete_on    = currentDate();

        $deleted = $tenantsPenalty->save();

        if(!$deleted)
        {
            return redirect()->route('penalty.penaltyList', ['category' => $q_category_id])->with('error', 'Denda tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('penalty.penaltyList', ['category' => $q_category_id])->with('success', 'Denda berjaya dihapus!');
        }
    }

    private function _getcurrentrunningno()
    {
        $latest_record = TenantsPenalty::orderBy('id', 'desc')->first();

        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generaterefno($running_no, $district_code)
    {
        $ref_no = str_pad($running_no, 6, "0", STR_PAD_LEFT);

        $ref_no = 'DK' . $district_code . currentYearTwoDigit() . currentMonth() . $ref_no;

        return $ref_no;
    }
}
