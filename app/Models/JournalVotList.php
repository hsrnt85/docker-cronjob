<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JournalVotList extends Model
{
    use HasFactory;
    protected $table = 'journal_vot_list';
    public $timestamps = false;


    public function income_account()
    {
        return $this->belongsTo(IncomeAccountCode::class, 'income_account_code_id');
    }

    public static function get_journal_vot_list($id){

        $data = self::select('id','income_account_code_id', 'debit_amount', 'credit_amount')
                    ->where(['journal_id' => $id, 'data_status' => 1])
                    ->orderBy('id','asc')->get();

        return $data;
    }

    public static function total_debit_and_credit($id){

        $data = self::select('id', DB::raw('SUM(credit_amount) as total_credit'), DB::raw('SUM(debit_amount) as total_debit'))->where(['journal_id' => $id, 'data_status' => 1])->first();

        return $data;
    }




}
