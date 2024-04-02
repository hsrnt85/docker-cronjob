<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Month;
use App\Models\PaymentNoticeSchedule;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentNoticeScheduleRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentNoticeScheduleController extends Controller
{
    public function listYear()
    {
        $yearAll = Year::where('data_status', 1)->orderBy('year','DESC')->get();

        return view(getFolderPath().'.listYear',
            [
                'yearAll' => $yearAll,
            ]
        );
    }

    public function edit(Request $request)
    {
        $year = $request->year;

        $paymentNoticeSchedule = Month::select('month.id AS month_id','month.name AS month_name','payment_notice_schedule.month','payment_notice_schedule.id AS payment_notice_schedule_id',
                DB::raw('DATE_FORMAT(payment_notice_schedule.payment_notice_date, "%d/%m/%Y") AS payment_notice_date'))
                ->leftJoin('payment_notice_schedule', function($join) use($year) {
                    $join->on('payment_notice_schedule.month','=','month.id');
                    $join->where('payment_notice_schedule.year', '=', $year);
                })
                ->where('month.data_status',1)
                ->get();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'year' => $year,
                'paymentNoticeSchedule' => $paymentNoticeSchedule,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(PaymentNoticeScheduleRequest $request)
    {
        $year = $request->year;
        $msgArr = [];

        for($month=1;$month<=12;$month++){

            if(isset($request['payment_notice_date_'.$month])) {

                $payment_notice_date = convertDatepickerDb($request['payment_notice_date_'.$month]);
                $month = getMonthFromDate($payment_notice_date);

                $variableName = PaymentNoticeSchedule::where(['year' => $year, 'month' => $month])->first();

                $data_before_json = "";
                $data_after_json = "";
                if ($variableName) {
                    $data_before = $variableName->getRawOriginal();
                    $data_before['item'] = $variableName->toArray();
                    $data_before_json = json_encode($data_before);
                }
                
                $process = PaymentNoticeSchedule::updateOrInsert(
                    //values used to filter a record
                    ['year'=>$year, 'month'=>$month],
                    //values to be updated or inserted
                    ['year'=>$year, 'month'=>$month, 'payment_notice_date'=>$payment_notice_date, 'action_by'=>loginId(), 'action_on'=>currentDate()]

                );

                $variableName = PaymentNoticeSchedule::where(['year' => $year, 'month' => $month])->first();

                if ($variableName) {
                    $data_after = $variableName;
                    $data_after['item'] = $variableName->toArray();
                    $data_after_json = json_encode($data_after);

                }

                if (!$process) {
                    $msgArr[$month] = "process error ".$month;
                }
                else {
                    setUserActivity("U", $variableName->name, $data_before_json, $data_after_json);
                }

            }
        }

        if (empty($msgArr)) {
            return redirect()->route('paymentNoticeSchedule.listYear')->with('success', 'Jadual notis bayaran bagi tahun '.$year.' berjaya dikemaskini!');
        }
        else{
            return redirect()->route('paymentNoticeSchedule.edit', ['id'=>$request->id, 'year'=>$year])->with('error', 'Jadual notis bayaran bagi tahun '.$year.' tidak berjaya dikemaskini!');
        }

    }

    public function view(Request $request)
    {
        $year = $request->year;

        $paymentNoticeSchedule = Month::select('month.id AS month_id','month.name AS month_name','payment_notice_schedule.id AS payment_notice_schedule_id',
                DB::raw('DATE_FORMAT(payment_notice_schedule.payment_notice_date, "%d/%m/%Y") AS payment_notice_date'))
                ->leftJoin('payment_notice_schedule', function($join) use($year) {
                    $join->on('payment_notice_schedule.month','=','month.id');
                    $join->where('payment_notice_schedule.year', '=', $year);
                })
                ->where('month.data_status',1)
                ->get();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'year' => $year,
                'paymentNoticeSchedule' => $paymentNoticeSchedule
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

}
