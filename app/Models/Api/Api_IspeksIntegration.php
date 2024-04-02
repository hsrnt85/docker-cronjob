<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_IspeksIntegration extends Model
{
    use HasFactory;

    protected $table = 'ispeks_integration';
    protected $primaryKey = 'id';
    protected $dates= ['action_on'];

    public $timestamps = false;
    
    public static function get_ispeks_integration_pp($id_ispeks_integration, $payment_method_id){ 

        $data = self::from('ispeks_integration as ispeks')->join('collector_statement as cs', 'cs.ispeks_integration_id', '=', 'ispeks.id')
        ->select('ispeks.file_name','cs.id as collector_statement_id','cs.collector_statement_no',
        'cs.payment_method_id', 'ispeks.action_on',
        // 'cs.jenis_bayaran_ms AS jenis_bayaran',
        'cs.collection_amount',
            DB::raw('FORMAT('.'cs.collection_amount, 2) AS jumlah_kutipan_display'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date, "%d/%m/%Y") AS tarikh_penyata_pemungut_display'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date_from, "%d/%m/%Y") AS tarikh_pungutan_dari_display'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date_to, "%d/%m/%Y") AS tarikh_pungutan_hingga_display'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date, "%d%m%Y") AS tarikh_penyata_pemungut'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date_from, "%d%m%Y") AS tarikh_pungutan_dari'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date_to, "%d%m%Y") AS tarikh_pungutan_hingga'),
            DB::raw('DATE_FORMAT('.'cs.bank_slip_date, "%d%m%Y") AS tarikh_slip_bank'),
            DB::raw('DATE_FORMAT('.'cs.collector_statement_date, "%Y") AS tahun_kewangan')
        )

        ->where(['cs.data_status'=> 1, 'ispeks.data_status' => 1, 'cs.payment_method_id' => $payment_method_id, 'ispeks.id' => $id_ispeks_integration])->groupBy('cs.collector_statement_no')->get();
        return $data;
    }

    public static function get_ispeks_integration_jurnal($id_data_ispeks_integration_arr){

        $data = Self::join('journal', 'journal.id', '=', 'ispeks_integration.journal_id')
            ->join('journal_vot_list', 'journal_vot_list.journal_id', '=', 'journal.id')
            ->select('ispeks_integration.file_name','journal.journal_no', 'ispeks_integration.action_on',
                DB::raw('SUM('.'journal_vot_list.debit_amount) AS amaun_debit'), DB::raw('SUM('.'journal_vot_list.credit_amount) AS amaun_kredit')
            )
            ->whereIn('ispeks_integration.id', $id_data_ispeks_integration_arr)
            ->groupBy('journal.journal_no')
            ->get();

        return $data;

    }

    public static function get_data_ispeks_integration($id_data_ispeks_integration){

        $data = Self::where([
                ['id', '=', $id_data_ispeks_integration],
                ['data_status', '=', '1']
            ])->first();

            return $data;

    }


    public static function get_ispeks_integration_list(){

        $data = Self::where('data_status', '1')->get();
    
        return $data;

    }

}
