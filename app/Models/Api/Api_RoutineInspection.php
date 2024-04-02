<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_RoutineInspection extends Model
{
    use HasFactory;

    protected $table   = 'routine_inspection';
    public $timestamps = false;

    public function quarters_category()
    {
        return $this->belongsTo(Api_QuartersCategory::class, 'quarters_category_id');
    }

    public function quarters()
    {
        return $this->belongsTo(Api_Quarters::class, 'quarters_id');
    }

    public function status()
    {
        return $this->belongsTo(Api_ApprovalStatus::class, 'approval_status_id');
    }

    public function monitoring_officer()
    {
        return $this->belongsTo(Api_Officer::class, 'monitoring_officer_id');
    }

    public function approval_officer()
    {
        return $this->belongsTo(Api_Officer::class, 'approval_officer_id');
    }

    public function inspection_transaction()
    {
        return $this->hasMany(Api_RoutineInspectionTransaction::class, 'routine_inspection_id')->where('data_status', 1);
    }

    public static function countRoutineInspectionActive($officerId, $start, $end)
    {
        $data = self::where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereDoesntHave('inspection_transaction')
                ->whereDate('inspection_date', '>=', $start)
                ->whereDate('inspection_date', '<=', $end)
                ->count();

        return $data;
    }

    public static function getRoutineInspectionActiveAll($officerId, $start, $end)
    {
        $data = self::with('quarters_category')
                ->where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereDoesntHave('inspection_transaction')
                ->whereDate('inspection_date', '>=', $start)
                ->whereDate('inspection_date', '<=', $end)
                ->get();

        return $data;
    }

    public static function getRoutineInspectionActiveById($officerId, $id)
    {
        $data = self::with('quarters_category')
                ->where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereDoesntHave('inspection_transaction')
                ->where('id', $id)
                ->first();

        return $data;
    }

    public static function getRoutineInspectionInProgressAll($officerId, $start, $end)
    {
        $data = self::with('quarters_category')
                ->with('inspection_transaction')
                ->with('inspection_transaction.attachments')
                ->where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereHas('inspection_transaction', function($query){
                    $query->where('inspection_status_id', 2);
                })
                ->whereDate('inspection_date', '>=', $start)
                ->whereDate('inspection_date', '<=', $end)
                ->get();

        return $data;
    }

    public static function getRoutineInspectionInProgressById($officerId, $id)
    {
        $data = self::with('quarters_category')
                ->with('inspection_transaction')
                ->with('inspection_transaction.attachments')
                ->where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereHas('inspection_transaction', function($query){
                    $query->where('inspection_status_id', 2);
                })
                ->where('id', $id)
                ->first();

        return $data;
    }

    public static function countRoutineInspectionCompleted($officerId, $start, $end)
    {
        // status selesai && belum approval
        $data = self::where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereHas('inspection_transaction', function($query){
                    $query->where('inspection_status_id', 1)
                    ->where('approval_status_id', null);
                })
                ->whereDate('inspection_date', '>=', $start)
                ->whereDate('inspection_date', '<=', $end)
                ->count();

        return $data;
    }

    public static function countRoutineInspectionApproved($officerId)
    {
        // status selesai && sudah approval
        $data = self::where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereHas('inspection_transaction', function($query){
                    $query->where('approval_status_id', 1);
                })
                ->count();

        return $data;
    }

    public static function getRoutineInspectionCompletedAll($officerId, $start, $end)
    {
        $data = self::with('quarters_category')
                ->with('inspection_transaction')
                ->with('inspection_transaction.attachments')
                ->where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereHas('inspection_transaction', function($query){
                    $query->where('inspection_status_id', 1)
                    ->where('approval_status_id', null);
                })
                ->whereDate('inspection_date', '>=', $start)
                ->whereDate('inspection_date', '<=', $end)
                ->get();

        return $data;
    }

    public static function getRoutineInspectionCompletedById($officerId, $id)
    {
        $data = self::with('quarters_category')
                ->with('inspection_transaction')
                ->with('inspection_transaction.attachments')
                ->where('data_status', 1)
                ->where('monitoring_officer_id', $officerId)
                ->whereHas('inspection_transaction', function($query){
                    $query->where('inspection_status_id', 1)
                    ->where('approval_status_id', null);
                })
                ->where('id', $id)
                ->first();

        return $data;
    }
    
}
