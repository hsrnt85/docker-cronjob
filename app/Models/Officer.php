<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table = 'officer';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
    public function officer_category()
    {
        return $this->belongsTo(OfficerCategory::class, 'officer_category_id');
    }

    public function officer_group()
    {
        return $this->belongsTo(OfficerGroup::class, 'officer_group_id');
    }

    public static function getPegawaiPemantauanByDaerah($district_id)
    {
        $data = self::with('user')
                ->where('data_status', 1)
                ->where('district_id', $district_id)
                ->whereRaw('FIND_IN_SET(?, officer_category_id)', [4])
                ->get();

        return $data;
    }

    public static function getPegawaiPengesahByDaerah($district_id)
    {
        $data = self::with('user')
                ->where('data_status', 1)
                ->where('district_id', $district_id)
                ->whereRaw('FIND_IN_SET(?, officer_category_id)', [3])
                ->get();

        return $data;
    }

    public static function getPegawaiPemantauan()
    {
        $data = self::with('user')
                ->where('data_status', 1)
                ->whereRaw('FIND_IN_SET(?, officer_category_id)', [4])
                ->get();

        return $data;
    }

    public static function getPegawaiPemantauanByUserId($id)
    {
        $data = self::with('user')
                ->where(['data_status'=> 1, 'users_id' => $id])
                ->whereRaw('FIND_IN_SET(?, officer_category_id)', [4])
                ->get();

        return $data;
    }
}
