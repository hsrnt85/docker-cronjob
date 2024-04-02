<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintOthers extends Model
{
    use HasFactory;

    protected $table = 'complaint_others';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function scopeActive($query)
    {
        return $query->where('complaint_others.data_status', 1);
    }

    public static function getComplaintOthersByComplaintId($complaint_id, $returnType='get')
    {

        $data = self::select('complaint_others.id', 'complaint_others.complaint_id','complaint_others.description','complaint_others.flag_action','complaint_others.is_maintenance','complaint_others.data_status')
        ->active()
        ->where('complaint_others.complaint_id', $complaint_id)
        ->$returnType();

        return $data;
    }

}

