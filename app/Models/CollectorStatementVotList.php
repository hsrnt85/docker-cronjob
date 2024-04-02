<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectorStatementVotList extends Model
{
    use HasFactory;
    protected $table = 'collector_statement_vot_list';
    public $timestamps = false;

    public function income_account()
    {
        return $this->belongsTo(IncomeAccountCode::class, 'income_account_code_id');
    }



    //EDIT PAGE ->> TABLE MAKLUMAT VOT HASIL
    public static function get_collector_statement_vot_list($collector_statement_id){

        $data = self::select('total_amount', 'income_account_code_id')
                    ->where(['collector_statement_id' => $collector_statement_id, 'data_status' => 1])
                    ->orderBy('income_account_code_id','asc')->get();

        return $data;
    }
}
