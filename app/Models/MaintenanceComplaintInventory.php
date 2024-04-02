<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceComplaintInventory extends Model
{
    use HasFactory;
    protected $table = 'maintenance_complaint_inventory';
    protected $dates= ['start_date','end_date'];
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    public function maintenance_status()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id');
    }

    public static function getMaintenanceInventory($id)
    {
        $data = self::select('maintenance_complaint_inventory.id','maintenance_complaint_inventory.complaint_id', 'complaint.ref_no', 'complaint.complaint_type', 'complaint_inventory.inventory_id', 'complaint_inventory.description', 'officer.users_id', 'maintenance.start_date', 'maintenance.end_date', 'maintenance.contractor_id', 'maintenance_complaint_inventory.complaint_inventory_id', 'maintenance_transaction.maintenance_complaint_inventory_id as mt_mci_id' , 'maintenance_transaction.maintenance_status_id')
        ->join('maintenance', 'maintenance.id', '=', 'maintenance_complaint_inventory.maintenance_id')
        ->join('complaint', 'complaint.id', '=', 'maintenance_complaint_inventory.complaint_id')
        ->join('complaint_inventory', 'complaint_inventory.id', '=', 'maintenance_complaint_inventory.complaint_inventory_id')
        ->join('officer', 'officer.id', '=', 'maintenance.monitoring_officer_id')
        ->leftJoin('maintenance_transaction', 'maintenance_transaction.maintenance_complaint_inventory_id', '=', 'maintenance_complaint_inventory.id') // For Maintenance Setting view page
        ->where(['maintenance_complaint_inventory.data_status'=> 1, 'maintenance_complaint_inventory.maintenance_id'=>$id])->get();

        return $data;
    }

    public static function maintenanceInventory($id) //by maintenance id
    {
        $data = self::select('maintenance_complaint_inventory.id','maintenance_complaint_inventory.complaint_id', 'complaint.ref_no', 'complaint.complaint_type', 'complaint_inventory.inventory_id', 'complaint_inventory.description', 'officer.users_id', 'maintenance.start_date', 'maintenance.end_date', 'maintenance.contractor_id')
        ->join('maintenance', 'maintenance.id', '=', 'maintenance_complaint_inventory.maintenance_id')
        ->join('complaint', 'complaint.id', '=', 'maintenance_complaint_inventory.complaint_id')
        ->join('complaint_inventory', 'complaint_inventory.id', '=', 'maintenance_complaint_inventory.complaint_inventory_id')
        ->join('officer', 'officer.id', '=', 'maintenance.monitoring_officer_id')
        ->where(['maintenance_complaint_inventory.data_status'=> 1, 'maintenance_complaint_inventory.maintenance_id'=>$id])->first();

        return $data;
    }

    public static function maintenanceInventorybyId($id) //by id
    {
        $data = self::select('maintenance.id as  maintenance_id','maintenance_complaint_inventory.id','maintenance_complaint_inventory.complaint_id', 'complaint.ref_no', 'complaint.complaint_type', 'complaint_inventory.inventory_id', 'complaint_inventory.description', 'officer.users_id', 'maintenance.start_date', 'maintenance.end_date', 'maintenance.contractor_id')
        ->join('maintenance', 'maintenance.id', '=', 'maintenance_complaint_inventory.maintenance_id')
        ->join('complaint', 'complaint.id', '=', 'maintenance_complaint_inventory.complaint_id')
        ->join('complaint_inventory', 'complaint_inventory.id', '=', 'maintenance_complaint_inventory.complaint_inventory_id')
        ->join('officer', 'officer.id', '=', 'maintenance.monitoring_officer_id')
        ->where(['maintenance_complaint_inventory.data_status'=> 1, 'maintenance_complaint_inventory.id'=>$id])->first();

        return $data;
    }

    // public static function maintenanceInventorybyIdandCID($id) //by id
    // {
    //     $data = self::select('maintenance.id as  maintenance_id','maintenance_complaint_inventory.id','maintenance_complaint_inventory.complaint_id', 'complaint.ref_no', 'complaint.complaint_type', 'complaint_inventory.inventory_id', 'complaint_inventory.description', 'officer.users_id', 'maintenance.start_date', 'maintenance.end_date', 'maintenance.contractor_id')
    //     ->join('maintenance', 'maintenance.id', '=', 'maintenance_complaint_inventory.maintenance_id')
    //     ->join('complaint', 'complaint.id', '=', 'maintenance_complaint_inventory.complaint_id')
    //     ->join('complaint_inventory', 'complaint_inventory.id', '=', 'maintenance_complaint_inventory.complaint_inventory_id')
    //     ->join('officer', 'officer.id', '=', 'maintenance.monitoring_officer_id')
    //     ->where(['maintenance_complaint_inventory.data_status'=> 1, 'maintenance_complaint_inventory.id'=>$id, ''])->first();

    //     return $data;
    // }


    public static function maintenanceInventorybyCID($id, $c_inventory_id) //by maintenance id and complaint_inventory_id
    {
        $data = self::where(['maintenance_id' => $id , 'complaint_inventory_id' => $c_inventory_id ,'data_status' => 1])->first();

        return $data;
    }



}

