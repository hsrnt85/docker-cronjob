<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table    = 'users';
    protected $primaryKey = 'id';
    public $timestamps  = false;
    protected $fillable = ['new_ic', 'password', 'name', 'position_id', 'position_type_id', 'position_grade_id', 'position_grade_code_id', 'services_type_id', 'marital_status_id', 'roles_id', 'email',
     'phone_no_hp', 'phone_no_home', 'date_of_service', 'is_blacklist_application', 'is_hrmis', 'flag', 'data_status', 'action_on'];
    protected $dates = ['date_of_service','expected_date_of_retirement','date_of_retirement'];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function position_type()
    {
        return $this->belongsTo(PositionType::class, 'position_type_id', 'id');
    }

    public function position_grade()
    {
        return $this->belongsTo(PositionGrade::class, 'position_grade_id');
    }

    public function position_grade_type()
    {
        return $this->belongsTo(PositionGradeType::class, 'position_type_id');
    }

    public function position_grade_code()
    {
        return $this->belongsTo(PositionGradeType::class, 'position_grade_code_id');
    }

    public function marital_status()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function office()
    {
        return $this->hasOne(UserOffice::class, 'users_id', 'id');
        // return $this->hasMany(UserOffice::class, 'users_id', 'id');
        // return $this->belongsTo(UserOffice::class, 'users_id');
        // return $this->hasManyThrough(Organization::class, UserOffice::class, 'organization_id', 'users_id');
    }

    // User.php (assuming your model is named User)
    public function specialPermissions()
    {
        return $this->hasMany(SpecialPermission::class, 'user_id', 'id');
    }


    public function roles()
    {
        return $this->belongsTo(Roles::class, 'roles_id')->where('data_status', 1);
    }

    public function services_type()
    {
        return $this->belongsTo(ServiceType::class, 'services_type_id', 'id');
    }
    
    public function system()
    {
        return $this->belongsTo(System::class, 'flag');
    }

    public function addressAll()
    {
        return $this->hasMany(UserHouse::class, 'users_id', 'id');
    }

    public function current_address()
    {
        return $this->addressAll->sortBy('address_type')->last();
    }

    public function officer()
    {
        return $this->hasOne(Officer::class, 'users_id', 'id')->where('data_status', 1)->first();
    }

    public function active_status()
    {
        return $this->belongsTo(ActiveStatus::class, 'data_status')->where('data_status', 1);
    }

    public function spouse()
    {
        return $this->hasOne(UserSpouse::class, 'users_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(UserChild::class, 'users_id', 'id');
    }

    public function finance_officer()
    {
        return $this->hasOne(FinanceOfficer::class, 'users_id');
    }

    public static function getAllUsers()
    {
        return self::all();
    }
    
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function addressOffice()
    {
        return $this->hasMany(UserAddressOffice::class, 'users_id', 'id');
    }

    public function user_info()
    {
        return $this->hasMany(UserInfo::class, 'users_id', 'id');
    }

    public function latest_user_info()
    {
        return $this->hasOne(UserInfo::class, 'users_id', 'id')->where('data_status', 1)->latest('id');
    }

    public function latest_user_info_before($date)
    {
        return $this->user_info()
                    ->where('action_on', '<', $date)
                    ->orderBy('action_on', 'desc')
                    ->first();
    }

    public static function getUserForView($ic)
    {
        $data = User::select('users.name', 'users.new_ic', 'users.email', 'users.phone_no_hp', 'users.phone_no_hp','users.id as users_id', 'users.position_id', 'users.position_grade_code_id', 'users.position_grade_id','organization.name as organization_name')
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
}
