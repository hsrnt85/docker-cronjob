<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\BlacklistPenalty;
use App\Models\BlacklistPenaltyRate;
use App\Models\Complaint;
use App\Models\ComplaintAppointment;
use App\Models\CronjobLogs;
use App\Models\CronjobType;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Year;
use App\Notifications\AppointmentLateApprovalNotification;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class CronJobController extends Controller
{
    public $process_method;

    public function __construct(){
        $this->process_method = 1;
    }

    // LIST CRON JOB
    public function index()
    {
        $listCronJob = CronjobLogs::where('data_status', 1)->orderBy('action_on')->get();

        return view(getFolderPath().'.index',
        [
            'list' => $listCronJob
        ]);
    }

    // LIST CRON JOB
    public function create()
    {
        $cronJobType = CronjobType::where('data_status', 1)->get();

        return view(getFolderPath().'.create',
        [
            'cronJobType' => $cronJobType
        ]);
    }

    // PROSES CRON JOB - MANUAL
    public function store(Request $request)
    {
        $cronjob_type = $request->cronjob_type;

        try {

            if($cronjob_type == 1) CronJobController::cancelApplicationOffer();
            else if($cronjob_type == 2) CronJobController::cancelComplaintAppointment();
            else if($cronjob_type == 3) CronJobController::checkTenantBlacklistPenalty();
            else if($cronjob_type == 4) CronJobController::checkHrmis();
            else if($cronjob_type == 5) CronJobController::addYear();

        } catch (\Exception $e) {

            // something went wrong
            return redirect()->route('cronJob.create')->with('error', 'Proses cron job tidak berjaya !' . ' ' . $e->getMessage());
        }

        return redirect()->route('cronJob.index')->with('success', 'Proses cron job berjaya ! ');
    }

    //DELETE CRON JOB LOG
    public function destroy(Request $request)
    {

        DB::beginTransaction();

        try {

            $cronjob_log = CronjobLogs::select('id','cronjob_type_id')->where(['data_status' => 1, 'id' => $request->id])->first();

            setUserActivity("D", $cronjob_log->cronjobType?->cronjob_type);

            $cronjob_log->data_status  = 0;
            $cronjob_log->delete_by    = loginId();
            $cronjob_log->delete_on    = currentDate();

            $cronjob_log->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('cronJob.index')->with('error', 'Proses cron job tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('cronJob.index')->with('success', 'Proses cron job berjaya dihapus!');

    }

    //SAVE CRON JOB LOG
    public static function saveCronjobLog($cronjob_type_id, $process_method, $result)
    {
        $cronjobLogs = new CronjobLogs;
        $cronjobLogs->cronjob_type_id = $cronjob_type_id;
        $cronjobLogs->process_method = $process_method;
        $cronjobLogs->process_status = ($result) ? 1 : 0;
        $cronjobLogs->action_on = currentDate();
        $cronjobLogs->action_by = loginId();

        $cronjobLogs->save();
    }

    //BATAL TAWARAN KUARTERS
    public static function cancelApplicationOffer()
    {
        $cronjob_type_id = 1;
        $expired_date = now()->subDays(15);
        $application_status_id = 7;

        $result = Application::where(['application.is_draft'=> 0,'application.data_status'=> 1])
            ->whereDate('application_date_time','<', $expired_date)
            ->whereHas('current_status', function ($query) use ($application_status_id) {
                $query->where('application_status_id', $application_status_id);
            })
            ->update(['data_status' => 2, 'action_on'=>currentDate()]);

        //SAVE LOG
        CronJobController::saveCronjobLog($cronjob_type_id, (new static)->process_method, $result);  //if($result)
    }

    //BATAL TEMUJANJI
    public static function cancelComplaintAppointment()
    {
        $cronjob_type_id = 2;
        $expired_date = now()->format('Y-m-d');
        $expired_time = now()->format('H:i:s');

        //Appointment will cancel automatically if tenant LATE SUBMIT APPOINTMENT APPROVAL to admin
        $cancelAppointmentAll = ComplaintAppointment::where('data_status', 1) ->whereNull('appointment_status_id')
            ->whereRaw("complaint_id NOT IN (SELECT complaint_id FROM complaint_appointment b where b.appointment_status_id =1 and b.data_status=1)")
            ->whereRaw("complaint_id IN (SELECT id FROM complaint WHERE data_status =1 and complaint_type =1)")
            // ->where('appointment_date' , '<',  $expired_date )
            ->where(function($query) use ( $expired_date, $expired_time) {
                $query->where('appointment_date' , '<=', $expired_date )  //Boleh sahkan hari yang sama dengan hari temujanji
                      ->where('appointment_time' , '<=',  $expired_time ); //Batal setelah sampai masa temujanji
            })
            ->orderBy('id','desc')
            ->groupby('complaint_id')
            ->get();

        if($cancelAppointmentAll)
        {
            //update table 1
            foreach($cancelAppointmentAll as $cancelAppointment)
            {
                $complaint = Complaint::where('id', $cancelAppointment->complaint_id)->first();

                $complaint->cancel_reason             = 'Dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan. Sila buat aduan baru untuk tetapan temujanji yang baru.';
                $complaint->data_status               = 2;
                $complaint->delete_on                 = currentDate();
                $result = $complaint->save();
            }

            //update table 2
            foreach($cancelAppointmentAll as $cancelAppointment)
            {
                $complaintAppointment = ComplaintAppointment::where('id', $cancelAppointment->id)->first();

                $complaintAppointment->cancel_remarks            = 'Dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan. Sila buat aduan baru untuk tetapan temujanji yang baru.';
                $complaintAppointment->data_status               = 2;
                $complaintAppointment->delete_on                 = currentDate();
                $complaintAppointment->save();

                //SEND NOTIFICATION TO USER
                //LATE SUBMIT APPOINTMENT APPROVAL
                $appointment_id = $cancelAppointment->id;
                $complaint = Complaint::where('id', $cancelAppointment->complaint_id )->first();
                $complaint_ref_no = $complaint->ref_no;

                $complaint->user?->notify(new AppointmentLateApprovalNotification($appointment_id, $complaint_ref_no));
            }

            //SAVE LOG
            CronJobController::saveCronjobLog($cronjob_type_id, (new static)->process_method, $cancelAppointmentAll);//if($cancelAppointmentAll)
        }
    }

    //PROSES DENDA HILANG KELAYAKAN
    public static function checkTenantBlacklistPenalty()
    {
        $cronjob_type_id = 3;

        $tenantsPenalty = BlacklistPenalty::getAllTenantsBlacklistPenalty();

        foreach($tenantsPenalty as $tPenalty){

            $district_code      =  $tPenalty->tenant->district_tenant->district_code;
            $initialDate        = convertDateDb($tPenalty->penalty_date);
            $intialMonth        = getMonthFromDate($initialDate);
            $subsequentMonths   = self::_getMonthListFromDate(Carbon::createFromFormat('Y-m-d', $initialDate));

            // Subsequent months
            foreach ($subsequentMonths as $date) {

                $month   = getMonthFromDate($date);

                if($month > $intialMonth){

                    $curr_running_no    = self::_getCurrentRunningNo();
                    $ref_no             = self::_generateRefNo($curr_running_no, $district_code);
                    $monthsApart        = self::_calculateMonthsApart($initialDate, $date);
                    $selectedRate       = BlacklistPenaltyRate::getRateBasedOnMonthsApart($monthsApart, convertDateDb($tPenalty->penalty_date));

                    $bp = new BlacklistPenalty;
                    $bp->penalty_ref_no                 = $ref_no;
                    $bp->running_no                     = $curr_running_no;
                    $bp->tenants_id                     = $tPenalty->tenants_id;
                    $bp->execution_date                 = $initialDate;
                    $bp->penalty_date                   = $date;
                    $bp->market_rental_fee              = $tPenalty->market_rental_amount;
                    $bp->blacklist_penalty_rate_list_id = $selectedRate->id;
                    $bp->penalty_amount                 = ($selectedRate->rate / 100) * $tPenalty->market_rental_amount;
                    $bp->meeting_remarks                = $tPenalty->meeting_remarks;
                    $bp->action_by                      = loginId();
                    $bp->action_on                      = currentDate();
                    $bp->save();
                }
            }
        }

        //SAVE LOG
        CronJobController::saveCronjobLog($cronjob_type_id, (new static)->process_method, $tenantsPenalty); //if($result)
    }

    //CHECK HRMIS - PENCEN
    public static function checkHrmis()
    {
        $cronjob_type_id = 4;

        $tenants = Tenant::select('tenants.id','tenants.user_id','tenants.new_ic', 'tenants.name', 'tenants.leave_status_id', 'users.expected_date_of_retirement')
                        ->join('users', 'users.id', '=', 'tenants.user_id')
                        ->where(['tenants.data_status'=>1, 'users.data_status'=>1])->whereNull('tenants.leave_status_id')
                        ->whereNull('users.expected_date_of_retirement')->get();

        foreach ($tenants as $tenant) {

            // $new_ic = "840820016000";
            $new_ic = $tenant->new_ic;

            $dataHRMIS  = getDataFromHRMIS($new_ic);

            if($dataHRMIS['table']['response']['StatusSemakanPemilikKompetensi']['KodStatusHRMIS']  == "01") //00:Register 01:Telah Pencen
            {
                // Extract the value of "TarikhDiJangkaBesara"
                $tarikhDiJangkaBesara = $dataHRMIS['table']['response']['MaklumatPerkhidmatan']['TarikhDiJangkaBesara'];

                // Update the tenant's expected date of retirement in the User table
                User::where('id', $tenant->user_id)->update(['expected_date_of_retirement' => $tarikhDiJangkaBesara]);
            }
        }

        // Log the result for each proses
        CronJobController::saveCronjobLog($cronjob_type_id, (new static)->process_method, $tenants);
    }

    //ADD YEAR
    public static function addYear()
    {
        $cronjob_type_id = 5;

        $year = date('Y');
        $result = Year::firstOrCreate(['year' => $year, 'data_status' => 1]);

        //SAVE LOG
        CronJobController::saveCronjobLog($cronjob_type_id, (new static)->process_method, $result); //if($result)
    }

    //PRIVATE FUNCTIONS
    private static function _getMonthListFromDate(Carbon $start)
    {
        $start->setDay(1);
        $start->addMonth();

        $months = [];

        foreach (CarbonPeriod::create($start, '1 month', Carbon::today()) as $month) {
            $months[] = $month->format('Y-m-d');
        }

        return $months;
    }

    private static function _getCurrentRunningNo()
    {
        $latest_record = BlacklistPenalty::orderBy('id', 'desc')->first();

        return ($latest_record) ? $latest_record->running_no + 1 : 1;
    }

    private static function _generateRefNo($running_no, $district_code)
    {
        $ref_no = str_pad($running_no, 4, "0", STR_PAD_LEFT);

        $ref_no = 'DHK' . $district_code . currentYearTwoDigit() . currentMonth() . $ref_no;

        return $ref_no;
    }

    private static function _calculateMonthsApart($initialDate, $penaltyDate)
    {
        $diffByMonth = ($initialDate && $penaltyDate) ? getDateDiffByMonth($initialDate, $penaltyDate) : 0;

        return $diffByMonth;
    }

}
