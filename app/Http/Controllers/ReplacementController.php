<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuartersCategory;
use App\Models\Quarters;
use App\Models\Application;
use App\Models\User;
use App\Models\ApplicationQuartersCategory;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Notifications\ReplacementNotification;


class ReplacementController extends Controller
{
    //
    public function index()
    {


        $categoryAll = QuartersCategory::getDistinctQuartersCategoryForReplacement();

        return view( getFolderPath().'.list',
        [
            'categoryAll' => $categoryAll,
        ]);
    }

    public function listReplacement(QuartersCategory $category)
    {
        $appEligibleReplacementAll = Application::getApplicationEligibleForReplacement($category);

        $tenantEligibleForReplacementAll = Tenant::getEligibleForReplacement($category);

        return view( getFolderPath().'.list_replacement',
        [
            'category' => $category,
            'appEligibleReplacementAll' => $appEligibleReplacementAll,
            'tenantEligibleForReplacementAll' => $tenantEligibleForReplacementAll,
        ]);
    }

    public function edit(Application $application)
    {
        $categoryAll = QuartersCategory::getAvailableQuartersCategory();
        
        $addressAll = Quarters::getAllAddress($application->selected_category()->id);

        $availableUnitAll = Quarters::getAvailableUnitByAddr($application->selected_quarters()->address_1);

        return view( getFolderPath().'.edit',
        [
            'application' => $application,
            'categoryAll' => $categoryAll,
            'addressAll' => $addressAll,
            'availableUnitAll' => $availableUnitAll,
        ]);
    }


    public function update(Request $request)
    {
        $application    = Application::findOrFail($request->id);
        $user           = User::findOrFail($application->user_id);

        DB::beginTransaction();

        try {

            $unSelectedPreviousSelection = ApplicationQuartersCategory::where('data_status', 1)
                                            ->where('application_id', $application->id)
                                            ->where('quarters_category_id', $application->selected_category()->id)
                                            ->where('quarters_id', $application->selected_quarters()->id)
                                            ->where('is_selected', 1)
                                            ->update([
                                                'is_selected' => 0,
                                                'action_by' => loginId(),
                                                'action_on' => currentDate(),
                                            ]);

            $applicationQuartersCategory = new ApplicationQuartersCategory;
            $applicationQuartersCategory->application_id        = $application->id;
            $applicationQuartersCategory->quarters_category_id  = $request->quarters_category;
            $applicationQuartersCategory->quarters_id           = $request->quarters;
            $applicationQuartersCategory->is_selected           = 1;
            $applicationQuartersCategory->replacement_reason    = $request->reason;
            $applicationQuartersCategory->replacement_letter    = $request->reason_attachment->store('documents/replacement_letter', 'assets-upload');
            $applicationQuartersCategory->action_by             = loginId();
            $applicationQuartersCategory->action_on             = currentDate();
            $applicationQuartersCategory->replace_by            = loginId();
            $applicationQuartersCategory->replace_on            = currentDate();

            $applicationQuartersCategory->save();

            $user->notify(new ReplacementNotification());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('replacement.index')->with('error', 'Pertukaran penempatan tidak berjaya' . ' ' . $e->getMessage());
        }

        return redirect()->route('replacement.index')->with('success', 'Pertukaran penempatan berjaya!');
    }
    
    public function tukarpage(Tenant $tenant)
    {

        return view( getFolderPath().'.replace',
        [
            'tenant' => $tenant,
        ]);
    }

    public function ajaxGetAddressByCategory(Request $request)
    {
        $categori_id = $request->cat_id;

        $addressAll = Quarters::getAllAddress($categori_id);

        if($addressAll->count() == 0)
        {
            return response()->json(['error' => 'Tiada unit alamat'], 404);
        }

        return response()->json([
            'data' => $addressAll,
        ], 201);
    }

    public function ajaxGetAvailableQuartersCategory(Request $request)
    {
        $id = $request->id;

        $quartersCategory = QuartersCategory::getAvailableQuartersCategory();

        if($quartersCategory->count() == 0)
        {
            return response()->json(['error' => 'Tiada Kategori Kuarters (Lokasi)'], 404);
        }

        return response()->json(['data'=>$quartersCategory], 201);
    }

    
    public function ajaxGetAvailableAddrByCategory(Request $request)
    {
        $categori_id = $request->cat_id;

        $availableAddressAll = Quarters::getAvailableAddressByCategory($categori_id);

        if($availableAddressAll->count() == 0)
        {
            return response()->json(['error' => 'Tiada alamat' . $categori_id], 404);
        }

        return response()->json([
            'data' => $availableAddressAll,
        ], 201);
    }


    public function ajaxGetAvailableUnitByAddr(Request $request)
    {
        $addr = $request->addr;

        $quartersAll = Quarters::getAvailableUnitByAddr($addr);

        if($quartersAll->count() == 0)
        {
            return response()->json(['error' => 'Tiada unit'], 404);
        }

        return response()->json([
            'data' => $quartersAll,
        ], 201);
    }
}
