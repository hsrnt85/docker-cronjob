<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsPaymentNoticeVotList extends Model
{
    use HasFactory;
    protected $table    = 'tenants_payment_notice_vot_list';
    protected $primaryKey = 'id';

    public $timestamps  = false;

    public function income_account()
    {
        return $this->belongsTo(IncomeAccountCode::class, 'income_account_code_id');
    }

    public static function get_tenants_payment_notice_vot_list($id){

        $data = self::select('id','income_account_code_id', 'debit_amount', 'credit_amount')
                    ->where('internal_journal_id', $id)
                    ->whereNot('data_status', 0)
                    ->orderBy('id','asc')->get();

        return $data;
    }
}
