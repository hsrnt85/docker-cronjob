<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api\Api_Tenant;
use App\Models\Api\Api_TenantsPaymentTransaction;
use App\Models\Api\Api_TenantsPaymentNotice;


class Api_PaymentRecordController extends Controller
{
    public function getPaymentRecord(Request $request)
    {
        $tenants = Api_Tenant::checkTenant();
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        //DATA BAYARAN - JOHORPAY
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        $dataTpt = Api_TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->join('tenants_payment_notice as tpn', 'tpn.id', '=', 'tpt.tenants_payment_notice_id')
            ->select('tpt.payment_date', 'tpt.payment_receipt_no', 'tpt.payment_description', 'tpn.payment_notice_no', 'tpt.total_payment')
            ->where(['tpt.data_status' => 1, 'tpn.data_status' => 1, 'tpn.tenants_id' => $tenants->id , 'tpn.payment_status' => 2, 'tpt.payment_category_id'=>3 ]);

        //------------------------------------------------------------------------------------------------------------------------------------------------------
        //DATA BAYARAN - IGFMAS, ISPEKS
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        $dataTptRecon = Api_TenantsPaymentNotice::from('tenants_payment_notice as tpn')
            ->join('tenants_payment_transaction AS tpt', 'tpt.id', '=', 'tpn.tenants_payment_transaction_id')
            ->select('payment_date', 'payment_receipt_no','payment_description AS description', 'tpn.payment_notice_no')
            ->selectRaw('tpn.total_amount_after_adjustment AS total_payment')
            ->where('tpt.data_status', 1)->where('payment_status',2)
            ->where(['tpt.data_status' => 1, 'tpn.data_status' => 1, 'tpn.no_ic' =>  auth('sanctum')->user()?->new_ic , 'tpn.payment_status' => 2 ])
            ->whereIn('tpt.payment_category_id', [1,2])
            ->where('tenants_payment_transaction_id','>',0);
          
        $paymentRecordAll = $dataTpt->unionAll($dataTptRecon)->orderBy('payment_date') ->get();

        return response()->json([

            'paymentRecordAll' => $paymentRecordAll,

        ], 200);

    }
}
