<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOffice extends Model
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
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
     public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

}
