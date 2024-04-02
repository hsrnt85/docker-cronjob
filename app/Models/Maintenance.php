<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $table = 'maintenance';
    protected $dates=['start_date', 'end_date'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function type()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function status()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id');
    }

    //MAKLUMAT PENYELENGGARAAN
    public static function maintenance($id)
    {
        $data= self::select('contractor.company_name', 'officer.users_id', 'maintenance.id', 'maintenance.start_date', 'maintenance.end_date', 'maintenance.monitoring_officer_id')
        ->join('officer', 'officer.id', '=', 'maintenance.monitoring_officer_id')
        ->join('contractor', 'contractor.id', '=', 'maintenance.contractor_id')
        ->where('maintenance.id',  $id)->first();

        return $data;
}
}
