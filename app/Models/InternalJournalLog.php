<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalJournalLog extends Model
{
    use HasFactory;
    protected $table    = 'internal_journal_log';
    public $timestamps  = false;

    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
    }

    public static function get_internal_journal_log($id){

        $data = self::select('id','internal_journal_id', 'transaction_status_id')->where(['internal_journal_id' => $id, 'data_status' => 1])->get();

        return $data;
    }

    public static function get_internal_journal_kuiri($id){

        $data = self::select('transaction_status_id', 'date', 'finance_officer_name','finance_officer_category_name' ,'remarks')
                    ->where(['internal_journal_id' => $id, 'transaction_status_id' => 5 ,'data_status' => 1])->get();

        return $data;
    }

    public static function current_internal_journal_log($id){

        $data = self::select('remarks', 'transaction_status_id')->where(['internal_journal_id' => $id, 'data_status' => 1])->orderBy('id', 'desc')->first();

        return $data;
    }

}
