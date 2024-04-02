<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalJournalVotList extends Model
{
    use HasFactory;
    protected $table    = 'internal_journal_vot_list';
    public $timestamps  = false;

    public function income_account()
    {
        return $this->belongsTo(IncomeAccountCode::class, 'income_account_code_id');
    }

    public static function get_internal_journal_vot_list($id){

        $data = self::select('id','income_account_code_id', 'debit_amount', 'credit_amount')
                    ->where(['internal_journal_id' => $id, 'data_status' => 1])
                    ->orderBy('id','asc')->get();

        return $data;
    }

}
