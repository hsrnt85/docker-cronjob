<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_JournalVotList extends Model
{
    use HasFactory;

    protected $table = 'journal_vot_list';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function get_jurnal_vot($id_jurnal){
             
        $data = Self::join('income_account_code as iac', 'journal_vot_list.income_account_code_id', '=', 'iac.id')
        ->select('iac.general_income_code','iac.ispeks_account_code',
            DB::raw('SUM('.'journal_vot_list.debit_amount) AS amaun_debit'),DB::raw('SUM('.'journal_vot_list.credit_amount) AS amaun_kredit'))
        ->where([
            ['journal_vot_list.journal_id', '=', $id_jurnal],
            ['journal_vot_list.data_status', '=', '1'],
            ['iac.data_status', '=', '1']
        ])
        ->groupby('iac.id')  
        ->get();

        return $data;
    
    }

}
