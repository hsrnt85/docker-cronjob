<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuartersCategory;
use App\Models\Document;
use App\Models\UserOffice;
use App\Models\UserHouse;
use App\Models\UserChild;
use App\Models\ChildAttachment;
use App\Models\UserSpouse;
use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\ApplicationHistory;
use App\Models\UserSalary;
use App\Models\Epnj;
use App\Models\User;
use App\Models\Quarters;
use App\Http\Requests\GreenlaneApplicationPostRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Config;


class GreenlaneApplicationController extends Controller
{

    public function index()
    {
        $applicationAll = Application::where('data_status', 1)
                        ->where('application_type', 2)
                        ->where('action_by', auth()->user()->id)
                        ->get();

        return view('modules.QuartersManagement.Greenlane.list',
        [
            'applicationAll' => $applicationAll
        ]);
    }
    //
    public function ajaxGetField(Request $request)
    {
        try {
            $getFields = User::join('services_type','services_type.id','=','users.services_type_id')
            ->join('position','position.id','=','users.position_id')
            ->join('position_grade','position_grade.id','=','users.position_grade_id')
            ->join('marital_status','marital_status.id','=','users.marital_status_id')
            ->join('users_address_house','users_address_house.users_id','=','users.id')
            ->join('position_type','position_type.id','=','users.position_type_id')
            ->join('users_address_office','users_address_office.users_id','=','users.id')
            ->join('users_salary','users_salary.new_ic','=','users.new_ic')
            ->select('users.*','users.new_ic','position.position_name','position_grade.grade_no','services_type.services_type','marital_status.marital_status',
                'users_address_house.address_1','users_address_house.address_2','users_address_house.address_3','position_type.position_type',
                'users_address_office.address_1 as address_office_1','users_address_office.address_2 as address_office_2','users_address_office.address_3 as address_office_3',
                'users_address_office.phone_no_office','users_address_office.fax_no_office','users_address_office.email_office','users_salary.no_gaji')
            ->where('users.new_ic',$request->new_ic)->first();
            // here you could check for data and throw an exception if not found e.g.
            // if(!$getFields) {
            //     throw new \Exception('Data not found');
            // }
            return response()->json($getFields, 200);
           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetFieldSpouse(Request $request)
    {
        try {
            $getFields = User::join('users_spouse','users_spouse.users_id','=','users.id')
            ->select(
                'users_spouse.spouse_name','users_spouse.new_ic as spouse_new_ic','users.id')
            ->where('users.new_ic',$request->new_ic)->first();
            // here you could check for data and throw an exception if not found e.g.
            // if(!$getFields) {
            //     throw new \Exception('Data not found');
            // }
            return response()->json($getFields, 200);
           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetFieldEpnj(Request $request)
    {
        try {
            $getFields = User::join('epnj','epnj.ic','=','users.new_ic')
            ->select(
                'users.name','users.new_ic as new_ic',
                'epnj.ic as epnj_ic','epnj.is_epnj')
            ->where('epnj.ic',$request->new_ic)->first();
            // here you could check for data and throw an exception if not found e.g.
            // if(!$getFields) {
            //     throw new \Exception('Data not found');
            // }
            return response()->json($getFields, 200);
           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetFieldSpouseEpnj(Request $request)
    {
        try {
            $getFields = UserSpouse::join('epnj','epnj.ic','=','users_spouse.new_ic')
            ->select(
                'users_spouse.spouse_name','users_spouse.new_ic as spouse_new_ic','users_spouse.is_work',
                'epnj.ic as epnj_ic','epnj.is_epnj')
            ->where('epnj.ic',$request->spouse_new_ic)->first();
            // here you could check for data and throw an exception if not found e.g.
            // if(!$getFields) {
            //     throw new \Exception('Data not found');
            // }
            return response()->json($getFields, 200);
           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetTable(Request $request)
    {
        try{
            $getTable = User::join('users_child','users_child.users_id','=','users.id')
            ->select('users_child.id as child_id','users_child.child_name','users_child.new_ic as child_ic','users_child.is_cacat')
            ->where('users.new_ic',$request->new_ic)
            ->where('users_child.data_status','=','1')->get();
            // here you could check for data and throw an exception if not found e.g.
            // if(!$getFields) {
            //     throw new \Exception('Data not found');
            // }
            return response()->json($getTable, 200);
           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetDistance(Request $request)
    {
        $application = Application::find($request->app_id);
        $office      = ($application != null) ? $application->user->office : auth()->user()->office;
        $latOffice   = $office->latitude;
        $lonOffice   = $office->longitude;

        $response = Http::get(config('env.gis_provider_url'), [
            'q' => $request->address1 . ' ' . $request->address2 . ' ' . $request->address3,
            'format' => 'json',
            'polygon' => '1',
            'addressdetails' => '1'
        ]);

        $data = $response->json();

        $latitude = 0;
        $longitude = 0;

        if($data != null)
        {
            $latitude   = $data[0]['lat'];
            $longitude  = $data[0]['lon'];
        }

        return response()->json([
            'response' => $response->json(),
            'status' => $response->successful(),
            'latOffice' => $latOffice,
            'lonOffice' => $lonOffice,
            'latitude' => isset($latitude) ? $latitude : '',
            'longitude' => isset($longitude) ? $longitude : '',
            'distance' => getDistanceByLatLong($latOffice, $lonOffice, $latitude, $longitude, 'K')
        ], 201);
    }

    public function create()
    {
        // dd(auth()->user());
        $QuartersCategoryAll = QuartersCategory::where('data_status', 1)
                                ->whereHas('quarters', function($query){
                                    $query->where('data_status', 1)
                                    ->whereNotNull('unit_no');
                                })
                                ->get();
        $documentAll = Document::where('data_status', 1)->get();
        $documentAll = Document::whereIn('id', [3, 4, 2, 5, 6, 7])->get();
        // $inventoryAll = Inventory::where('data_status', 1)->get();
        // $maintenanceInventoryAll = MaintenanceInventory::where('data_status', 1)->get();

        // dd($maintenanceInventoryAll);

        return view('modules.QuartersManagement.Greenlane.create', [
            'QuartersCategoryAll' => $QuartersCategoryAll,
            'documentAll' => $documentAll,
        ]);
    }

    public function store(GreenlaneApplicationPostRequest $request)
    {
        //Check ic
        $new_ic = $request->new_ic;
        $user_id = $request->user_id;

        $check = Application::join('users','users.id','=','application.user_id')->where([
            ['new_ic', '=', $new_ic],
            ['application.user_id', '=',$user_id],
            ['application.data_status', '=', '1'],
            ['application.application_type','=','2']
        ])->first();

        if($check)
        {
            return redirect()->route('applicationGreenlane.create')->with('error', 'No Kad Pengenalan sudah wujud!');

        }

        else{

                // Application Greenlane
                $application = new Application;
                $application->application_type      = 2;                                    // 2: Greenlane
                $application->user_id               = $request->user_id;
                $application->q_category_id         = $request->quarter_category;
                $application->is_rental             = (bool) $request->current_house_rent;  // 1: Sewa
                $application->rental_fee            = $request->current_house_rent;
                $application->distance_from_office  = $request->current_house_distance;
                $application->is_draft              = 1;                                    // 1: Draft
                $application->data_status           = 1;
                $application->action_by             = auth()->user()->id;
                $application->action_on             = date('Y-m-d H:i:s');

                    // Ulasan
                    if($request->has('review'))
                        {
                            $application->review            = $request->review;
                        }

                    // Ulasan dokumen
                    if($request->has('review_document'))
                        {
                            $pathReviewDocument = $request->review_document->store('documents/ulasan', 'assets-upload');
                            $application->review_attachment = $pathReviewDocument;
                        }

                    $saved = $application->save();

                    // User Salary
                    $userSalary = new UserSalary;
                    $userSalary->application_id = $application->id;
                    $userSalary->new_ic         = auth()->user()->new_ic;
                    $userSalary->basic_salary   = substr($request->user_salary,3);
                    $userSalary->itp            = substr($request->user_itp,3);
                    $userSalary->bsh            = substr($request->user_bsh,3);
                    $userSalary->no_gaji        = $request->user_no_gaji;
                    $userSalary->data_status    = 1;
                    $userSalary->action_by      = auth()->user()->id;
                    $userSalary->action_on      = date('Y-m-d H:i:s');
                    $userSalary->save();

                    // User Office
                    $updateUserOffice = UserOffice::where('users_id', $request->id)
                                            ->where('data_status', 1)
                                            ->orderBy('id','desc')
                                            ->take(1)
                                            ->update([
                                                'phone_no_office' => $request->phone_no_office,
                                                'fax_no_office'   => $request->fax_no_office,
                                                'email_office'    => $request->email_office,
                                                'action_by'       => auth()->user()->id,
                                                'action_on'       => date('Y-m-d H:i:s'),
                                               ]);

                    // User Spouse
                    $userSpouseDetails = [];
                    $userSpouseDetails['phone_no_hp'] = $request->spouse_phone_no;

                    if($request->is_spouse_work == 1)
                        {
                            $userSpouseDetails['office_address_1']  = $request->spouse_office_address_1;
                            $userSpouseDetails['office_address_2']  = $request->spouse_office_address_2;
                            $userSpouseDetails['office_address_3']  = $request->spouse_office_address_3;
                            $userSpouseDetails['position_name']     = $request->spouse_position;
                            $userSpouseDetails['salary']            = $request->spouse_salary;
                        }

                    $userSpouseDetails['action_by'] = auth()->user()->id;
                    $userSpouseDetails['action_on'] = date('Y-m-d H:i:s');


                    $updateUserSpouse = UserSpouse::where('users_id', auth()->user()->id)
                                            ->where('data_status', 1)
                                            ->orderBy('id','desc')
                                            ->take(1)
                                            ->update($userSpouseDetails);

                    // User Child
                    foreach($request->child as $childKey => $childValue)
                    {
                        $updateUserChild = UserChild::where('id', $childValue)
                                             ->where('data_status', 1)
                                             ->update([
                                                 'is_cacat' => $request->cacat,
                                                 'action_by' => auth()->user()->id,
                                                 'action_on' => date('Y-m-d H:i:s'),
                                             ]);

                        $pathChildIc    = $request->child_ic_document[$childValue]->store('documents/child_attachment', 'assets-upload');

                        $childAttachment = new ChildAttachment;
                        $childAttachment->application_id = $application->id;
                        $childAttachment->users_child_id = $childValue;
                        $childAttachment->path_document  = $pathChildIc;
                        $childAttachment->data_status    = 1;
                        $childAttachment->action_by      = auth()->user()->id;
                        $childAttachment->action_on      = date('Y-m-d H:i:s');
                        $childAttachment->save();
                    }

                    // EPNJ Pinjaman User
                    //$office      = $application->user->office;
                    $office      = UserOffice::where(['id' => $request->user_id])->first();
                    $latOffice   = $office->latitude;
                    $lonOffice   = $office->longitude;

                    if($request->is_epnj_user == 1)
                        {
                            $latLong = getLatLongByAddress($request->user_epnj_address_1, $request->user_epnj_address_2, $request->user_epnj_address_3);

                            $distance = getDistanceByLatLong($latLong['lat'], $latLong['long'], $latOffice, $lonOffice, 'K');

                            $epnjUserDetails = [
                                'address_1' => $request->user_epnj_address_1,
                                'address_2' => $request->user_epnj_address_2,
                                'address_3' => $request->user_epnj_address_3,
                                'postcode'  => $request->user_epnj_postcode,
                                'mukim'     => $request->user_epnj_mukim,
                                'latitude'  => $latLong['lat'],
                                'longitude' => $latLong['long'],
                                'distance'  => $distance
                            ];

                    $updateEpnjUser = Epnj::where('ic', $request->new_ic)
                                            ->update($epnjUserDetails);
                        }

                    // EPNJ Pinjaman Spouse
                    if($request->is_epnj_spouse == 1)
                    {
                         $epnjSpouseDetails = [
                            'address_1' => $request->spouse_epnj_address_1,
                            'address_2' => $request->spouse_epnj_address_2,
                            'address_3' => $request->spouse_epnj_address_3,
                            'postcode'  => $request->spouse_epnj_postcode,
                            'mukim'     => $request->spouse_epnj_mukim,

                         ];

                         $updateEpnjUser = Epnj::where('ic', auth()->user()->spouse->new_ic)
                                        ->update($epnjSpouseDetails);
                    }


            if($request->document != null)
            {
                foreach($request->document as $key => $file)
                {
                    $path = $file->store('documents/applications', 'assets-upload');

                    $applicationAttachment = new ApplicationAttachment;
                    $applicationAttachment->a_id            = $application->id;
                    $applicationAttachment->d_id            = $key;
                    $applicationAttachment->path_document   = $path;
                    $applicationAttachment->data_status     = 1;
                    $applicationAttachment->action_by       = auth()->user()->id;
                    $applicationAttachment->action_on       = date('Y-m-d H:i:s');

                    $applicationAttachment->save();
                }
            }

            if(!$saved)
            {
                return redirect()->route('applicationGreenlane.create')->with('error', 'Permohonan tidak berjaya ditambah!');
            }
            else
            {
                return redirect()->route('applicationGreenlane.index')->with('success', 'Permohonan berjaya ditambah!');
            }
        }
    }

    public function edit(Request $request)
    {
        $QuartersCategoryAll = QuartersCategory::where('data_status', 1)
                                ->whereHas('quarters', function($query){
                                    $query->where('data_status', 1)
                                    ->whereNotNull('unit_no');
                                })
                                ->get();

        $documentAll = Document::where('data_status', 1)->get();
        $documentAll = Document::whereIn('id', [3, 4, 2, 5, 6, 7])->get();

        $user = auth()->user();

        $application = Application::where([
                            'id' => $request->id,
                            'user_id' => auth()->user()->id
                        ])
                        ->first();

        // Kalau sudah hantar
        if($application->is_draft == 0)
        {
            return redirect()->route('applicationGreenlane.view', ['id' => $request->id])->with('error', 'TIDAK BOLEH KEMASKINI - Permohonan sudah dihantar!');
        }

        $applicationAttachmentAll = ApplicationAttachment::where([
                                        'a_id' => $application->id,
                                        'data_status' => 1])
                                    ->get();

        // dd($applicationAttachmentAll->where('a_id', $application->id)->where('d_id', 4)->first());

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

        $userChildAttachmentAll = ChildAttachment::where('application_id', $application->id)
                                ->whereIn('users_child_id', $userChildAll->pluck('id'))
                                ->where('data_status', 1)
                                ->get();

        $userSalary = UserSalary::where('new_ic', $user->new_ic)
                                ->where('application_id', $application->id)
                                ->where('data_status', 1)
                                ->first();

        $userEpnj = Epnj::where('ic', $user->new_ic)
                    ->where('data_status', 1)
                    ->first();

        $userSpouseEpnj = Epnj::where('ic', $user->spouse?->new_ic)
                            ->where('data_status', 1)
                            ->first();

        return view('modules.QuartersManagement.Greenlane.edit', [
            'QuartersCategoryAll' => $QuartersCategoryAll,
            'documentAll' => $documentAll,
            'user' => $user,
            'userHouse' => $userHouse,
            'userOffice' => $userOffice,
            'userSpouse' => $userSpouse,
            'userChildAll' => $userChildAll,
            'userChildAttachmentAll' => $userChildAttachmentAll,
            'application' => $application,
            'applicationAttachmentAll' => $applicationAttachmentAll,
            'userSalary' => $userSalary,
            'userEpnj' => $userEpnj,
            'userSpouseEpnj' => $userSpouseEpnj,
            'cdn' => getCdn()
        ]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        $application = Application::where(['id' => $request->id, 'action_by' => auth()->user()->id])->first();

        // Kalau sudah hantar
        if($application->is_draft == 0)
        {
            return redirect()->route('applicationGreenlane.view', ['id' => $request->id])->with('error', 'TIDAK BOLEH KEMASKINI - Permohonan sudah dihantar!');
        }

        try {

            // Application
            $application->q_category_id         = $request->quarter_category;
            $application->is_rental             = (bool) $request->current_house_rent;  // 1: Sewa
            $application->rental_fee            = $request->current_house_rent;
            $application->distance_from_office  = $request->current_house_distance;
            $application->action_by             = auth()->user()->id;
            $application->action_on             = date('Y-m-d H:i:s');

            // Ulasan
            if($request->has('review'))
            {
                $application->review            = $request->review;
            }

            // Ulasan dokumen
            if($request->has('review_document'))
            {
                // dd($request->review_document);
                $pathReviewDocument = $request->review_document->store('documents/ulasan', 'assets-upload');
                $application->review_attachment = $pathReviewDocument;
            }

            $application->save();


            // User Office
            $updateUserOffice = UserOffice::where('users_id', auth()->user()->id)
                                ->where('data_status', 1)
                                ->orderBy('id','desc')
                                ->take(1)
                                ->update([
                                    'phone_no_office' => $request->phone_no_office,
                                    'fax_no_office'   => $request->fax_no_office,
                                    'email_office'    => $request->email_office,
                                    'action_by'       => auth()->user()->id,
                                    'action_on'       => date('Y-m-d H:i:s'),
                                   ]);

            // User Spouse
            $userSpouseDetails = [];
            $userSpouseDetails['phone_no_hp'] = $request->spouse_phone_no;

            if($request->is_spouse_work == 1)
            {
                $userSpouseDetails['office_address_1']  = $request->spouse_office_address_1;
                $userSpouseDetails['office_address_2']  = $request->spouse_office_address_2;
                $userSpouseDetails['office_address_3']  = $request->spouse_office_address_3;
                $userSpouseDetails['position_name']     = $request->spouse_position;
                $userSpouseDetails['salary']            = $request->spouse_salary;
            }

            $userSpouseDetails['action_by'] = auth()->user()->id;
            $userSpouseDetails['action_on'] = date('Y-m-d H:i:s');


            $updateUserSpouse = UserSpouse::where('users_id', auth()->user()->id)
                                ->where('data_status', 1)
                                ->orderBy('id','desc')
                                ->take(1)
                                ->update($userSpouseDetails);



            // User Child
            foreach($request->child as $childKey => $childValue)
            {

                $updateUserChild = UserChild::where('id', $childValue)
                                    ->where('data_status', 1)
                                    ->update([
                                        'is_cacat' => $request->cacat[$childValue],
                                        'action_by' => auth()->user()->id,
                                        'action_on' => date('Y-m-d H:i:s'),
                                    ]);

                if($request->child_ic_document !== null && isset($request->child_ic_document[$childValue]))
                {
                    // Delete attachment lama
                    $oldChildAttachment = ChildAttachment::where([
                                            'application_id' => $application->id,
                                            'users_child_id' => $childValue,
                                            'data_status' => 1
                                        ])
                                        ->update(['data_status'=> 0]);

                    // Store attachment baru
                    $pathChildIc    = $request->child_ic_document[$childValue]->store('documents/child_attachment', 'assets-upload');

                    $childAttachment = new ChildAttachment;
                    $childAttachment->application_id = $application->id;
                    $childAttachment->users_child_id = $childValue;
                    $childAttachment->path_document  = $pathChildIc;
                    $childAttachment->data_status    = 1;
                    $childAttachment->action_by      = auth()->user()->id;
                    $childAttachment->action_on      = date('Y-m-d H:i:s');
                    $childAttachment->save();
                }
            }

            // EPNJ Pinjaman User
            if($request->is_epnj_user == 1)
            {
                $epnjUserDetails = [
                    'address_1' => $request->user_epnj_address_1,
                    'address_2' => $request->user_epnj_address_2,
                    'address_3' => $request->user_epnj_address_3,
                    'postcode'  => $request->user_epnj_postcode,
                    'mukim'     => $request->user_epnj_mukim,
                ];

                $updateEpnjUser = Epnj::where('ic', auth()->user()->new_ic)
                                ->update($epnjUserDetails);
            }

            // EPNJ Pinjaman Spouse
            if($request->is_epnj_spouse == 1)
            {
                $epnjSpouseDetails = [
                    'address_1' => $request->spouse_epnj_address_1,
                    'address_2' => $request->spouse_epnj_address_2,
                    'address_3' => $request->spouse_epnj_address_3,
                    'postcode'  => $request->spouse_epnj_postcode,
                    'mukim'     => $request->spouse_epnj_mukim,

                ];

                $updateEpnjUser = Epnj::where('ic', auth()->user()->spouse->new_ic)
                                ->update($epnjSpouseDetails);
            }

            if($request->document)
            {
                foreach($request->document as $key => $file)
                {
                    if($file)
                    {
                        // Delete attachment lama
                        $oldApplicationAttachment = ApplicationAttachment::where(['a_id' => $application->id, 'd_id' => $key, 'data_status' => 1])->first();

                        if($oldApplicationAttachment)
                        {
                            $oldApplicationAttachment->data_status = 0;
                            $oldApplicationAttachment->save();
                        }

                        // Store attachment baru
                        $path = $file->store('documents/applications', 'assets-upload');

                        $applicationAttachment = new ApplicationAttachment;
                        $applicationAttachment->a_id            = $application->id;
                        $applicationAttachment->d_id            = $key;
                        $applicationAttachment->path_document   = $path;
                        $applicationAttachment->data_status     = 1;
                        $applicationAttachment->action_by       = auth()->user()->id;
                        $applicationAttachment->action_on       = date('Y-m-d H:i:s');

                        $applicationAttachment->save();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('applicationGreenalne.view', ['id' => $request->id])->with('error', 'Permohonan tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationGreenlane.view', ['id' => $request->id])->with('success', 'Permohonan berjaya dikemaskini! ');
    }

    public function view(Request $request)
    {
        $application = Application::find($request->id);

        $user = auth()->user();

        $userOffice = UserOffice::where('users_id', $user->id)
                        ->where('data_status', 1)
                        ->first();

        $userHouse = UserHouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        $userSalary = UserSalary::where('new_ic', $user->new_ic)
                    ->where('application_id', $application->id)
                    ->where('data_status', 1)
                    ->first();

        $userSpouse = UserSpouse::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->first();

        $userChildAll = UserChild::where('users_id', $user->id)
                    ->where('data_status', 1)
                    ->get();

        $userChildAttachmentAll = ChildAttachment::where('application_id', $application->id)
                                ->whereIn('users_child_id', $userChildAll->pluck('id'))
                                ->where('data_status', 1)
                                ->get();

        $userEpnj = Epnj::where('ic', $user->new_ic)
                    ->where('data_status', 1)
                    ->first();

        $userSpouseEpnj = Epnj::where('ic', $user->spouse?->new_ic)
                            ->where('data_status', 1)
                            ->first();

        $applicationAttachmentAll = ApplicationAttachment::where('a_id', $application->id)
                                    ->where('data_status', 1)
                                    ->orderBy('d_id', 'asc')
                                    ->get();

        return view('modules.QuartersManagement.Greenlane.view', [
            'application' => $application,
            'user' => $user,
            'userHouse' => $userHouse,
            'userOffice' => $userOffice,
            'userSpouse' => $userSpouse,
            'userSalary' => $userSalary,
            'userChildAll' => $userChildAll,
            'userChildAttachmentAll' => $userChildAttachmentAll,
            'userEpnj' => $userEpnj,
            'userSpouseEpnj' => $userSpouseEpnj,
            'applicationAttachmentAll' => $applicationAttachmentAll,
            'cdn' => getCdn()
        ]);
    }

    public function send(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        $application = Application::where(['id' => $id, 'action_by' => auth()->user()->id])->first();

        try {

            $application->is_draft = 0;
            $application->save();

            $applicationHistory = new ApplicationHistory;
            $applicationHistory->application_id         = $application->id;
            $applicationHistory->application_status_id  = 1; // 1: Baru
            $applicationHistory->data_status            = 1;
            $applicationHistory->action_by              = auth()->user()->id;
            $applicationHistory->action_on              = date('Y-m-d H:i:s');
            $applicationHistory->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('applicationGreenlane.view', ['id' => $id])->with('error', 'Permohonan tidak berjaya dihantar!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationGreenlane.view', ['id' => $id])->with('success', 'Permohonan berjaya dihantar! ');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $application = Application::where('data_status', 1)
                        ->where('id', $id)
                        ->first();

            if($application)
            {
                $application->data_status = 0;
                $application->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->route('applicationGreenlane.index')->with('error', 'Permohonan tidak berjaya dipadam!' . ' ' . $e->getMessage());
        }

        return redirect()->route('applicationGreenlane.index')->with('success', 'Permohonan berjaya dipadam! ');
    }
}
