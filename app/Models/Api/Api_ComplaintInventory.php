<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_ComplaintInventory extends Model
{
    use HasFactory;

    protected $table = 'complaint_inventory';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Api_Inventory::class, 'inventory_id')->where('inventory.data_status' , 1);
    }

    public function complaint_inventory_attachment()
    {
        return $this->belongsTo(Api_ComplaintInventoryAttachment::class, 'complaint_inventory_id')->where('complaint_inventory_attachment.data_status' , 1);
    }

    public function scopeActive($query)
    {
        return $query->where('complaint_inventory.data_status', 1);
    }

    public static function getInventoryByComplaintId($complaint_id, $returnType='get')
     {
        $data =  self::select('complaint_inventory.id','complaint_inventory.complaint_id','complaint_inventory.inventory_id','complaint_inventory.description','complaint_inventory.flag_action','complaint_inventory.is_maintenance','complaint_inventory.data_status')
                ->with('inventory:id,quarters_category_id,name,price,data_status')
                ->active()
                ->where('complaint_id', $complaint_id)
                ->$returnType();

        return $data;
    }

    public static function inventoryByComplaintId($complaint_id, $returnType='get')
    {

    }

}
