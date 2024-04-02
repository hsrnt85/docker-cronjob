<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Tenant;
use App\Models\PaymentNoticeTransaction;
use App\Http\Resources\ListData;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AgencyPaymentNoticeController extends Controller
{
    public function listYear()
    {
        $yearAll = ListData::Year();

        return view(getFolderPath().'.listYear', compact('yearAll'));
    }

    public function listPaymentNotice(Request $request)
    {
        $year = $request->year;

        $paymentNoticeTransactionAll = PaymentNoticeTransaction::from('payment_notice_transaction as pnt')
            ->select('pnt.year', 'month.id AS month_id', 'month.name AS month_name', 'pnt.month')
            ->join('month','pnt.month','=','month.id')
            ->where(['month.data_status'=>1, 'pnt.data_status'=>1, 'pnt.flag_process'=>1])
            ->where('pnt.year', '=',  $year)
            ->where('pnt.month', '<=',  currentMonthInYear($year))
            ->groupBy('pnt.year','pnt.month')
            ->orderBy('pnt.year','ASC')->orderBy('pnt.month','ASC')
            ->get();
        
        if(checkPolicy("U") || checkPolicy("V")){
            return view(getFolderPath().'.listPaymentNotice', compact('year', 'paymentNoticeTransactionAll')); 
        }else{
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function listAgencyWithTenant(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $agencyAll = Organization::getAllAgencyWithTenant($year, $month);

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.listAgencyWithTenant', 
                compact('year', 'month', 'agencyAll')
            );
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function listTenant(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $oid = $request->oid;// organization id

        $organization = Organization::select('id','name')->where(['data_status'=> 1, 'id'=>$oid])->first();

        $carian_nama_penghuni = ($request->carian_nama_penghuni) ? $request->carian_nama_penghuni : "";
        
        $tenantPaymentNoticeAll = Tenant::from('tenants as t')
            ->select('tpn.*','t.name','t.quarters_id')
            ->join('tenants_payment_notice AS tpn', function ($q) use($year, $month){
                $q->on('tpn.tenants_id','=','t.id')
                ->where('tpn.data_status',1)
                ->whereYear('tpn.notice_date', '=', $year)
                ->whereMonth('tpn.notice_date', '=', $month)
                ->where('tpn.district_id', '=', districtId());
            })
            ->where('t.data_status', 1)
            ->where('tpn.organization_id', '=',  $oid)
            ->orderBy('t.name','DESC');
            
        if($carian_nama_penghuni) $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->where('t.name' , 'LIKE', '%'.$carian_nama_penghuni.'%');
        $tenantPaymentNoticeAll = $tenantPaymentNoticeAll->get();

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.listTenant', 
                compact('year','month','oid','carian_nama_penghuni','organization','tenantPaymentNoticeAll')
            ); 
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    
    public function listTenantPdf(Request $request, Tenant $tenant)
    {
        $year = $request->year;
        $month = $request->month;
        $organization_id = $request->oid;
        $organization = Organization::select('name')->where(['data_status'=> 1, 'id'=>$organization_id])->first();

        $tenantPaymentNoticeAll = Tenant::from('tenants as t')
        ->select('tpn.*','t.name','t.quarters_id')
        ->join('tenants_payment_notice AS tpn', function ($q) use($year, $month){
            $q->on('tpn.tenants_id','=','t.id')
            ->where('tpn.data_status',1)
            ->whereYear('tpn.notice_date', '=', $year)
            ->whereMonth('tpn.notice_date', '=', $month)
            ->where('tpn.district_id', '=', districtId());
        })
        ->where('t.data_status', 1)
        ->where('tpn.organization_id', '=',  $organization_id)
        ->orderBy('t.name','DESC')
        ->get();

        $dataReturn = compact('year','month','organization','tenantPaymentNoticeAll');
        //------------------------------------------------------------------------------------------------------
        $tempPdf = PDF::loadview(getFolderPath() . '.listTenantPdf', $dataReturn);
        $tempPdf->setPaper('A4', 'landscape');
        $tempPdf->output();
        // Get the total page count
        $totalPages = $tempPdf->getCanvas()->get_page_count();
        //------------------------------------------------------------------------------------------------------

        $pdf = PDF::loadview(getFolderPath().'.listTenantPdf', array_merge($dataReturn, compact('totalPages')));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Senarai-penyewa-'.date("dmY-His").'.pdf');

    }

}
