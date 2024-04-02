<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $table    = 'users_info';
    protected $primaryKey = 'id';
    public $timestamps  = false;
    protected $fillable = ['users_id', 'position_id', 'position_type_id', 'position_grade_id', 'position_grade_code_id', 'services_type_id', 'marital_status_id',
  'data_status', 'action_on'];

    public static function getLatestUserInfo()
    {
        $userInfo = UserInfo::select('id', 'users_id')->where(['users_id' => loginId(), 'data_status' => 1])->orderBy('id', 'DESC')->first();
        return $userInfo;
    }

    public static function getLatestUserInfoByUserId($user_id)
    {
        $userInfo = self::with('position', 'position_grade', 'position_grade_type', 'position_grade_code', 'position_type', 'marital_status', 'services_type')
            ->where(['users_id' => $user_id, 'data_status' => 1])
            ->first();

        return $userInfo;
    }

    public static function getUserInfoById($id)
    {
        $userInfo = self::with('position', 'position_grade', 'position_grade_type', 'position_grade_code', 'position_type', 'marital_status', 'services_type')
            ->where(['id' => $id, 'data_status' => 1])
            ->first();

        return $userInfo;
    }

  public function position()
  {
      return $this->belongsTo(Position::class, 'position_id');
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
  public function position_type()
  {
      return $this->belongsTo(PositionType::class, 'position_type_id');
  }

  public function marital_status()
  {
      return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
  }

  public function services_type()
  {
      return $this->belongsTo(ServicesType::class, 'services_type_id');
  }
}
