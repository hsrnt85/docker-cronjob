<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\MeetingPanel;
use App\Models\MeetingApplication;
use App\Http\Resources\ListData;
use App\Models\Application;
use App\Models\Officer;
use App\Http\Requests\MeetingRegistrationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use \Carbon\Carbon;

class MeetingRegistrationController extends Controller
{
    public function index()
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $meeting = Meeting::where('data_status', 1)->orderBy('date','DESC');
        if($district_id)
        {
            $meeting = $meeting->where('district_id', $district_id);
        }
        $meetingAll = $meeting->get();

        return view( getFolderPath().'.list',
        [
            'meetingAll' => $meetingAll
        ]);
    }

    public function create()
    {
        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $meeting_id = 0;
        //PANEL - INTERNAL
        $officer_group_id = 1;

        $internalPanelAll = ListData::Officer($district_id, $officer_group_id);
        //PANEL - INVITATION
        $invitationPanelAll = ListData::InvitationPanel($district_id);
        //QUARTERS CATEGORY LIST
        $listQuartersCategoryAll = self::QuartersCategory(0, 5);//meeting_id, application_status_id

        $listApplicationAll = Application::join('application_scoring', 'application_scoring.application_id', '=', 'application.id')
            ->join('application_quarters_category', 'application_quarters_category.application_id', '=', 'application.id')
            ->join('users', 'users.id', '=', 'application.user_id')
            ->join('services_type', 'services_type.id', '=', 'users.services_type_id')
            ->leftJoin('meeting_application', function ($join) {
                $join->on('meeting_application.application_id','=','application.id');
                    $join->where('meeting_application.data_status', '=', 1);
                    //$join->whereNull('meeting_application.application_id');
                })
            ->select('application.id', DB::raw('users.name as applicant_name'), 'application.application_date_time', 'services_type.services_type',
                DB::raw('SUM(application_scoring.mark) as total_mark', 'application.application_status'),
                DB::raw('(CASE WHEN meeting_application.application_id IS NULL THEN 0 ELSE meeting_application.application_id END) AS meeting_application_id')
            )
            ->whereNull('meeting_application.application_id')
            //->orWhere('meeting_application.is_delay',1)
            ->where([
                ['application.data_status', 1],
                ['application_quarters_category.data_status', 1],
                ['services_type.data_status', 1]
            ]);
            $listApplicationAll = $listApplicationAll->whereHas('current_status', function ($query) use ($meeting_id) {
                if($meeting_id==0){
                    $query->where('application_status_id', 5); // 5:Lulus
                }
            })
            ->groupBy('application_scoring.application_id')
            ->get();//

        $listApplicationAll = $listApplicationAll->sortBy('total_mark');

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create', [
                'district_id' => $district_id,
                'internalPanelAll' => $internalPanelAll,
                'invitationPanelAll' => $invitationPanelAll,
                'listQuartersCategoryAll' => $listQuartersCategoryAll,
                'listApplicationAll' => $listApplicationAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(MeetingRegistrationRequest $request)
    {
        $application_ids = $request->application_ids;
        if($application_ids == null)
        {
            return redirect()->route('meetingRegistration.create')->with('error', 'Daftar Mesyuarat tidak berjaya ditambah! Senarai Permohonan Tidak Wujud');
        }

        $date   = convertDateDb(Carbon::createFromFormat('d/m/Y',  $request->date));
        $date_sys = $request->date;//dd($date_sys);

        $meeting = new Meeting;
        $meeting->bil_no                      = $request->bil_no;
        $meeting->district_id                 = districtId();
        $meeting->purpose                     = $request->purpose;
        $meeting->date                        = $date;
        $meeting->time                        = $request->time;
        $meeting->venue                       = $request->venue;
        $meeting->action_by                   = loginId();
        $meeting->action_on                   = currentDate();

        $saved = $meeting->save();

        $meeting_id = $meeting->id;

        //MEETING PANEL - INTERNAL
        $meeting_internal_panel_ids = $request->meeting_internal_panel_ids;
        foreach($meeting_internal_panel_ids as $i => $users_id)
        {
            $meetingPanel = new MeetingPanel;
            $meetingPanel->meeting_id        = $meeting_id;
            $meetingPanel->users_id          = $users_id;
            $meetingPanel->is_chairmain      = isset($request->meeting_chairmain_ids[$i]) ? 1 : 0;
            $meetingPanel->action_by         = loginId();
            $meetingPanel->action_on         = currentDate();

            $saved = $meetingPanel->save();

        }

        //MEETING PANEL - EXTERNAL
        $meeting_invitation_panel_ids = $request->meeting_invitation_panel_ids;
        foreach($meeting_invitation_panel_ids as $invitation_panel_id)
        {
            $meetingPanel = new MeetingPanel;
            $meetingPanel->meeting_id        = $meeting_id;
            $meetingPanel->invitation_panel_id  = $invitation_panel_id;
            $meetingPanel->action_by         = loginId();
            $meetingPanel->action_on         = currentDate();

            $saved = $meetingPanel->save();

        }

        //MEETING - APPLICATION
        foreach($application_ids as $application_id){

            $quarters_category_ids = isset($request->quarters_category_ids[$application_id]) ? explode(",",$request->quarters_category_ids[$application_id]) : '';// get all id

            foreach($quarters_category_ids as $quarters_category_id){
                $meetingAapplication = new MeetingApplication;
                $meetingAapplication->meeting_id      = $meeting_id;
                $meetingAapplication->application_id  = $application_id;
                $meetingAapplication->quarters_category_id  = $quarters_category_id;
                $meetingAapplication->action_by       = loginId();
                $meetingAapplication->action_on       = currentDate();
                $meetingAapplication->save();
            }
        }

        //------------------------------------------------------------------------------------------------------------------
        // Save User Activity
        //------------------------------------------------------------------------------------------------------------------
        setUserActivity("A", $date_sys.' : '.$meeting->bil_no);
        //------------------------------------------------------------------------------------------------------------------

        if(!$saved)
        {
            return redirect()->route('meetingRegistration.create')->with('error', 'Daftar Mesyuarat tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('meetingRegistration.index')->with('success', 'Daftar Mesyuarat berjaya ditambah!');
        }

    }

    public function edit(Request $request)
    {
        $meeting_id = $request->id;
        //$district_id = $request->district_id;

        //MEETING
        $meeting = Meeting::where('id', $meeting_id)->first();
        //MEETING PANEL
        //PANEL - INTERNAL & MEETING PANEL
        $internalPanelAll = self::internalPanel('rj',$meeting);//flag_join, $meeting data
        //PANEL - INVITATION & MEETING PANEL
        $invitationPanelAll = self::invitationPanel('rj',$meeting);//flag_join, $meeting data

        //QUARTERS CATEGORY LIST
        $listQuartersCategoryAll = self::QuartersCategory($meeting_id, 0);//meeting_id, application_status_id

        //APPLICATION & MEETING APPLICATION
        $listApplicationAll = Application::join('application_scoring', 'application_scoring.application_id', '=', 'application.id')
            ->join('application_quarters_category', 'application_quarters_category.application_id', '=', 'application.id')
            ->join('users', 'users.id', '=', 'application.user_id')
            ->join('services_type', 'services_type.id', '=', 'users.services_type_id')
            ->leftJoin('meeting_application', function ($join) use ($meeting_id){
                $join->on('meeting_application.application_id','=','application.id');
                    $join->where('meeting_application.data_status', '=', 1);
                    if($meeting_id>0){
                        $join->where('meeting_application.meeting_id', '=', $meeting_id);
                    }
                })
            ->select('application.id', DB::raw('users.name as applicant_name'), 'application.application_date_time', 'services_type.services_type',
                DB::raw('SUM(application_scoring.mark) as total_mark', 'application.application_status'),
                DB::raw('(CASE WHEN meeting_application.application_id IS NULL THEN 0 ELSE meeting_application.application_id END) AS meeting_application_id')
            )
            ->where([
                ['application.data_status', 1],
                ['application_quarters_category.data_status', 1],
                ['services_type.data_status', 1]
            ]);
            $listApplicationAll = $listApplicationAll->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 5); // 5:Lulus
            })
            ->groupBy('application_scoring.application_id')
            ->get();

        $listApplicationAll = $listApplicationAll->sortBy('total_mark');

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'internalPanelAll' => $internalPanelAll,
                'invitationPanelAll' => $invitationPanelAll,
                'meeting' => $meeting,
                'listQuartersCategoryAll' => $listQuartersCategoryAll,
                'listApplicationAll' => $listApplicationAll,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(Request $request)
    {
        $meeting_id = $request->meeting_id;

        DB::beginTransaction();

        try {
            $meeting = Meeting::findOrFail($meeting_id);
            $date_sys = convertDateSys($request->date);
             //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data before changes
            $data_before = $meeting->getRawOriginal();//dd($data_before);
            //------------------------------------------------------------------------------------------------------------------


            // Update Meeting
            $meeting->bil_no           = $request->bil_no;
            $meeting->district_id      = districtId();
            $meeting->purpose          = $request->purpose;
            $meeting->date             = $request->date;
            $meeting->time             = $request->time;
            $meeting->venue            = $request->venue;
            $meeting->action_by        = loginId();
            $meeting->action_on        = currentDate();
            $meeting->save();

            //---------------------------------------------------------------------------------------------------------------------------
            $meeting_internal_panel_ids = $request->meeting_internal_panel_ids;
            // Uncheck Panel-internal
            MeetingPanel::where('data_status', 1)
                        ->where('meeting_id', $meeting_id)
                        ->whereNotIn('users_id', array_keys($meeting_internal_panel_ids))
                        ->update([
                            'data_status' => 0,
                            'delete_by' => loginId(),
                            'delete_on' => currentDate()
                        ]);
            // Uncheck Chairman
            MeetingPanel::where('data_status', 1)
                        ->where('meeting_id', $meeting_id)
                        ->whereNotIn('is_chairmain', array_keys($request->meeting_chairmain_ids))
                        ->update([
                            'data_status' => 0,
                            'delete_by' => loginId(),
                            'delete_on' => currentDate()
            ]);
            //Insert
            foreach($meeting_internal_panel_ids as $i => $users_id)
            {
                $meetingPanel = MeetingPanel::where('meeting_id', $meeting_id)
                                    ->where('users_id', $users_id)
                                    ->where('data_status', 1)
                                    ->first();

                if(!$meetingPanel)
                {
                    $flag_meeting_chairmain = ($request->meeting_chairmain_ids[0]==$users_id) ? 1 : 0;

                    $meetingPanel = new MeetingPanel;
                    $meetingPanel->meeting_id   = $meeting_id;
                    $meetingPanel->users_id     = $users_id;
                    $meetingPanel->is_chairmain = $flag_meeting_chairmain;
                    $meetingPanel->action_by    = loginId();
                    $meetingPanel->action_on    = currentDate();
                    $meetingPanel->save();
                }else{

                }
            }
            //---------------------------------------------------------------------------------------------------------------------------
            $meeting_invitation_panel_ids = $request->meeting_invitation_panel_ids;
            // Uncheck Panel -invitation
            MeetingPanel::where('data_status', 1)
                        ->where('meeting_id', $meeting_id)
                        ->whereNotIn('invitation_panel_id', array_keys($meeting_invitation_panel_ids))
                        ->update([
                            'data_status' => 0,
                            'delete_by' => loginId(),
                            'delete_on' => currentDate()
                        ]);
            //Insert
            foreach($meeting_invitation_panel_ids as $invitation_panel_id)
            {
                $meetingPanel = MeetingPanel::where('meeting_id', $meeting_id)
                                    ->where('invitation_panel_id', $invitation_panel_id)
                                    ->where('data_status', 1)
                                    ->first();

                if($meetingPanel == null)
                {
                    $meetingPanel = new MeetingPanel;
                    $meetingPanel->meeting_id   = $meeting_id;
                    $meetingPanel->invitation_panel_id  = $invitation_panel_id;
                    $meetingPanel->action_by    = loginId();
                    $meetingPanel->action_on    = currentDate();
                    $meetingPanel->save();
                }
            }
            //---------------------------------------------------------------------------------------------------------------------------
            //MEETING - APPLICATION
            // Uncheck
            MeetingApplication::where('data_status', 1)
                        ->where('meeting_id', $meeting_id)
                        ->whereNotIn('application_id', array_keys($request->application_ids)) // id categoryClass checked
                        ->update([
                            'data_status' => 0,
                            'delete_by' => loginId(),
                            'delete_on' => currentDate()
                        ]);
            //Insert
            $application_ids = $request->application_ids;
            
            foreach($application_ids as $application_id){

                $meetingApplication = MeetingApplication::where('meeting_id', $meeting_id)
                                    ->where('application_id', $application_id)
                                    ->where('data_status', 1)
                                    ->first();

                if($meetingApplication == null)
                {
                    $quarters_category_ids = isset($request->quarters_category_ids[$application_id]) ? explode(",",$request->quarters_category_ids[$application_id]) : '';
                    
                    foreach($quarters_category_ids as $quarters_category_id){
                        $meetingAapplication = new MeetingApplication;
                        $meetingAapplication->meeting_id      = $meeting_id;
                        $meetingAapplication->application_id  = $application_id;
                        $meetingAapplication->quarters_category_id  = $quarters_category_id;
                        $meetingAapplication->action_by       = loginId();
                        $meetingAapplication->action_on       = currentDate();
                        $meetingAapplication->save();
                    }
                }
            }

            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            $data_after = $meeting;
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            setUserActivity("U", $date_sys.' : '.$meeting->bil_no, $data_before, $data_after);
            //------------------------------------------------------------------------------------------------------------------

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('meetingRegistration.index')->with('error', 'Daftar Mesyuarat tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('meetingRegistration.index')->with('success', 'Daftar Mesyuarat berjaya dikemaskini!');

    }

    public function view(Request $request)
    {

        $meeting_id = $request->id;

        $meeting = Meeting::where(['id'=> $meeting_id])->first();
        //MEETING PANEL
        //PANEL - INTERNAL & MEETING PANEL
        $internalPanelAll = self::internalPanel('j',$meeting);//flag_join, $meeting data
        //PANEL - INVITATION & MEETING PANEL
        $invitationPanelAll = self::invitationPanel('j',$meeting);//flag_join, $meeting data

        //QUARTERS CATEGORY LIST
        $listQuartersCategoryAll = self::QuartersCategory($meeting_id, 0);//meeting_id, application_id

        //APPLICATION & MEETING APPLICATION
        $listApplicationAll = Application::join('application_scoring', 'application_scoring.application_id', '=', 'application.id')
                ->join('application_quarters_category', 'application_quarters_category.application_id', '=', 'application.id')
                ->join('users', 'users.id', '=', 'application.user_id')
                ->join('services_type', 'services_type.id', '=', 'users.services_type_id')
                ->join('meeting_application', function ($join) use ($meeting_id){
                    $join->on('meeting_application.application_id','=','application.id');
                        $join->where('meeting_application.data_status', '=', 1);
                        if($meeting_id>0){
                            $join->where('meeting_application.meeting_id', '=', $meeting_id);
                        }
                    })
                ->select('application.id', DB::raw('users.name as applicant_name'), 'application.application_date_time', 'services_type.services_type',
                    DB::raw('SUM(application_scoring.mark) as total_mark', 'application.application_status'),
                    DB::raw('(CASE WHEN meeting_application.application_id IS NULL THEN 0 ELSE meeting_application.application_id END) AS meeting_application_id')
                )
                ->where([
                    ['application.data_status', 1],
                    ['application_quarters_category.data_status', 1],
                    ['services_type.data_status', 1]
                ])
                ->groupBy('application_scoring.application_id')
                ->get();

        $listApplicationAll = $listApplicationAll->sortBy('total_mark');

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'meeting'=>$meeting,
                'internalPanelAll'=>$internalPanelAll,
                'invitationPanelAll'=>$invitationPanelAll,
                'listQuartersCategoryAll' => $listQuartersCategoryAll,
                'listApplicationAll'=> $listApplicationAll
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function destroy(Request $request)
    {
        $meeting_id = $request->id;

        DB::beginTransaction();

        try {

            $meeting = Meeting::findOrFail($meeting_id);
            $meeting->data_status  = 0;
            $meeting->delete_by    = loginId();
            $meeting->delete_on    = currentDate();
            $meeting->save();

            MeetingPanel::where('meeting_id', $meeting_id)
                        ->update([
                            'data_status' => 0,
                            'delete_by' => loginId(),
                            'delete_on' => currentDate()
                        ]);

            MeetingApplication::where('meeting_id', $meeting_id)
                        ->update([
                            'data_status' => 0,
                            'delete_by' => loginId(),
                            'delete_on' => currentDate()
                        ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('meetingRegistration.index', ['id'=>$meeting_id])->with('error', 'Daftar Mesyuarat tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('meetingRegistration.index')->with('success', 'Daftar Mesyuarat berjaya dihapus!');
    }

    //INDEX SURAT
    public function indexLetter(Request $request)
    {
        $meeting_id = $request->id;

        $meeting = Meeting::where('id', $meeting_id)->first();
        //PEGAWAI PENGESAHAN
        $officerAll = self::officer($meeting, $meeting_id);
        //PANEL - INTERNAL & MEETING PANEL
        $internalPanelAll = self::internalPanel('j',$meeting);//flag_join, $meeting data
        //PANEL - INVITATION & MEETING PANEL
        $invitationPanelAll = self::invitationPanel('j',$meeting);//flag_join, $meeting data

        return view( getFolderPath().'.indexLetter',
        [
            'meeting' => $meeting,
            'officerAll' => $officerAll,
            'internalPanelAll' => $internalPanelAll,
            'invitationPanelAll' => $invitationPanelAll
        ]);
    }

    //KEMASKINI SURAT - UPDATE, CREATE & EMAIL LETTER
    public function processLetter(Request $request)
    {
        //Note: flag_process -> 1=jana surat, 2=email surat, 3=jana surat & email surat
        $flag_process = $request->flag;

        $meeting_id = $request->id;
        $meeting = Meeting::where('id', $meeting_id)->first();

        if($flag_process == 3){
            $meeting->letter_ref_no   = $request -> letter_ref_no;
            $meeting->letter_date     = $request -> letter_date;
            $meeting->officer_id      = $request -> officer;
            $meeting->action_by       = loginId();
            $meeting->action_on       = currentDate();
            $meeting->save();
        }

        $chairmain_arr = [];
        $meeting_panel_arr = [];
        $meeting_panel_email_arr = [];

        //PANEL - INVITATION & MEETING PANEL
        $invitationPanelAll = self::invitationPanel('j',$meeting);//flag_join, $meeting data
        //if(count($meeting_panel_arr)==0){ $v = 0; }else{ $v = count($meeting_panel_arr); }
        foreach($invitationPanelAll as $v => $invitationPanel)
        {
            if($invitationPanel->email != ''){
                $meeting_panel_arr[$v]['name'] = $invitationPanel->name;
                $meeting_panel_arr[$v]['position'] = $invitationPanel->position;
                $meeting_panel_arr[$v]['department'] =  $invitationPanel->department;

                 if($flag_process != 2){
                    $meeting_panel_email_arr[$v]['email']= $invitationPanel->email;
                    $meeting_panel_email_arr[$v]['name']= $invitationPanel->name;
                }
            }
        }

        //PANEL - INTERNAL & MEETING PANEL
        $internalPanelAll = self::internalPanel('j',$meeting);//flag_join, $meeting data
        if(count($meeting_panel_arr)==0){ $i = 0; }else{ $i = count($meeting_panel_arr); }
        //SET ARRAY MEETING PANEL
        foreach($internalPanelAll as $internalPanel)
        {
            if($internalPanel->email != ''){
                if($internalPanel->is_chairmain == 1){
                    $chairmain_arr['name'] = $internalPanel->users->name;
                    $chairmain_arr['position'] = $internalPanel->users->position->position_name;
                }
                $meeting_panel_arr[$i]['name'] = $internalPanel->users->name;
                $meeting_panel_arr[$i]['position'] = $internalPanel->users->position->position_name;
                $meeting_panel_arr[$i]['department'] = '';

                if($flag_process != 2){
                    $meeting_panel_email_arr[$i]['email']= $internalPanel->email;
                    $meeting_panel_email_arr[$i]['name']= $internalPanel->name;
                }
            }
            $i++;
        }

        //JANA SURAT
        $pdf = self::createLetter($meeting, $chairmain_arr, $meeting_panel_arr);
        if($flag_process == 2){
            return $pdf->stream('surat-jemputan-mesyuarat-jawatankuasa-perumahan-pdf.pdf');
        }
        //EMEL SURAT
        if($flag_process != 2){
            self::sendEmailSuratJemputanMesyuarat($pdf, $meeting_panel_email_arr);
            return redirect()->route('meetingRegistration.indexLetter', ['id'=>$meeting_id])->with('success', 'Surat jemputan mesyuarat jawatankuasa perumahan telah dihantar!');
        }

    }

    //FUNC - CREATE LETTER
    public function createLetter($meeting, $chairmain_arr, $meeting_panel_arr){

        return $pdf = PDF::loadView('download-pdf.surat-jemputan-mesyuarat-jawatankuasa-perumahan-pdf', [
            'meeting' => $meeting,
            'chairmain' => $chairmain_arr,
            'meeting_panel_arr' => $meeting_panel_arr
        ]);

        return $pdf;
    }

    //FUNC - EMAIL LETTER
    public function sendEmailSuratJemputanMesyuarat($pdf, $meeting_panel_email_arr){
        try {
            $emails_to = $meeting_panel_email_arr;

            Mail::send('email.email-surat-jemputan-mesyuarat-jawatankuasa-perumahan', [], function($message) use ($pdf, $emails_to)
            {
                $message->from(config('env.mail_username'), config('env.mail_sender_bkp'));
                $message->to($emails_to)->subject('Jemputan Mesyuarat Jawatankuasa Perumahan');
                $message->attachData($pdf->output(), 'surat-jemputan-mesyuarat-jawatankuasa-perumahan-'.date("dmY-His").'.pdf');
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //AJAX PROCESS
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //BY CATEGORY
    public function ajaxGetApplicationList(Request $request)
    {
        $quarters_category_id = $request->qcid;
        $meeting_id = $request->mid;
        $page = $request->p;

        $listApplicationAll = Application::join('application_scoring', 'application_scoring.application_id', '=', 'application.id')
            ->join('application_quarters_category', 'application_quarters_category.application_id', '=', 'application.id')
            ->join('users', 'users.id', '=', 'application.user_id')
            ->join('quarters_category', 'quarters_category.id', '=', 'application_quarters_category.quarters_category_id')
            ->join('services_type', 'services_type.id', '=', 'users.services_type_id');
            if($page =="view"){
                $listApplicationAll = $listApplicationAll->Join('meeting_application', function ($join) use ($meeting_id){
                    $join->on('meeting_application.application_id','=','application.id');
                    $join->where('meeting_application.data_status', '=', 1);
                    $join->where('meeting_application.meeting_id', '=', $meeting_id);
                });
            }else{
                $listApplicationAll = $listApplicationAll->leftjoin('meeting_application', function ($join) use ($meeting_id){
                    $join->on('meeting_application.application_id','=','application.id');
                    $join->where('meeting_application.data_status', '=', 1);
                    if($meeting_id>0){
                        $join->where('meeting_application.meeting_id', '=', $meeting_id);
                    }
                });
            }
            $listApplicationAll = $listApplicationAll->select('application.id', DB::raw('users.name as applicant_name'),DB::raw("DATE_FORMAT(application.application_date_time,'%d/%m/%Y') AS application_date"), 'services_type.services_type',
                DB::raw('quarters_category.name as category_name'), 'application_quarters_category.quarters_category_id',
                DB::raw('SUM(application_scoring.mark) as total_mark', 'application.application_status'),
                DB::raw('(CASE WHEN meeting_application.application_id IS NULL THEN 0 ELSE meeting_application.application_id END) AS meeting_application_id')
            );
            if($page =="new"){
                $listApplicationAll = $listApplicationAll->whereNull('meeting_application.application_id');
            }
            $listApplicationAll = $listApplicationAll->where([
                ['application.data_status', 1],
                ['application_quarters_category.data_status', 1],
                ['services_type.data_status', 1]
            ]);
            if($quarters_category_id>0){
                $listApplicationAll = $listApplicationAll->where('application_quarters_category.quarters_category_id', '=', $quarters_category_id);
            }
            $listApplicationAll = $listApplicationAll->whereHas('current_status', function ($query) use ($meeting_id) {
                if($meeting_id==0){
                    $query->where('application_status_id', 5); // 5:Lulus
                }
            })
            ->groupBy('application_scoring.application_id')
            ->get();//

        $listApplicationAll = $listApplicationAll->sortBy('total_mark');

        return response()->json($listApplicationAll);
    }

    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //FUNCTION
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function officer($meeting){

        $officerAll = Officer::join('users','users.id','=','officer.users_id')
            //->join('officer_category','officer_category.id','=','officer.officer_category_id')
            ->select('users.name','officer.id')
            ->where([
                ['officer.district_id', $meeting->district_id],
                ['officer.officer_group_id', 1],
                ['officer.data_status',1]
            ])
            ->get();

      return $officerAll;

    }

    public function internalPanel($flag_join, $meeting){

        $meeting_id = $meeting->id;
        $district_id = $meeting->district_id;

        if($flag_join == "j"){
            $internalPanelAll = MeetingPanel::join('officer', function ($join) use ($meeting_id, $district_id){
                $join->on('officer.users_id','=','meeting_panel.users_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                    if($district_id>0){
                        $join->where('officer.district_id', $district_id);
                    }
                });
        }else{
            $internalPanelAll = MeetingPanel::rightJoin('officer', function ($join) use ($meeting_id, $district_id){
                $join->on('officer.users_id','=','meeting_panel.users_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                    if($district_id>0){
                        $join->where('officer.district_id', $district_id);
                    }
                });
        }
        $internalPanelAll = $internalPanelAll->join('users','users.id','=','officer.users_id')
            ->join('position_grade','users.position_grade_id','=','position_grade.id')
            ->where([
                ['officer.data_status', 1],
                ['officer.district_id',$district_id],
                ['officer.officer_group_id', 1],
            ])
            ->select('meeting_panel.meeting_id',DB::raw('meeting_panel.users_id as meeting_panel_id'),'meeting_panel.is_chairmain','officer.users_id','users.name','users.position_id','users.email')
            ->orderBy('grade_no')
            ->groupBy('officer.users_id')
            ->get();

      return $internalPanelAll;

    }

    public function invitationPanel($flag_join, $meeting){

        $meeting_id = $meeting->id;
        if($flag_join == "j"){
            $invitationPanelAll = MeetingPanel::join('invitation_panel', function ($join) use ($meeting_id){
                $join->on('invitation_panel.id','=','meeting_panel.invitation_panel_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                });
        }else{
            $invitationPanelAll = MeetingPanel::rightJoin('invitation_panel', function ($join) use ($meeting_id){
                $join->on('invitation_panel.id','=','meeting_panel.invitation_panel_id')
                    ->where('meeting_panel.meeting_id', '=', $meeting_id)
                    ->where('meeting_panel.data_status','=', 1);
                });
        }
        $invitationPanelAll = $invitationPanelAll->select('meeting_panel.meeting_id',DB::raw('meeting_panel.invitation_panel_id as meeting_panel_id'),DB::raw('invitation_panel.id as invitation_panel_id'),'invitation_panel.name','invitation_panel.position','invitation_panel.department','invitation_panel.email')//,'representative','representative_position')
            ->groupBy('invitation_panel.id')
            ->get();

        return $invitationPanelAll;

    }

    //QUARTERS CATEGORY LIST
    public function QuartersCategory($meeting_id, $application_status_id){

        $quartersCategoryAll = Application::join('application_quarters_category', 'application.id', '=', 'application_quarters_category.application_id')
                ->join('quarters_category', 'quarters_category.id', '=', 'application_quarters_category.quarters_category_id');
                if($meeting_id>0){
                    $quartersCategoryAll = $quartersCategoryAll->join('meeting_application', function ($join) use ($meeting_id){
                        $join->on('application.id','=','meeting_application.application_id')
                            ->where('meeting_application.meeting_id', '=', $meeting_id)
                            ->where('meeting_application.data_status', '=', 1);
                    });
                }else{
                    $quartersCategoryAll = $quartersCategoryAll->leftJoin('meeting_application', function ($join) {
                        $join->on('meeting_application.application_id','=','application.id')
                            ->where('meeting_application.data_status', '=', 1);
                    });
                }
                $quartersCategoryAll = $quartersCategoryAll->select('application.id', DB::raw('quarters_category.name as category_name'), 'application_quarters_category.quarters_category_id')
                ->where([
                    ['application.data_status', 1],
                    ['application_quarters_category.data_status', 1]
                ]);
                if($application_status_id>0){
                    $quartersCategoryAll = $quartersCategoryAll->whereHas('current_status', function ($query) use ($application_status_id){
                        $query->where('application_status_id', $application_status_id); // 5:Lulus
                    })
                    ->whereNull('meeting_application.application_id');
                }
                $quartersCategoryAll = $quartersCategoryAll->groupBy('quarters_category.name')->get();

        return $quartersCategoryAll;

    }
}

