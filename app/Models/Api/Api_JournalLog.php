<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_JournalLog extends Model
{
    use HasFactory;

    protected $table = 'journal_log';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function get_journal_log($id_jurnal){
       
        $logById = self::select('transaction_status_id','finance_officer_name','position_name',DB::raw('DATE_FORMAT(date, "%d%m%Y") AS tarikh'))
                ->where([
                    ['journal_id', '=', $id_jurnal],
                    ['data_status', '=', '1']
                ])
                ->whereIn('transaction_status_id', [2,3,4])
                ->orderBy('transaction_status_id')  
                ->orderBy('journal_id','DESC')  
                ->get()
                ->unique('transaction_status_id');

        return $logById;

    }
}
