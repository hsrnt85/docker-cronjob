<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_Tenant;
use App\Models\MonitoringTenantLeave;
use App\Models\MonitoringTenantLeaveAttachment;
use App\Models\Tenant;
use App\Models\TenantQuartersInventory;
use App\Notifications\MonitoringTenantsLeaveNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Api_TenantLeaveController extends Controller
{
    public function getPenghuniKeluar(Request $request)
    {
        $user       = auth('sanctum')->user();
        // $officer    = $user->officer();
        $district_id = (!is_all_district()) ?  districtId() : null;

        $tenantsAll  = Api_Tenant::with('quarters_category:id,name')
            ->with('quarters:id,unit_no,address_1,address_2,address_3')
            ->with('tenant_inventory:id,tenants_id,inventory_id,quantity_in')
            ->where('data_status', 1)
            ->where('leave_status_id', 1)
            ->select('id', 'name', 'new_ic', 'phone_no_hp', 'phone_no_office', 'quarters_id', 'quarters_category_id')
            ->selectRaw('DATE_FORMAT(leave_application_date, "%d/%m/%Y") as formatted_leave_date');

        if ($district_id) {
            $tenantsAll = $tenantsAll->whereHas('quarters_category', function ($subQ) use ($district_id) {
                $subQ->where('district_id', $district_id);
            });
        }

        $tenantsAll = $tenantsAll->get();

        $tenantInventoryAll = TenantQuartersInventory::with('inventory:id,name')
            ->whereIn('tenants_id', $tenantsAll->pluck('id'))
            ->select(DB::raw('id as tid'), 'tenants_id', 'inventory_id')
            ->get();

        // Masukkan inventory name
        $tenantsAll->each(function ($tenant, $key) use ($tenantInventoryAll) {
            $tenant->tenant_inventory->each(function ($ti) use ($tenantInventoryAll) {
                $ti->name = $tenantInventoryAll->where('inventory_id', $ti->inventory_id)->first()->inventory->name;
            });
        });

        return response()->json([
            'tenantsAll' => $tenantsAll,
        ], 200);
    }

    public function getPenghuniKeluarById(Request $request)
    {
        $user        = auth('sanctum')->user();
        $id          = $request->id;
        $district_id = (!is_all_district()) ?  districtId() : null;

        $tenantsAll  = Api_Tenant::with('quarters_category:id,name')
            ->with('quarters:id,unit_no,address_1,address_2,address_3')
            ->with('tenant_inventory:id,tenants_id,inventory_id,quantity_in')
            ->where('data_status', 1)
            ->where('leave_status_id', 1)
            ->select('id', 'name', 'new_ic', 'phone_no_hp', 'phone_no_office', 'quarters_id', 'quarters_category_id')
            ->selectRaw('DATE_FORMAT(leave_date, "%d/%m/%Y") as formatted_leave_date');

        if ($district_id) {
            $tenantsAll = $tenantsAll->whereHas('quarters_category', function ($subQ) use ($district_id) {
                $subQ->where('district_id', $district_id);
            });
        }

        $tenantsAll = $tenantsAll->where('id', $id)->get();

        $tenantInventoryAll = TenantQuartersInventory::with('inventory:id,name')
            ->whereIn('tenants_id', $tenantsAll->pluck('id'))
            ->select(DB::raw('id as tid'), 'tenants_id', 'inventory_id')
            ->get();

        // Masukkan inventory name
        $tenantsAll->each(function ($tenant, $key) use ($tenantInventoryAll) {
            $tenant->tenant_inventory->each(function ($ti) use ($tenantInventoryAll) {
                $ti->name = $tenantInventoryAll->where('inventory_id', $ti->inventory_id)->first()->inventory->name;
            });
        });

        $tenantSingle = $tenantsAll->first();

        return response()->json([
            'tenantSingle' => $tenantSingle,
        ], 200);
    }

    public function updatePemantauanPenghuniKeluar(Request $request)
    {
        $user    = auth('sanctum')->user();
        $officer = $user->officer();

        $tidAll = $request->tid; //tenant inventory id
        $statusAll = $request->status;
        $conditionAll = $request->condition;
        $quantityAll = $request->quantity;
        $tenant_id = $request->tenant_id;
        $tenant = Api_Tenant::find($tenant_id);

        DB::beginTransaction();

        try {

            foreach($tidAll as $key => $tid) {
                $update = TenantQuartersInventory::where('id', $tid)
                ->update([
                    'monitoring_inventory_status_id' => $statusAll[$key],
                    'monitoring_inventory_condition_id' => $conditionAll[$key],
                    'monitoring_quantity' => $quantityAll[$key],
                ]);
            }

            $updateTenant = Tenant::where('id', $tenant_id)
                ->update([
                    'leave_status_id' => 2,
                    'action_on' => currentDate(),
                    'action_by' => $user->id,
                ]);

            $mtl = new MonitoringTenantLeave();
            $mtl->tenants_id = $request->tenant_id;
            $mtl->monitoring_date = currentDate();
            $mtl->monitoring_leave_status_id = $request->monitoring_leave_status;
            $mtl->description = $request->description;
            $mtl->officer_id = $officer->id;
            $mtl->action_on = currentDate();
            $mtl->action_by = $user->id;
            $mtl->save();
            $mtl->refresh();

            if ($request->gambar != null) {
                foreach ($request->gambar  as $key => $file) {
                    $path = $file->store('documents/monitoring_tenant_leave', 'assets-upload');

                    $attachment = new MonitoringTenantLeaveAttachment();

                    $attachment->monitoring_tenants_leave_id = $mtl->id;
                    $attachment->path_document  = $path;
                    $attachment->data_status    = 1;
                    $attachment->action_by      = $user->id;
                    $attachment->action_on      = currentDate();

                    $saved = $attachment->save();
                }
            }

            $mtl->monitoring_officer->user->notify(new MonitoringTenantsLeaveNotification($tenant->new_ic, $tenant->quarters_category_id, $tenant->id));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return response()->json([
                'status' => "Error",
                'detail' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => "Sukses",
            'detail' => "Pemantauan penghuni keluar berjaya disimpan"
        ], 200);
    }
}
