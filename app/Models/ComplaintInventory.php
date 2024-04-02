<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintInventory extends Model
{
    use HasFactory;

    protected $table    = 'complaint_inventory';
    protected $primaryKey = 'id';
    protected $fillable = ['path_document'];

    public $timestamps  = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function attachment()
    {
        return $this->hasOne(ComplaintInventoryAttachment::class, 'complaint_inventory_id');
    }

    public static function getComplaintInventoryAll($complaint_id)
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->get();

        return $data;
    }

    public static function getComplaintInventoryMaintanance($complaint_id) //selenggara
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->where('flag_action', 1)->get();

        return $data;
    }

    public static function getComplaintInventoryRejected($complaint_id) //ditolak
    {
        $data = self::where('data_status', 1)->where('complaint_id', $complaint_id )->whereNull('flag_action')->get();

        return $data;
    }

    //MAINTENANCE TRANSACTION
    public static function getComplaintInventoryById($id)
    {
        $data = self::join('complaint as c', 'c.id' , '=', 'complaint_inventory.complaint_id')->where(['complaint_inventory.id' => $id, 'complaint_inventory.data_status' => 1, 'c.data_status' => 1])->first();

        return $data;
    }

    public function maintenance_status()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id');
    }

    public function monitoring_officer()
    {
        return $this->belongsTo(Officer::class, 'monitoring_officer_id');
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    // public function maintenance_transaction()
    // {
    //     return $this->belongsTo(MaintenanceTransaction::class, 'complaint_inventory_id');
    // }

    // public function current_maintenance_transaction()
    // {
    //     return $this->hasOne(MaintenanceTransaction::class, 'complaint_inventory_id')->latestOfMany();
    // }

    // public static function getComplaintNotBeenMaintained() //not yet selected for maintenance
    // {
    //     $data = self::select('complaint.id as complaint_id', 'complaint.complaint_type', 'complaint.ref_no','complaint_inventory.id as complaint_inventory_id', 'complaint_inventory.description', 'complaint_type.complaint_name', 'complaint_inventory.inventory_id')
    //     ->join('complaint', 'complaint.id', '=', 'complaint_inventory.complaint_id')
    //     ->join('complaint_type', 'complaint_type.id', '=', 'complaint.complaint_type')
    //     ->whereRaw("complaint_inventory.id NOT IN (SELECT complaint_inventory_id FROM maintenance_complaint_inventory where data_status = 1 )")
    //     ->where(['complaint_inventory.flag_action'=> 1, 'complaint_inventory.data_status'=> 1])->get();

    //     return $data;
    // }

}
