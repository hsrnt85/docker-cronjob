<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpecialPermission;
use App\Models\SpecialPermissionAttachment;
use App\Models\District;
use App\Models\Position;
use App\Models\PositionGradeType;
use App\Models\PositionGrade;
use App\Models\ServicesType;
use App\Models\Organization;
use App\Models\User;
use App\Http\Requests\SpecialPermissionRequest;
use App\Models\UserInfo;
// use DB;
use Illuminate\Support\Facades\DB;

class SpecialPermissionController extends Controller
{
    public function index()
    {

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $specialPermission = SpecialPermission::join('users','users.id','=','special_permission.user_id')
            ->leftJoin('position','position.id','=','users.position_id')
            ->leftJoin('position_grade_code','position_grade_code.id','=','users.position_grade_code_id')
            ->leftJoin('position_grade','position_grade.id','=','users.position_grade_id')
            ->leftJoin('users_address_office','users_address_office.users_id','=','users.id')
            ->leftJoin('organization','organization.id','=','users_address_office.organization_id')
            ->leftJoin('users_info as ui','ui.id','=','special_permission.user_info_id')
            ->leftJoin('position as ui_position','ui_position.id','=','ui.position_id')
            ->leftJoin('position_grade_code as ui_position_grade_code','ui_position_grade_code.id','=','ui.position_grade_code_id')
            ->leftJoin('position_grade as ui_position_grade','ui_position_grade.id','=','ui.position_grade_id')
            ->select('special_permission.id','users.name','users.new_ic','position.position_name','position_grade_code.grade_type','position_grade.grade_no',
                'ui_position.position_name as ui_position_name','ui_position_grade_code.grade_type as ui_grade_type','ui_position_grade.grade_no as ui_grade_no',
                'organization.name as organization_name')
            ->where('special_permission.data_status', 1);

        if($district_id)
        {
            $specialPermission = $specialPermission->where('users_address_office.district_id', $district_id);
        }

        $listSpecialPermission = $specialPermission->get();

        return view( getFolderPath().'.list',
        [
            'listSpecialPermission' => $listSpecialPermission

        ]);
    }

    public function ajaxCheckIcUser(Request $request)
    {
        try {
            $getFields = User::leftJoin('special_permission', function ($join){
                $join->on('special_permission.user_id','=','users.id');
                    $join->where('special_permission.data_status', '=', 1);
                })
                ->select('users.id as user_id', 'special_permission.user_id as special_user_id')
                ->where('users.data_status', 1)
                ->where('new_ic',$request->new_ic)
                ->first();

            return response()->json($getFields, 200);

           } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function ajaxGetField(Request $request)
    {
        try {
            $getFields = User::leftJoin('position','position.id','=','users.position_id')
                ->leftJoin('position_grade_code','position_grade_code.id','=','users.position_grade_code_id')
                ->leftJoin('position_grade','position_grade.id','=','users.position_grade_id')
                ->leftJoin('users_address_office','users_address_office.users_id','=','users.id')
                ->leftJoin('organization','organization.id','=','users_address_office.organization_id')
                ->leftJoin('services_type','services_type.id','=','users.services_type_id')
                ->leftJoin('district','district.id','=','users_address_office.district_id')
                ->select('users.id','users.name','users.new_ic','users.email','position.position_name','position_grade_code.grade_type','position_grade.grade_no',
                        'organization.name as organization_name','services_type.services_type','district_name')
                ->where('new_ic',$request->new_ic)->first();

            $latest_user_info = UserInfo::getLatestUserInfoByUserId($getFields->id);

            if ($latest_user_info) 
            {
                $getFields->user_info_position_name = $latest_user_info->position->position_name;
                $getFields->user_info_grade_type = $latest_user_info->position_grade_type->grade_type;
                $getFields->user_info_grade_no = $latest_user_info->position_grade->grade_no;
                $getFields->user_info_services_type = $latest_user_info->services_type->services_type;
            }

            return response()->json($getFields, 200);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $positionAll = Position::where('data_status', 1)->get();
        $positionGradeTypeAll = PositionGradeType::where('data_status', 1)->get();
        $positionGradeAll = PositionGrade::where('data_status', 1)->get();
        $districtAll = District::where('data_status', 1)->get();
        $servicesTypeAll = ServicesType::where('data_status', 1)->get();
        $organizationAll = Organization::where('data_status', 1)->get();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'positionAll' => $positionAll,
                'positionGradeTypeAll' => $positionGradeTypeAll,
                'positionGradeAll' => $positionGradeAll,
                'districtAll' => $districtAll,
                'servicesTypeAll' => $servicesTypeAll,
                'organizationAll' => $organizationAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(SpecialPermissionRequest $request)
    {
        $user_id = $request->user_id;

        //Check user id
        $check = SpecialPermission::where('data_status', 1)->where('user_id', $user_id)->first();

        if($check)
        {
            return redirect()->route('specialPermission.create')->with('error', 'No Kad Pengenalan sudah wujud dalam Kebenaran Khas!');
        }
        else
        {
            $latest_user_info  = UserInfo::getLatestUserInfoByUserId($user_id);

            $specialPermission = new SpecialPermission;

            $specialPermission->user_id         = $user_id;
            $specialPermission->user_info_id    = ($latest_user_info) ? $latest_user_info->id : null;
            $specialPermission->remarks         = $request->remarks;
            $specialPermission->data_status     = 1;
            $specialPermission->action_by       = loginId();
            $specialPermission->action_on       = currentDate();

            $saved = $specialPermission->save();

            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $specialPermission->user?->name);
            //------------------------------------------------------------------------------------------------------------------

            $supporting_document = $request->supporting_document;

            if($supporting_document)
            {
                foreach($supporting_document as $attachment)
                {
                    $filename = currentDateTimeFilename()."_".$attachment->getClientOriginalName();
                    $path = $attachment->storeAs('documents/special_permission_attachment', $filename, 'assets-upload');

                    $SpecialPermissionAttachment                        = new SpecialPermissionAttachment;
                    $SpecialPermissionAttachment->special_permission_id = $specialPermission->id;
                    $SpecialPermissionAttachment->path_document         = $path;
                    $SpecialPermissionAttachment->action_by             = loginId();
                    $SpecialPermissionAttachment->action_on             = currentDate();
                    $SpecialPermissionAttachment->save();
                }
            }

            if(!$saved)
            {
                return redirect()->route('specialPermission.create')->with('error', 'Kebenaran Khas tidak berjaya ditambah!');
            }
            else
            {
                return redirect()->route('specialPermission.index')->with('success', 'Kebenaran Khas berjaya ditambah!');
            }
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $specialPermission = SpecialPermission::join('users','users.id','=','special_permission.user_id')
            ->leftJoin('position','position.id','=','users.position_id')
            ->leftJoin('position_grade_code','position_grade_code.id','=','users.position_grade_code_id')
            ->leftJoin('position_grade','position_grade.id','=','users.position_grade_id')
            ->leftJoin('users_address_office','users_address_office.users_id','=','users.id')
            ->leftJoin('organization','organization.id','=','users_address_office.organization_id')
            ->leftJoin('services_type','services_type.id','=','users.services_type_id')
            ->leftJoin('district','district.id','=','users_address_office.district_id')
            ->leftJoin('users_info as ui','ui.id','=','special_permission.user_info_id')
            ->leftJoin('position as ui_position','ui_position.id','=','ui.position_id')
            ->leftJoin('position_grade_code as ui_position_grade_code','ui_position_grade_code.id','=','ui.position_grade_code_id')
            ->leftJoin('position_grade as ui_position_grade','ui_position_grade.id','=','ui.position_grade_id')
            ->leftJoin('services_type as ui_services_type','ui_services_type.id','=','ui.services_type_id')
            ->select('special_permission.id','users.name','users.new_ic','users.email','position.position_name','position_grade_code.grade_type','position_grade.grade_no',
                    'organization.name as organization_name','services_type.services_type','district_name', 'special_permission.remarks',
                    'ui_position.position_name as ui_position_name','ui_position_grade_code.grade_type as ui_grade_type','ui_position_grade.grade_no as ui_grade_no',
                    'ui_services_type.services_type as ui_services_type')
            ->where('special_permission.id', $id)->first();

        $special_permission_attachment = SpecialPermissionAttachment::where('data_status', 1)->where('special_permission_id', $specialPermission->id)->get();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'specialPermission' => $specialPermission,
                'special_permission_attachment' => $special_permission_attachment
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function edit(Request $request){

        $id = $request->id;
        $specialPermission = SpecialPermission::join('users','users.id','=','special_permission.user_id')
            ->leftJoin('position','position.id','=','users.position_id')
            ->leftJoin('position_grade_code','position_grade_code.id','=','users.position_grade_code_id')
            ->leftJoin('position_grade','position_grade.id','=','users.position_grade_id')
            ->leftJoin('users_address_office','users_address_office.users_id','=','users.id')
            ->leftJoin('organization','organization.id','=','users_address_office.organization_id')
            ->leftJoin('services_type','services_type.id','=','users.services_type_id')
            ->leftJoin('district','district.id','=','users_address_office.district_id')
            ->leftJoin('users_info as ui','ui.id','=','special_permission.user_info_id')
            ->leftJoin('position as ui_position','ui_position.id','=','ui.position_id')
            ->leftJoin('position_grade_code as ui_position_grade_code','ui_position_grade_code.id','=','ui.position_grade_code_id')
            ->leftJoin('position_grade as ui_position_grade','ui_position_grade.id','=','ui.position_grade_id')
            ->leftJoin('services_type as ui_services_type','ui_services_type.id','=','ui.services_type_id')
            ->select('special_permission.id','users.name','users.new_ic','users.email','position.position_name','position_grade_code.grade_type','position_grade.grade_no',
                    'organization.name as organization_name','services_type.services_type','district_name', 'special_permission.remarks', 'special_permission.user_id',
                    'ui_position.position_name as ui_position_name','ui_position_grade_code.grade_type as ui_grade_type','ui_position_grade.grade_no as ui_grade_no',
                    'ui_services_type.services_type as ui_services_type')
            ->where('special_permission.id', $id)->first();

        $special_permission_attachment = SpecialPermissionAttachment::where('data_status', 1)->where('special_permission_id', $specialPermission->id)->get();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'specialPermission' => $specialPermission,
                'special_permission_attachment' => $special_permission_attachment
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {

            $special_permission = SpecialPermission::findOrFail($id);

            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data before changes
            $data_before = $special_permission->getRawOriginal();//dd($data_before);
            $data_before['item']= $special_permission->toArray() ?? [];//dd($data_before);
            //------------------------------------------------------------------------------------------------------------------

            $special_permission->user_id      = $request->user_id;
            $special_permission->remarks      = $request->remarks;
            $special_permission->action_by       = loginId();
            $special_permission->action_on       = currentDate();
            $special_permission->save();

            $special_permission_attachment = $request->supporting_document;
            if($special_permission_attachment != NULL){

            foreach($special_permission_attachment as $i => $attachment)
            {

                $special_permission_id = isset($request->supporting_document_id[$i]) ? $request->supporting_document_id[$i] : 0;

                //update------
                if($special_permission_id)
                {
                    $special_permission_attachment = isset($request->supporting_document[$i]) ? $request->supporting_document[$i]: "";

                    if($special_permission_attachment)
                    {
                        foreach($special_permission_attachment as $i => $attachment)
                        {
                            $filename = currentDateTimeFilename()."_".$attachment->getClientOriginalName();
                            $path = $attachment->store('documents/special_permission_attachment', 'assets-upload');

                            $SpecialPermissionAttachment = SpecialPermissionAttachment::where([['data_status', 1],['id', $special_permission_id]])->first();
                            $SpecialPermissionAttachment->path_document         = $path;
                            $SpecialPermissionAttachment->action_by             = loginId();
                            $SpecialPermissionAttachment->action_on             = currentDate();
                            $SpecialPermissionAttachment->save();
                        }
                    }
                }
                else  //insert new -------
                {
                    $special_permission_attachment = isset($request->supporting_document[$i]) ? $request->supporting_document[$i]: "";
                    if($special_permission_attachment)
                    {
                        foreach($special_permission_attachment as $attachment)
                        {
                            $filename = currentDateTimeFilename()."_".$attachment->getClientOriginalName();
                            $path = $attachment->storeAs('documents/special_permission_attachment', $filename , 'assets-upload');

                            $SpecialPermissionAttachment                        = new SpecialPermissionAttachment;
                            $SpecialPermissionAttachment->special_permission_id = $special_permission->id;
                            $SpecialPermissionAttachment->path_document         = $path;
                            $SpecialPermissionAttachment->action_by             = loginId();
                            $SpecialPermissionAttachment->action_on             = currentDate();
                            $SpecialPermissionAttachment->save();
                        }
                    }
                }
            }
            }

            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            $data_after = $special_permission;
            $data_after['item'] = $special_permission->toArray() ?? [];

            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            setUserActivity("U", $special_permission->user?->name, $data_before_json, $data_after_json);
            //------------------------------------------------------------------------------------------------------------------

            DB::commit();

            return redirect()->route('specialPermission.view', ['id'=>$request->id])->with('success', 'Kebenaran Khas berjaya dikemaskini!');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('specialPermission.edit', ['id'=>$request->id])->with('error', 'Kebenaran Khas tidak berjaya dikemaskini!');
        }
    }

    public function destroyByRow(Request $request)
    {
        $special_permission_attachment_id = $request->row_supporting_document_id;
        $special_permission_id = $request->id;

        try {

            SpecialPermissionAttachment::where(['id'=> $special_permission_attachment_id])
                                    ->update([
                                        'data_status' => 0,
                                        'delete_by' => auth()->user()->id,
                                        'delete_on' => date('Y-m-d H:i:s')
                                    ]);


            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('specialPermission.edit', ['id'=>$special_permission_id])->with('error', 'Dokumen Sokongan tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('specialPermission.edit', ['id'=>$special_permission_id])->with('success', 'Dokumen Sokongan berjaya dihapus!');
    }

    public function destroy(Request $request)
    {
        $specialPermission = SpecialPermission::where('id', $request->id)->first();

        setUserActivity("D", $specialPermission->user?->name);

        $specialPermission->data_status  = 0;
        $specialPermission->delete_by    = loginId();
        $specialPermission->delete_on    = currentDate();

        $deleted = $specialPermission->save();

        $special_permission_attachment = SpecialPermissionAttachment::where(['special_permission_id' => $specialPermission->id , 'data_status'=> 1])->get();

        if($special_permission_attachment)
        {
            foreach($special_permission_attachment as $sp_attachment)
            {
                $sp_attachment->data_status  = 0;
                $sp_attachment->delete_by    = loginId();
                $sp_attachment->delete_on    = currentDate();

                 $sp_attachment->save();
            }
        }

        if(!$deleted)
        {
            return redirect()->route('specialPermission.index', ['id'=>$request->id])->with('error', 'Kebenaran Khas tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('specialPermission.index')->with('success', 'Kebenaran Khas berjaya dihapus!');
        }
    }

}
