<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_CollectorStatementLog extends Model
{
    use HasFactory;
    protected $table = 'collector_statement_log';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function get_log_penyata_pemungut($id_penyata_pemungut){
       
        $logById = Self::select('transaction_status_id','finance_officer_name','position_name',
                            DB::raw('DATE_FORMAT(date, "%d%m%Y") AS tarikh'),
                            DB::raw('DATE_FORMAT(date, "%d/%m/%Y") AS tarikh_display'))
                ->where([
                    ['collector_statement_id', '=', $id_penyata_pemungut],
                    ['data_status', '=', '1']
                ])
                ->whereIn('transaction_status_id', [2,3,4])
                ->orderBy('transaction_status_id')  
                ->orderBy('collector_statement_id','DESC')  
                ->get()
                ->unique('transaction_status_id');

        return $logById;

    }

}
