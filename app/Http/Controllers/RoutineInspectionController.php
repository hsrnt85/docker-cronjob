<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Quarters;
use App\Models\QuartersCategory;
use App\Models\RoutineInspection;
use App\Models\RoutineInspectionTransactionAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class RoutineInspectionController extends Controller
{
    public function index()
    {
        $seeAllDistrict = is_all_district();

        $quartersCategoryAll = ($seeAllDistrict) ? QuartersCategory::getAllQuartersCategory() : QuartersCategory::getAllQuartersCategory(districtId());

        return view( getFolderPath().'.list',
        [
            'quartersCategoryAll' => $quartersCategoryAll,
        ]);
    }

    public function listInspection(QuartersCategory $category)
    {
        $inspectionAll = RoutineInspection::getAllInspectionByCategoryId($category);
        $inspectionArchivedDone = RoutineInspection::getAllArchivedInspectionByCategoryId_Done($category);
        $inspectionArchivedNonDone = RoutineInspection::getAllArchivedInspectionByCategoryId_notDone($category);

        // dd($inspectionArchivedAll);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.list_inspection',
            [
                'inspectionAll' => $inspectionAll,
                'inspectionArchivedDone' => $inspectionArchivedDone,
                'inspectionArchivedNonDone' => $inspectionArchivedNonDone,
                'category' => $category,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function create(QuartersCategory $category)
    {
        $addressAll     = Quarters::getAllAddress($category->id);
        $pemantauAll    = Officer::getPegawaiPemantauanByDaerah(districtId());
        $pengesahAll    = Officer::getPegawaiPengesahByDaerah(districtId());

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'category' => $category,
                'addressAll' => $addressAll,
                'pemantauAll' => $pemantauAll,
                'pengesahAll' => $pengesahAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(Request $request)
    {
        $curr_running_no    = $this->_getcurrentrunningno($request->quarters_category_id);
        $district           = QuartersCategory::where('id', $request->quarters_category_id)->first()->district;
        $ref_no             = $this->_generaterefno($curr_running_no, $district->district_code);

        DB::beginTransaction();

        try {
            $inspection = new RoutineInspection;
            $inspection->ref_no                 = $ref_no;
            $inspection->running_no             = $curr_running_no;
            $inspection->quarters_category_id   = $request->quarters_category_id;
            $inspection->address                = $request->address;
            $inspection->inspection_date        = Carbon::createFromFormat('d/m/Y', $request->tarikh_pemantauan);
            $inspection->monitoring_officer_id  = $request->pemantau;
            $inspection->remarks                = $request->catatan;
            $inspection->data_status            = 1;
            $inspection->action_on              = currentDate();
            $inspection->action_by              = loginId();

            $inspection->save();

            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $inspection->ref_no);
            //------------------------------------------------------------------------------------------------------------------


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('routineInspectionRecord.create', ['category' => $request->quarters_category_id])->with('error', 'Pemantauan berkala tidak berjaya disimpan!' . ' ' . $e->getMessage());
        }

        return redirect()->route('routineInspectionRecord.listInspection', ['category' => $request->quarters_category_id])->with('success', 'Pemantauan berkala berjaya disimpan');
    }

    public function edit(RoutineInspection $inspection)
    {
        $category       = QuartersCategory::findOrFail($inspection->quarters_category_id);
        //$addressAll     = Quarters::getAvailableAddressByCategory($category->id);
        $addressAll     = Quarters::getAllAddress($category->id);
        $pemantauAll    = Officer::getPegawaiPemantauanByDaerah(districtId());
        $pengesahAll    = Officer::getPegawaiPengesahByDaerah(districtId());

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'inspection' => $inspection,
                'category' => $category,
                'addressAll' => $addressAll,
                'pemantauAll' => $pemantauAll,
                'pengesahAll' => $pengesahAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $updateInspection = RoutineInspection::where([
                'id' => $request->inspection_id,
                'data_status' => 1
            ])
            ->first();

            $data_before = $updateInspection->getAttributes();

            // Store the original values before changes
            $originalAddress = $updateInspection->address;
            $originalInspectionDate = $updateInspection->inspection_date;
            $originalMonitoringOfficerId = $updateInspection->monitoring_officer_id;
            $originalRemarks = $updateInspection->remarks;
            $originalActionOn = $updateInspection->action_on;
            $originalActionBy = $updateInspection->action_by;

            // Apply the updates to the inspection
            $updateInspection->address = $request->address;
            $updateInspection->inspection_date = Carbon::createFromFormat('d/m/Y', $request->tarikh_pemantauan);
            $updateInspection->monitoring_officer_id = $request->pemantau;
            $updateInspection->remarks = $request->catatan;
            $updateInspection->action_on = currentDate();
            $updateInspection->action_by = loginId();
            $updateInspection->save();

            $data_after = $updateInspection->getAttributes();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('routineInspectionRecord.edit', ['inspection' => $request->inspection_id])->with('error', 'Pemantauan berkala tidak berjaya disimpan!' . ' ' . $e->getMessage());
        }

        // Save user activity
        setUserActivity("U", $updateInspection->ref_no, [
            'address' => $originalAddress,
            'inspection_date' => $originalInspectionDate,
            'monitoring_officer_id' => $originalMonitoringOfficerId,
            'remarks' => $originalRemarks,
            'action_on' => $originalActionOn,
            'action_by' => $originalActionBy,
        ], $data_after);

        return redirect()->route('routineInspectionRecord.listInspection', ['category' => $request->quarters_category_id])->with('success', 'Pemantauan berkala berjaya disimpan');
    }


    public function view(RoutineInspection $inspection)
    {
        $transaction = ($inspection->inspection_transaction) ? $inspection->inspection_transaction : null;
        $attachmentAll  = RoutineInspectionTransactionAttachment::getAttachmentAll($transaction?->id);
        $tab = ($inspection->inspection_transaction) ? 'terdahulu' : '';

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'inspection' => $inspection,
                'transaction' => $transaction,
                'attachmentAll' => $attachmentAll,
                'tab' => $tab,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $inspection = RoutineInspection::where('id', $request->id)->first();

            setUserActivity("D", $inspection->ref_no);

            $inspection->data_status  = 0;
            $inspection->delete_by    = loginId();
            $inspection->delete_on    = currentDate();

            $inspection->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('routineInspectionRecord.listInspection', ['category' => $request->quarters_category_id])->with('error', 'Pemantauan berkala tidak berjaya dihapus!');
        }

        return redirect()->route('routineInspectionRecord.listInspection', ['category' => $request->quarters_category_id])->with('success', 'Pemantauan berkala berjaya dihapus!');
    }

    public function ajaxcheckalamat(Request $request)
    {
        $checkAddress = RoutineInspection::checkAddressByDate($request->address, Carbon::createFromFormat('d/m/Y', $request->date));

        return response()->json(
            [
                'count' => $checkAddress
            ],
            201
        );
    }

    private function _getcurrentrunningno($category_id)
    {
        $latest_record = RoutineInspection::orderBy('id', 'desc')->first();

        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private function _generaterefno($running_no, $district_code)
    {
        $ref_no = str_pad($running_no, 6, "0", STR_PAD_LEFT);

        $ref_no = 'PB' . $district_code . currentYearTwoDigit() . currentMonth() . $ref_no;

        return $ref_no;
    }
}
