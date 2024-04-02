<?php

namespace App\Http\Controllers;
use App\Models\TenantsPaymentTransaction;
use App\Models\Year;
use App\Models\Month;
use App\Models\PaymentCategory;
use Illuminate\Http\Request;

class PaymentRecordController extends Controller
{
    public function index(Request $request)
    {
        $district_id = (!is_all_district()) ? districtId() : null;

        $year = Year::get_year();
        $month = Month::get_month();
        $paymentCategory = PaymentCategory::where('data_status', 1)->get();

        $carian_tahun = $request->input('carian_tahun');
        $carian_bulan = $request->input('carian_bulan');
        $carian_bayaran = $request->input('carian_bayaran');
        $search_no_ic = str_replace('-', '', $request->carian_no_ic);

        $recordList = TenantsPaymentTransaction::from('tenants_payment_transaction as tpt')
            ->leftJoin('tenants_payment_notice as tpn','tpn.id','=','tpt.tenants_payment_notice_id')
            ->leftJoin('payment_category as pc','tpt.payment_category_id','=','pc.id')
            ->select('tpn.name', 'tpn.no_ic', 'tpn.payment_notice_no', 'tpt.payment_receipt_no','tpt.action_on AS payment_date' ,'tpt.payment_description','pc.payer_name','pc.payment_category','tpt.payment_category_id','tpt.total_payment')
            ->where(['tpt.data_status'=>1]);

            if($carian_tahun) $recordList = $recordList->whereYear('tpt.payment_date', $carian_tahun);
            if($carian_bulan) $recordList = $recordList->whereMonth('tpt.payment_date', $carian_bulan);
            if($search_no_ic) $recordList = $recordList -> where('tpn.no_ic', $search_no_ic);
            if($carian_bayaran) $recordList = $recordList -> where ('tpt.payment_category_id', $carian_bayaran);

        $recordList = $recordList->get();

        return view(getFolderPath() . '.index', compact('year', 'month', 'paymentCategory', 'carian_tahun', 'carian_bulan', 'carian_bayaran','recordList', 'search_no_ic'));

    }


}
