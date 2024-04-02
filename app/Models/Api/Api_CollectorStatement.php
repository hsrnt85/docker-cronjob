<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api_CollectorStatement extends Model
{
    use HasFactory;
    protected $table = 'collector_statement';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public static function get_passed_collector_statement($payment_method_id=0){ // 4: LULUS 

        $data = self::from('collector_statement as cs')
                ->join('tenants_payment_transaction as tpt', 'cs.id', '=', 'tpt.collector_statement_id')
                ->select('cs.id','cs.collector_statement_no', 'cs.collector_statement_date', 'cs.collection_amount', 'cs.bank_slip_date', 'cs.bank_slip_no', 'tpt.online_payment_refno', 
                    DB::raw('DATE_FORMAT(cs.collector_statement_date, "%d/%m/%Y") AS tarikh_penyata_pemungut_display'),
                    DB::raw('DATE_FORMAT(cs.collector_statement_date_from, "%d/%m/%Y") AS tarikh_pungutan_dari_display'),
                    DB::raw('DATE_FORMAT(cs.collector_statement_date_to, "%d/%m/%Y") AS tarikh_pungutan_hingga_display'),
                    DB::raw('DATE_FORMAT(cs.collector_statement_date, "%d%m%Y") AS tarikh_penyata_pemungut'),
                    DB::raw('DATE_FORMAT(cs.collector_statement_date_from, "%d%m%Y") AS tarikh_pungutan_dari'),
                    DB::raw('DATE_FORMAT(cs.collector_statement_date_to, "%d%m%Y") AS tarikh_pungutan_hingga'),
                    DB::raw('DATE_FORMAT(cs.bank_slip_date, "%d%m%Y") AS tarikh_slip_bank'),
                    DB::raw('DATE_FORMAT(cs.collector_statement_date, "%Y") AS tahun_kewangan'),
                )
                ->where(['cs.transaction_status_id' => 4,'cs.data_status' =>  1, 'tpt.data_status' =>  1]);
                
        if($payment_method_id>0) $data = $data->where('cs.payment_method_id', $payment_method_id);

        $data = $data->where('cs.ispeks_integration_id', 0)->orWhereNull('cs.ispeks_integration_id');

        $data = $data->groupBy('cs.collector_statement_no');

        $data = $data->get();

        return $data;
    }

}
