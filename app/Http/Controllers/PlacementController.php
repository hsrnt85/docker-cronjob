<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationQuartersCategory;
use App\Models\Quarters;
use App\Models\QuartersCategory;
use App\Models\OfferLetter;
use App\Models\QuartersClassGrade;
use App\Models\UserOffice;
use App\Models\UserHouse;
use App\Models\UserSalary;
use App\Models\UserSpouse;
use App\Models\UserChild;
use App\Models\ChildAttachment;
use App\Models\Epnj;
use App\Models\ApplicationAttachment;
use App\Models\ApplicationPlacementAttachment;
use App\Models\DocumentsQuartersAcceptanceAttachment;
use App\Models\QuartersOfferLetter;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\OfferLetterNotification;

class PlacementController extends Controller
{
    public function index()
    {

        $district_id     = null;
        if(!is_all_district()){
            $district_id     = auth()->user()->office->district_id;
        }

        $categoryAll = QuartersCategory::getDistinctQuartersCategoryForPlacement($district_id);

        return view( getFolderPath().'.list',
        [
            'categoryAll' => $categoryAll,
        ]);
    }

    public function listPlacement(QuartersCategory $category)
    {
        $appNeededPlacementAll = Application::getApplicationNeededPlacement($category);

        $appNeededPrintAll = Application::where('data_status', 1)
                                ->where('is_draft', 0)
                                ->whereHas('quarters_category', function($query) use ($category) {
                                    $query->where('quarters_category_id', $category->id);
                                    $query->where('is_selected', 1);
                                    $query->whereNotNull('quarters_id');
                                })
                                ->whereHas('current_status', function ($query) {
                                    $query->where('application_status_id', 7); // 7:Lulus Mesyuarat
                                })
                                ->get();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.list_placement',
            [
                'category' => $category,
                'appNeededPlacementAll' => $appNeededPlacementAll,
                'appNeededPrintAll' => $appNeededPrintAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function bulkPlacement(QuartersCategory $category) {

        $appNeededPlacementAll = Application::getApplicationNeededPlacement($category);

        $addressAll = Quarters::select('address_1')
                        ->where('data_status', 1)
                        ->where('quarters_cat_id', $category->id)
                        ->whereNotNull('unit_no')
                        ->groupBy('address_1')
                        ->get();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.list_bulk_placement',
            [
                'category' => $category,
                'appNeededPlacementAll' => $appNeededPlacementAll,
                'addressAll' => $addressAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function updateBulk(Request $request) {

        $counter    = 0;
        $category   = QuartersCategory::findOrFail($request->category_id);
        $classIDAll = $category->getAllClassIdArr();

        DB::beginTransaction();

        try {

            foreach($request->application_id as $application_id)
           {
                $appNeededPlacementChecked  = Application::checkApplicationNeededPlacement($category, $application_id);

                if(isset($request->unit_no[$application_id]))
                {
                    $update             = ApplicationQuartersCategory::updatePlacement($appNeededPlacementChecked, $request->unit_no[$application_id]);
                    $positionGradeId    = $appNeededPlacementChecked->user->position_grade_id;
                    // $runningNo          = $this->getRunningNo();
                    $rental             = QuartersClassGrade::getRental($classIDAll, $positionGradeId);
                    // $save               = QuartersOfferLetter::newOfferLetter($appNeededPlacementChecked, $runningNo, $rental);
                    $save               = QuartersOfferLetter::newOfferLetter($appNeededPlacementChecked, $rental, $request->letter_ref_no[$application_id]);
                    $saveAttachment     = ApplicationPlacementAttachment::saveAttachment($application_id, $request->offer_letter[$application_id]);

                    if($update) $counter++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('placement.bulkPlacement', $category)->with('error', 'Pengesahan penempatan tidak berjaya');
        }

        return redirect()->route('placement.bulkPlacement', $category)->with('success', $counter . ' Pengesahan penempatan berjaya');
    }


    public function show(Application $application)
    {
        $application_id = $application->id;
        $quartersCategoryAll = QuartersCategory::getAvailableQuartersCategory();

        $quartersAll = Quarters::where('data_status', 1)
                        ->where('quarters_cat_id', $application->selected_category()->id)
                        ->whereNotNull('unit_no')
                        ->get();

        $quartersInitialAddress = Quarters::where('data_status', 1)
                                ->where('quarters_cat_id', $application->selected_category()->id)
                                ->first();

        $user = $application->user;

        $userOffice = UserOffice::where('users_id', $user->id)
                        ->where('data_status', 1)
                        ->first();

        $userHouse = UserHouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        $userSalary = UserSalary::where('new_ic', $user->new_ic)
                    ->where('application_id', $application_id)
                    ->where('data_status', 1)
                    ->first();

        $userSpouse = UserSpouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->first();

        $userChildAll = UserChild::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        $userChildAttachmentAll = ChildAttachment::where('application_id', $application_id)
                                ->whereIn('users_child_id', $userChildAll->pluck('id'))
                                ->where('data_status', 1)
                                ->get();

        $userEpnj = Epnj::where('ic', $user->new_ic)
                    ->where('data_status', 1)
                    ->first();

        $userSpouseEpnj = Epnj::where('ic', $user->spouse?->new_ic)
                            ->where('data_status', 1)
                            ->first();

        $applicationAttachmentAll = ApplicationAttachment::where('a_id', $application_id)
                                    ->where('data_status', 1)
                                    ->orderBy('d_id', 'asc')
                                    ->get();

        $quartersAcceptanceAttachmentAll = DocumentsQuartersAcceptanceAttachment::where('application_id', $application_id)
                                    ->where('data_status', 1)
                                    ->get();
        
        $userInfo = UserInfo::getUserInfoById($application->user_info_id);
        

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'quartersCategoryAll' => $quartersCategoryAll,
                'quartersAll' => $quartersAll,
                'quartersInitialAddress' => $quartersInitialAddress,
                'application' => $application,
                'user' => $user,
                'userOffice' => $userOffice,
                'userHouse' => $userHouse,
                'userSalary' => $userSalary,
                'userSpouse' => $userSpouse,
                'userChildAll' => $userChildAll,
                'userChildAttachmentAll' => $userChildAttachmentAll,
                'userEpnj' => $userEpnj,
                'userSpouseEpnj' => $userSpouseEpnj,
                'userInfo' => $userInfo,
                'applicationAttachmentAll' => $applicationAttachmentAll,
                'quartersAcceptanceAttachmentAll' => $quartersAcceptanceAttachmentAll,
                'cdn' => config('env.upload_ftp_url')
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function edit(Application $application){

        $quartersCategoryAll = QuartersCategory::where('data_status', 1)
                                ->whereHas('quarters', function($query){
                                    $query->where('data_status', 1)
                                    ->whereNotNull('unit_no');
                                })
                                ->get();

        $quartersAll = Quarters::where('data_status', 1)
                        ->where('quarters_cat_id', $application->q_category_id)
                        ->whereNotNull('unit_no')
                        ->get();

        $quarters = Quarters::where('data_status', 1)
                ->where('quarters_cat_id', $application->q_category_id)
                ->first();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'quartersCategoryAll' => $quartersCategoryAll,
                'application' => $application,
                'quartersAll' => $quartersAll,
                'quarters' => $quarters,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(Request $request){

        $id = $request->id;

        DB::beginTransaction();

        try {

            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data before changes
            $application = Application::findOrFail($id);
            $data_before = $application->getRawOriginal(); // Capture application data before changes
            $data_before['item'] = $application->toArray() ?? [];
            //------------------------------------------------------------------------------------------------------------------


            $updatePenempatan = Application::where('data_status', 1)
                                ->where('is_draft', 0)
                                ->where('id', $id)
                                ->whereNull('q_id')
                                ->whereHas('current_status', function ($query) {
                                    $query->where('application_status_id', 7);
                                })
                                ->update([
                                    'q_id' => $request->quarters,
                                    'action_by' => loginId(),
                                    'action_on' => currentDate(),
                                ]);

            DB::commit();
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            $application->refresh(); // Refresh the model to get the updated data
            $data_after = $application->getRawOriginal();
            $data_after['item'] = $application->toArray() ?? [];
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            setUserActivity("U", $data_before, $data_after);
            //------------------------------------------------------------------------------------------------------------------

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('placement.edit', ['application' => $id])->with('error', 'Pengesahan penempatan tidak berjaya' . ' ' . $e->getMessage());
        }

        return redirect()->route('placement.index')->with('success', 'Pengesahan penempatan berjaya!');
    }


    public function printPage(Application $application)
    {
        $category   = $application->category;
        $classIDAll = $category->quartersClass->pluck('id');
        $user       = auth()->user();

        $rental = QuartersClassGrade::where('data_status', 1)
                    ->whereIn('q_class_id', $classIDAll)
                    ->where('p_grade_id', $user->position_grade_id)
                    ->first();

        return view( getFolderPath().'.print-page',
        [
            'application' => $application,
            'rental' => $rental,
        ]);
    }


    public function cetak(Application $application, Request $request)
    {
        DB::beginTransaction();

        $category   = $application->category;
        $classIDAll = $category->quartersClass->pluck('id');
        $user       = $application->user;

        $rental = QuartersClassGrade::where('data_status', 1)
                    ->whereIn('q_class_id', $classIDAll)
                    ->where('p_grade_id', $user->position_grade_id)
                    ->first();

        $offerLetter = new OfferLetter;

        try {

            $offerLetter->application_id            = $application->id;
            $offerLetter->letter_ref_no             = $request->rujukan_kami;
            $offerLetter->letter_date               = $request->tarikh_surat;
            $offerLetter->rental_rate               = $rental->rental_fee;
            $offerLetter->final_confirmation_date   = $request->tarikh_maklumbalas;
            $offerLetter->manager                   = $request->pengarah;
            $offerLetter->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('placement.printpage', ['application' => $application])->with('error', 'Cetakan surat tawaran tidak berjaya' . ' ' . $e->getMessage());
        }

        $offerLetter->refresh();

        // dd($offerLetter);

        $pdf = PDF::loadView('download-pdf.pengesahan-penempatan-pdf', [
            'current_address' => $application->user->current_address(),
            'letter_ref_no' => $offerLetter->letter_ref_no,
            'letter_date' => $offerLetter->letter_date,
            'rental_rate' => $offerLetter->rental_rate,
            'final_confirmation_date' => $offerLetter->final_confirmation_date,
            'manager' => $offerLetter->manager,
            'quarters_category' => $application->category->name,
            'address' => $application->user,
        ]);

        $pdf->setPaper('A4', 'potrait');

        return $pdf->download('pengesahan.pdf');

        return redirect()->route('placement.index')->with('success', 'Pengesahan penempatan berjaya!');
    }

    public function triggerNotification(Application $application)
    {
        $user = $application->user;

        $user->notify(new OfferLetterNotification());

        return redirect()->route('placement.index')->with('success', 'Notifikasi berjaya!');
    }

    public function ajaxGetUnitNo(Request $request)
    {
        $categoryId = $request->id;

        $quartersAll = Quarters::where('data_status', 1)
                        ->where('quarters_cat_id', $categoryId)
                        ->whereNotNull('unit_no')
                        ->get();

        if($quartersAll->count() == 0)
        {
            return response()->json(['error' => 'Tiada unit'], 404);
        }

        return response()->json([
            'data' => $quartersAll,
        ], 201);
    }

    public function ajaxGetAvailableUnitByAddr(Request $request)
    {
        $addr = $request->addr;
        $category_id = $request->category_id;

        $quartersAll = Quarters::getAvailableUnitByAddr($category_id, $addr);

        if($quartersAll->count() == 0)
        {
            return response()->json(['error' => 'Tiada unit2'], 404);
        }

        return response()->json([
            'data' => $quartersAll,
        ], 201);
    }

    private function getRunningNo()
    {
        $lastRow = QuartersOfferLetter::where('data_status', 1)
                    ->orderBy('id', 'DESC')
                    ->first();

        return ($lastRow) ?  $lastRow->running_no : 0;
    }
}
