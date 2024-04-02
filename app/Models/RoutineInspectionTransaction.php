<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineInspectionTransaction extends Model
{
    use HasFactory;

    protected $table    = 'routine_inspection_transaction';
    public $timestamps  = false;
    protected $dates    = ['action_on', 'deleted_on'];

    public function routineInspection()
    {
        return $this->belongsTo(RoutineInspection::class, 'routine_inspection_id');
    }

    public function inspectionStatus()
    {
        return $this->belongsTo(InspectionStatus::class, 'inspection_status_id');
    }

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'approval_officer_id');
    }

    public function monitoring_officer()
    {
        return $this->belongsTo(Officer::class, 'monitoring_officer_id');
    }

    public function approvalStatus()
    {
        return $this->belongsTo(ApprovalStatus::class, 'approval_status_id');
    }

    public static function getAllInspectionByApprovalOfficer($officerId)
    {
        $data = self::with('routineInspection')
                ->with('routineInspection.quarters_category')
                ->with('routineInspection.quarters')
                ->with('approvalStatus')
                ->where('data_status', 1)
                ->where('approval_officer_id', $officerId)
                ->where('inspection_status_id', 1)
                ->where('approval_status_id', null)
                ->get();

        return $data;
    }

    public static function getAllInspectionByApprovalOfficerWithStatus($officerId)
    {
        $data = self::with('routineInspection')
                ->with('routineInspection.quarters_category')
                ->with('routineInspection.quarters')
                ->with('approvalStatus')
                ->where('data_status', 1)
                ->where('approval_officer_id', $officerId)
                ->whereNotNull('approval_status_id')
                ->get();

        return $data;
    }

    public function attachments()
    {
        return $this->hasMany(RoutineInspectionTransactionAttachment::class);
    }

}
