<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuartersCategory;
use App\Models\Document;
use App\Models\UserOffice;
use App\Models\UserHouse;
use App\Models\UserChild;
use App\Models\UserSpouse;
use App\Http\Requests\QuartersApplicationPostRequest;


class QuartersApplicationController extends Controller
{
    //
    public function create()
    {
        // dd(auth()->user());
        $quartersCategoryAll = QuartersCategory::where('data_status', 1)->get();
        $documentAll = Document::where('data_status', 1)->get();
        // $inventoryAll = Inventory::where('data_status', 1)->get();
        // $maintenanceInventoryAll = MaintenanceInventory::where('data_status', 1)->get();

        // dd($maintenanceInventoryAll);

        $user = auth()->user();
        $userOffice = UserOffice::where('users_id', $user->id)
                        ->where('data_status', 1)
                        ->first();

        $userHouse = UserHouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();      

        $userSpouse = UserSpouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->first();

        $userChildAll = UserChild::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        return view('modules.QuartersManagement.Application.create', [
            'quartersCategoryAll' => $quartersCategoryAll,
            'documentAll' => $documentAll,
            'user' => $user,
            'userHouse' => $userHouse,
            'userOffice' => $userOffice,
            'userSpouse' => $userSpouse,
            'userChildAll' => $userChildAll,
        ]); 
    }

    public function store(QuartersApplicationPostRequest $request)
    {
        dd($request);
    }
}
