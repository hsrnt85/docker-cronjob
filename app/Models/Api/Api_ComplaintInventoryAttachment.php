<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintInventoryAttachment extends Model
{
    use HasFactory;

    protected $table    = 'complaint_inventory_attachment';
    public $timestamps  = false;

    public static function getInventoryAttachmentbyId($complaint_inv_id, $returnType = 'get')
    {
        $data = self::select('complaint_inventory_attachment.id', 'complaint_inventory_attachment.complaint_inventory_id', 'complaint_inventory_attachment.path_document', 'complaint_inventory_attachment.data_status')
                ->leftJoin('complaint_inventory', 'complaint_inventory.id', '=', 'complaint_inventory_attachment.complaint_inventory_id')
                ->where('complaint_inventory.data_status', 1)
                ->where('complaint_inventory_attachment.data_status', 1)
                ->where('complaint_inventory_attachment.complaint_inventory_id', $complaint_inv_id)
                ->$returnType();

        return $data;
    }

}
