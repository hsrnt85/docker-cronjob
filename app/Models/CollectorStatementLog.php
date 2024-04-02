<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectorStatementLog extends Model
{
    use HasFactory;
    protected $table = 'collector_statement_log';
    public $timestamps = false;

    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
    }

    public static function current_collector_statement_log($id){

        $data = self::select('remarks', 'transaction_status_id', 'data_status')->where('collector_statement_id' , $id)->whereIn('data_status' , [1,2])->orderBy('id', 'desc')->first();

        return $data;
    }

    public static function get_collector_statement_log($id){

        $data = self::select('id','collector_statement_id', 'transaction_status_id')->where(['collector_statement_id' => $id, 'data_status' => 1])->get();

        return $data;
    }

    public static function get_collector_statement_log_kuiri($id){

        $data = self::select('transaction_status_id', 'date', 'finance_officer_name', 'remarks', 'log_status')->where(['collector_statement_id' => $id, 'transaction_status_id' => 5 ,'data_status' => 1])->get();

        return $data;
    }

    //CETAK
    public static function get_collector_statement_log_by_status($id , $status){

        $data = self::select('finance_officer_name', 'position_name', 'action_on')
                    ->where(['collector_statement_id' => $id, 'transaction_status_id' => $status, 'data_status' => 1])->orderBy('id', 'desc')->first();

        return $data;
    }
}
