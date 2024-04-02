<?php

namespace App\Models\Api;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Api_User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function position()
    {
        return $this->belongsTo(Api_Position::class, 'position_id');
    }

    public function position_type()
    {
        return $this->belongsTo(Api_PositionType::class, 'position_type_id');
    }

    public function position_grade()
    {
        return $this->belongsTo(Api_PositionGrade::class, 'position_grade_id');
    }

    public function position_grade_type()
    {
        return $this->belongsTo(Api_PositionGradeType::class, 'position_type_id');
    }

    public function position_grade_code()
    {
        return $this->belongsTo(Api_PositionGradeType::class, 'position_grade_code_id');
    }

    public function user_address_office()
    {
        return $this->belongsTo(Api_UserOffice::class, 'users_id');
    }


    public static function getUserForView($ic)
    {
        $data = Api_User::select('users.name', 'users.new_ic', 'users.email', 'users.phone_no_hp', 'users.phone_no_hp','users.id as users_id', 'users.position_id', 'users.position_grade_code_id', 'users.position_grade_id','organization.name as organization_name')
                ->with('position:id,position_name')
                ->with('position_grade:id,grade_no')
                ->with('position_grade_code:id,grade_type')
                ->leftJoin('users_address_office', 'users_address_office.users_id', '=', 'users.id')
                ->leftJoin('organization', 'organization.id', '=', 'users_address_office.organization_id')
                ->where([
                    'users.new_ic' => $ic,
                    'users.data_status' => 1
                ])
                ->first();

        $filteredData['id']             = $data->users_id;
        $filteredData['name']           = $data->name;
        $filteredData['new_ic']         = $data->new_ic;
        $filteredData['email']          = $data->email;
        $filteredData['phone_no_hp']    = $data->phone_no_hp;
        $filteredData['phone_no_home']  = $data->phone_no_home;
        $filteredData['position_name']  = $data->position->position_name;
        $filteredData['grade_no']       = $data->position_grade->grade_no;
        $filteredData['grade_type']     = $data->position_grade_code?->grade_type;
        $filteredData['organization']   = $data->organization_name;

        return $filteredData;
    }

    public static function getUserProfileInfo($user_id)
    {
        $data = self::where(['id'=> $user_id, 'data_status' => 1])->first();

        return $data;
    }

    public function officer()
    {
        return $this->hasOne(Api_Officer::class, 'users_id', 'id')->where('data_status', 1)->first();
    }
    public function tenants()
    {
        return $this->hasOne(Api_Tenant::class, 'user_id')->latestOfMany();
    }
}
