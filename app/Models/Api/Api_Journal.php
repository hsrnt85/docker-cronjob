<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_Journal extends Model
{
    use HasFactory;

    protected $table = 'journal';
    protected $primaryKey = 'id';

    public $timestamps = false;
    
    public static function get_passed_jurnal(){
       
        $senaraiJurnal = self::join('journal_vot_list as jvl', 'jvl.journal_id', '=', 'journal.id')
            ->join('collector_statement as cs', 'cs.id', '=', 'journal.collector_statement_id')
            ->select('journal.*','cs.collector_statement_no',
                DB::raw('DATE_FORMAT(journal.journal_date, "%d%m%Y") AS tarikh_jurnal'),
                DB::raw('DATE_FORMAT(cs.collector_statement_date, "%d%m%Y") AS tarikh_penyata_pemungut'),
                DB::raw('SUM(jvl.debit_amount) AS amaun_debit'), DB::raw('SUM(jvl.credit_amount) AS amaun_kredit'),
                DB::raw('DATE_FORMAT(journal.action_on, "%s") AS seconds')
            )
            ->where(['journal.data_status'=> 1, 'jvl.data_status'=> 1, 'journal.transaction_status_id' => 4])
            ->where('journal.ispeks_integration_id', 0)
            ->groupBy('journal_no')  
            ->orderBy('journal_no', 'DESC')  
            ->get();

        return $senaraiJurnal;

    }

}
