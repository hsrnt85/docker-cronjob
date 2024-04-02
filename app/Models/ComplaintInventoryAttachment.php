<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintInventoryAttachment extends Model
{
    use HasFactory;
    protected $table    = 'complaint_inventory_attachment';
    protected $primaryKey = 'id';
    protected $fillable = ['path_document'];

    public $timestamps  = false;

    public static function getInventoryAttachment($id)
    {
        $data = self::where(['data_status' => 1, 'complaint_inventory_id' => $id])->get();

        return $data;
    }
}
