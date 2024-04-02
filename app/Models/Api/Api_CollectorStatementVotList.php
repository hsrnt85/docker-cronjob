<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_CollectorStatementVotList extends Model
{
    use HasFactory;
    protected $table = 'collector_statement_vot_list';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function get_penyata_pemungut_vot($id){
    
        $data = Self::join('income_account_code as iac', 'collector_statement_vot_list.income_account_code_id', '=', 'iac.id')
                ->select('iac.general_income_code','iac.ispeks_account_code',
                    DB::raw('SUM('.'collector_statement_vot_list.total_amount) AS amaun'))
                ->where([
                    ['collector_statement_vot_list.collector_statement_id', '=', $id],
                    ['collector_statement_vot_list.data_status', '=', '1'],
                    ['iac.data_status', '=', '1']
                ])
                ->groupBy('iac.id')  
                ->get();

        return $data;

    }

}
