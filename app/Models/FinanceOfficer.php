<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceOfficer extends Model
{
    use HasFactory;

    protected $table = 'finance_officer';
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

    public function finance_category()
    {
        return $this->belongsTo(FinanceOfficerCategory::class, 'finance_officer_category_id');
    }

    public static function current_fin_officer_by_category($finance_officer_cat_id, $user_id)
    {

        $list = FinanceOfficer::join('users','users.id','=','finance_officer.users_id')
                    ->select('finance_officer.id', 'finance_officer.users_id', 'finance_officer.finance_officer_category_id', 'users.position_id','users.name')
                    ->where('finance_officer.data_status',1)
                    ->where('finance_officer.users_id', $user_id);

        if($finance_officer_cat_id>0)
        {
            $list = $list->whereRaw('FIND_IN_SET(?, finance_officer_category_id)', $finance_officer_cat_id);
        }
        $list = $list->first();

        return $list;
    }

    public static function current_fin_officer($user_id)
    {
        $data = FinanceOfficer::join('users','users.id','=','finance_officer.users_id')
                    ->select('finance_officer.id', 'finance_officer.users_id', 'finance_officer.finance_officer_category_id','users.name', 'users.position_id')
                    ->where('finance_officer.data_status',1)
                    ->where('finance_officer.users_id', $user_id)->first();

        return $data;
    }

    public static function finance_officer_by_id($id)
    {
        $data = FinanceOfficer::select('id', 'users_id')->where(['id' => $id, 'data_status' => 1])->first();

        return $data;
    }


}
