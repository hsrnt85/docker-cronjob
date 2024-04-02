<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_Tenant;
use App\Models\Api\Api_TenantsPaymentNotice;
use App\Models\Api\Api_PaymentNoticeSchedule;
use Illuminate\Http\Request;

class Api_PaymentNoticeController extends Controller
{
    public function getPaymentNotice(Request $request)
    {
        $tenants = Api_Tenant::checkTenant();

        $currentPaymentNotice = Api_TenantsPaymentNotice::where('data_status',1)
        ->where('tenants_id', '=', $tenants ->id)
        ->orderBy('notice_date','DESC')
        ->first();

        $paymentNoticeSchedule = null;
        $datePayBefore = null;
        $year = null;
        $month = null;
        $notice_date = ($currentPaymentNotice) ? $currentPaymentNotice->notice_date : "";

        $paymentNoticeSchedule = Api_PaymentNoticeSchedule::select('payment_notice_date')->where('data_status',1)->where('payment_notice_date','>',$notice_date)->first();
        $datePayBefore = $paymentNoticeSchedule->payment_notice_date ?? "";
        $year = getYearFromDate($notice_date);
        $month = getMonthFromDate($notice_date);

        // $paymentNoticeSchedule = Api_PaymentNoticeSchedule::select('payment_notice_date')->where('data_status',1)->orderBy('id','desc')->first();

        $payment_notice_id = $currentPaymentNotice->id ?? 0;
        $payment_status = $currentPaymentNotice->payment_status ?? 0;

        //SEWA  -----------------------------------------------------------------------------------------------------
        $rental_amount = $currentPaymentNotice->rental_amount ?? 0;
        $outstanding_rental_amount = $currentPaymentNotice->outstanding_rental_amount ?? 0;
        $total_rental = $currentPaymentNotice->total_rental ?? 0;

        $arrRental = ['rental_amount' => $rental_amount, 'outstanding_rental_amount' => $outstanding_rental_amount, 'total_rental' => $total_rental];

        //DENDA -----------------------------------------------------------------------------------------------------
        $damage_penalty_amount = $currentPaymentNotice->damage_penalty_amount ?? 0;
        $outstanding_damage_penalty_amount = $currentPaymentNotice->outstanding_damage_penalty_amount ?? 0;
        $total_damage_penalty = $currentPaymentNotice->total_damage_penalty ?? 0;

        $blacklist_penalty_amount = $currentPaymentNotice->blacklist_penalty_amount ?? 0;
        $outstanding_blacklist_penalty_amount = $currentPaymentNotice->outstanding_blacklist_penalty_amount ?? 0;
        $total_blacklist_penalty = $currentPaymentNotice->total_blacklist_penalty ?? 0;

        $penalty_amount = $damage_penalty_amount + $blacklist_penalty_amount;
        $outstanding_penalty_amount = $outstanding_damage_penalty_amount + $outstanding_blacklist_penalty_amount;
        $total_penalty = $total_damage_penalty + $total_blacklist_penalty;

        $arrPenalty = ['penalty_amount' => $penalty_amount, 'outstanding_penalty_amount' => $outstanding_penalty_amount, 'total_penalty' => $total_penalty];

        $maintenance_fee_amount = $currentPaymentNotice->maintenance_fee_amount ?? 0;
        $outstanding_maintenance_fee_amount = $currentPaymentNotice->outstanding_maintenance_fee_amount ?? 0;
        $total_maintenance_fee = $currentPaymentNotice->total_maintenance_fee ?? 0;
        $arrMaintenance = ['maintenance_fee_amount' => $maintenance_fee_amount, 'outstanding_maintenance_fee_amount' => $outstanding_maintenance_fee_amount, 'total_maintenance_fee' => $total_maintenance_fee];

        $total_current = $rental_amount + $penalty_amount + $maintenance_fee_amount;
        $total_outstanding = $outstanding_rental_amount + $outstanding_penalty_amount + $outstanding_maintenance_fee_amount;
        $total_payment = $currentPaymentNotice->total_payment ?? 0;

        //Adjustment-----------------------------------------------------------------------------------------------------
        $amount_adjustment = $currentPaymentNotice->adjustment_amount ?? '0';
        $amount_after_adjustment = $currentPaymentNotice->total_amount_after_adjustment ?? '0';

        $total_payment = ($payment_status == 2) ? "0.00" : numberFormatComma($amount_after_adjustment);

        return response()->json([
            // 'all' => $currentPaymentNotice,
            'sewa' => $arrRental,
            'yuranPenyelenggaraan' => $arrMaintenance,
            'denda' => $arrPenalty,
            'pelarasan' => $amount_adjustment,
            'jumlahAkhir' => $amount_after_adjustment,
            'jumlahPerluDibayar' => $total_payment

        ], 200);

    }
}
