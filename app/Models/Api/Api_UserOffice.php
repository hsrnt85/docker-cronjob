<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_UserOffice extends Model
{
    use HasFactory;
    protected $table    = 'users_address_office';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    protected $fillable = [
        'users_id',
        'organization_id',
        'department_id',
        'address_1',
        'address_2',
        'address_3',
        'district_id',
        'postcode',
        'phone_no_office',
        'latitude',
        'longitude',
        'data_status',
        'action_on'
    ];


    public function organization()
    {
        return $this->belongsTo(Api_Organization::class, 'organization_id');
    }

    public function district()
    {
        return $this->belongsTo(Api_District::class, 'district_id');
    }
}
