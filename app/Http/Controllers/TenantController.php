<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\ApplicationAttachment;
use App\Models\QuartersCategory;
use App\Models\LeaveOption;
use App\Models\TenantsLeaveAttachment;
use App\Models\TenantQuartersInventory;
use App\Models\TenantsOptionsAttachment;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    //
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

    public function tenantList(QuartersCategory $category)
    {
        if(!checkPolicy("V"))
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

        $tenantAll      = Tenant::getAllCurrentTenantsByCategory($category->id);
        $tenantBlacklistAll = Tenant::getAllBlacklistTenants($category->id);
        $tenantLeftAll  = Tenant::getAllLeftTenants($category->id);
          
        return view( getFolderPath().'.list_tenant',
        [
            'tenantAll' => $tenantAll,
            'tenantLeftAll' => $tenantLeftAll,
            'tenantBlacklistAll' => $tenantBlacklistAll,
            'category' => $category,
        ]);
    }


    public function view(QuartersCategory $category, Tenant $tenant, Request $request)
    {
        $tenant = Tenant::getSingleTenant($category->id, $tenant->id);

        $applicationAttachmentAll = ApplicationAttachment::where('a_id', $tenant->application->id)
                                    ->where('data_status', 1)
                                    ->orderBy('d_id', 'asc')
                                    ->get();

        if(in_array($tenant->leave_status_id, [2,3]))
        {
            $leave_id_arr = stringToArray($tenant->leave_option_id, ',');
            $leaveOptionIdAll = LeaveOption::where('data_status' , 1)->whereIn('id', $leave_id_arr )->get();

            $tenantsLeaveAttachment = TenantsLeaveAttachment::where(['data_status'=> 1 , 'tenants_id' => $tenant->id])->first();

            $tenantsQuartersInventoryAll = TenantQuartersInventory::where(['data_status'=> 1 , 'tenants_id' => $tenant->id])->get();

            $leaveOptionDocumentAll = TenantsOptionsAttachment::select('tenants_options_attachment.*', 'leave_option.description', 'leave_option.flag_option')
            ->leftJoin('leave_option', 'leave_option.id' , '=', 'tenants_options_attachment.leave_option_id')
            ->where('leave_option.data_status',1)->where('tenants_options_attachment.data_status',1) ->where('leave_option.flag_option',2) ->where('tenants_options_attachment.tenants_id', $tenant->id )->get();
        }

        $tab = (in_array($tenant->leave_status_id, [2,3])) ? 'keluar' : (($tenant->blacklist_date) ? 'blacklist' : 'semasa');

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'category' => $category,
                'tenant' => $tenant,
                'applicationAttachmentAll' => $applicationAttachmentAll,
                'cdn' => getCdn(),
                'leaveOptionIdAll' => $leaveOptionIdAll ?? '',
                'tenantsLeaveAttachment' => $tenantsLeaveAttachment ?? '',
                'tenantsQuartersInventoryAll' => $tenantsQuartersInventoryAll ?? '',
                'leaveOptionDocumentAll' => $leaveOptionDocumentAll ?? '',
                'tab' => $tab
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function leaveApproval(QuartersCategory $category, Tenant $tenant, Request $request)
    {
        // if(!checkPolicy("V"))
        // {
        //     return redirect()->route('dashboard')->with('error-permission','access.denied');
        // }

        $tenant = Tenant::getSingleTenant($category->id, $tenant->id);

        $applicationAttachmentAll = ApplicationAttachment::where('a_id', $tenant->application->id)
                                    ->where('data_status', 1)
                                    ->orderBy('d_id', 'asc')
                                    ->get();

        if(in_array($tenant->leave_status_id, [1,2]))
        {
            $leave_id_arr = stringToArray($tenant->leave_option_id, ',');
            $leaveOptionIdAll = LeaveOption::where('data_status' , 1)->whereIn('id', $leave_id_arr )->get();

            $tenantsLeaveAttachment = TenantsLeaveAttachment::where(['data_status'=> 1 , 'tenants_id' => $tenant->id])->first();

            $tenantsQuartersInventoryAll = TenantQuartersInventory::where(['data_status'=> 1 , 'tenants_id' => $tenant->id])->get();

            $leaveOptionDocumentAll = TenantsOptionsAttachment::select('tenants_options_attachment.*', 'leave_option.description', 'leave_option.flag_option')
            ->leftJoin('leave_option', 'leave_option.id' , '=', 'tenants_options_attachment.leave_option_id')
            ->where('leave_option.data_status',1)->where('tenants_options_attachment.data_status',1) ->where('leave_option.flag_option',2) ->where('tenants_options_attachment.tenants_id', $tenant->id )->get();
        }

        $tab = (in_array($tenant->leave_status_id, [1,2])) ? 'keluar' : (($tenant->blacklist_date) ? 'blacklist' : 'semasa');

        //Monitoring attachment
        $attachmentAll = $tenant->monitor_leave->attachments ?? null;

        return view( getFolderPath().'.leave_approval',
        [
            'category' => $category,
            'tenant' => $tenant,
            'applicationAttachmentAll' => $applicationAttachmentAll,
            'cdn' => getCdn(),
            'leaveOptionIdAll' => $leaveOptionIdAll ?? '',
            'tenantsLeaveAttachment' => $tenantsLeaveAttachment ?? '',
            'tenantsQuartersInventoryAll' => $tenantsQuartersInventoryAll ?? '',
            'leaveOptionDocumentAll' => $leaveOptionDocumentAll ?? '',
            'attachmentAll' => $attachmentAll ?? '',
            'tab' => $tab
        ]);
    }

    public function leaveApprovalProcess(QuartersCategory $category, Tenant $tenant, Request $request)
    {
        // Validate user permission
        if(!checkPolicy("U"))
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

        DB::beginTransaction();

        try {
            $approved = Tenant::where([
                'id' => $tenant->id,
                'leave_status_id' => 2,
                'data_status' => 1
            ])
            ->update([
                'leave_status_id' => 3,
                'leave_date' => convertDatepickerDb($request->approval_date),
                'approved_by' => loginId(),
                'approved_on' => convertDatepickerDb($request->approval_date),
            ]);

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('tenant.leaveApproval', ['category' => $category, 'tenant' => $tenant])->with('error', 'Pengesahan penghuni keluar tidak berjaya disimpan!' . ' ' . $e->getMessage());
        }

        return redirect()->route('tenant.tenantList', ['category' => $category])->with('success', 'Pengesahan penghuni keluar berjaya disimpan');
    }
}
