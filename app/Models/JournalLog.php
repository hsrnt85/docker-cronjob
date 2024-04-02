<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalLog extends Model
{
    use HasFactory;
    protected $table = 'journal_log';
    public $timestamps = false;

    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
    }

    public static function get_journal_log($id){

        $data = self::select('id','journal_id', 'transaction_status_id')->where(['journal_id' => $id, 'data_status' => 1])->get();

        return $data;
    }

    public static function get_journal_kuiri($id){

        $data = self::select('transaction_status_id', 'date', 'finance_officer_name','finance_officer_category_name' ,'remarks')
                    ->where(['journal_id' => $id, 'transaction_status_id' => 5 ,'data_status' => 1])->get();

        return $data;
    }

    public static function curent_journal_log($id){

        $data = self::select('remarks', 'transaction_status_id')->where(['journal_id' => $id, 'data_status' => 1])->orderBy('id', 'desc')->first();

        return $data;
    }

    //CETAK
    public static function get_journal_log_by_status($id , $status){

        $data = self::select('finance_officer_name', 'position_name', 'action_on')
                    ->where(['journal_id' => $id, 'transaction_status_id' => $status, 'data_status' => 1])->orderBy('id', 'desc')->first();

        return $data;
    }

}
