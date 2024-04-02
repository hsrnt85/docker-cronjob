<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Quarters extends Model
{
    use HasFactory;

    protected $table    = 'quarters';
    public $timestamps  = false;

    public function category()
    {
        return $this->belongsTo(Api_QuartersCategory::class, 'quarters_cat_id')->select('id', 'district_id', 'name', 'description', 'data_status');
    }

    public function inventories()
    {
        return $this->belongsToMany(Api_Inventory::class, 'quarters_inventory', 'q_id', 'i_id')->withPivot('id','quantity', 'm_inventory_id', 'data_status', 'action_by', 'action_on', 'delete_by', 'delete_on');
    }

    public function application_quarters_categories()
    {
        return $this->hasMany(Api_ApplicationQuartersCategory::class, 'quarters_id');
    }

    public function quarters_condition()
    {
        return $this->belongsTo(Api_QuartersCondition::class, 'quarters_condition_id');
    }

    public static function getAllAddress($category_id)
    {
        $data = Api_Quarters::select('address_1')
                    ->where('data_status', 1)
                    ->where('quarters_cat_id', $category_id )
                    ->whereNotNull('unit_no')
                    ->groupBy('address_1')
                    ->get();
        return $data;
    }

    public static function getAvailableAddressByCategory($category_id)
    {
        $data = Api_Quarters::select('address_1')
                    ->whereDoesntHave('application_quarters_categories')
                    ->whereNotNull('unit_no')
                    ->where('quarters_cat_id', $category_id )
                    ->where('data_status', 1)
                    ->groupBy('address_1')
                    ->get();
        return $data;
    }

    public static function getAvailableUnitByAddr($address)
    {
        $data = Api_Quarters::whereDoesntHave('application_quarters_categories')
                        ->whereNotNull('unit_no')
                        ->where('address_1', $address)
                        ->where('quarters_condition_id', 1)
                        ->where('data_status', 1)
                        ->get();
        return $data;
    }

    public static function getUserProfileKuartersInfo($quarters_id)
    {
        $data = self::whereNotNull('unit_no')
                    ->where('id', $quarters_id)
                    ->where('data_status', 1)
                    ->first();
        return $data;
    }

}
