<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentNoticeTransaction extends Model
{
    use HasFactory;
    protected $table = 'payment_notice_transaction';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $dates = ['notice_date'];

    public static function get_year_month($payment_category_id)
    {
        $data = self::from('payment_notice_transaction AS pnt')->leftJoin('reconciliation_transaction AS rt', function ($q) use($payment_category_id) {
            $q->on('rt.year', '=', 'pnt.year')
                ->on('rt.month', '=', 'pnt.month')
                ->where('rt.payment_category_id', $payment_category_id)
                ->where('rt.data_status', 1);
        })
        ->select('pnt.year', 'pnt.month','rt.flag_process')->where('pnt.data_status', 1)->groupBy('pnt.year')->groupBy('pnt.month')->get();

        return $data;
    }
    public static function get_year()
    {
        $data = self::select('year')->groupBy('year')->get();
        return $data;
    }

    public static function get_month()
    {
        $data = self::from('payment_notice_transaction as pnt')->join('month','pnt.month','=','month.id')->select('month.name','month.id')->groupBy('month')->get();
        return $data;
    }

    
}
