<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_MaintenanceTransactionAttachment extends Model
{
    use HasFactory;
    protected $table = 'maintenance_transaction_attachment';
    public $timestamps  = false;

    public static function getMaintenanceAttachmentbyId($id)
    {
        $data = self::select('maintenance_transaction.id', 'maintenance_transaction.maintenance_date', 'maintenance_transaction.monitoring_officer_id', 'maintenance_transaction.maintenance_status_id', 'maintenance_transaction.remarks', 'maintenance_transaction_attachment.maintenance_transaction_id', 'maintenance_transaction_attachment.path_document')
                ->leftjoin('maintenance_transaction', 'maintenance_transaction.id', '=', 'maintenance_transaction_attachment.maintenance_transaction_id')
                ->where('maintenance_transaction.data_status', 1)
                ->where('maintenance_transaction_attachment.data_status', 1)
                ->where('maintenance_transaction_attachment.maintenance_transaction_id', $id)
                ->get();

        return $data;
    }

}
