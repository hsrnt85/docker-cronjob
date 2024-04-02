<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialPermission extends Model
{
    use HasFactory;

    protected $table = 'special_permission';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_info()
    {
        return $this->belongsTo(UserInfo::class, 'users_info_id', 'id');
    }

}