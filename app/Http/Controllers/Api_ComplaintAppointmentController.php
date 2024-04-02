<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Api\Api_ComplaintAppointment;
use App\Notifications\AppointmentApprovalNotification;
use App\Notifications\ComplaintAppointmentNotification;


class Api_ComplaintAppointmentController extends Controller
{
    //cancel Appointment
    public function cancelAppointmentByOfficer(Request $request)
    {
        $complaint_id = $request->complaintId;
        $cancel_remarks = $request->officer_cancel_remarks;

        $complaint_appointment_latest = Api_ComplaintAppointment::latestAppointmentByComplaintId($complaint_id);

        if ($complaint_appointment_latest) {
            $complaint_appointment_latest->cancel_remarks          =  $cancel_remarks;
            $complaint_appointment_latest->data_status             =  2;
            $complaint_appointment_latest->delete_by               = auth('sanctum')->user()->id;
            $complaint_appointment_latest->delete_on               = currentDate();
            $saved = $complaint_appointment_latest->save();

            $complaintNoti = Complaint::select('id', 'users_id')->where('id', $complaint_id)->first();
            $complaintNoti->user?->notify(new ComplaintAppointmentNotification($complaint_appointment_latest->ref_no, 'cancel', $complaint_appointment_latest->id));

            if ($saved) {
                return response()->json([
                    'status' => true,
                    'message' => "Temujanji Aduan berjaya dibatalkan!",
                ], 200);
            } else {
                return response()->json([
                    'status'    => false,
                    'message'   => "Temujanji Aduan tidak berjaya dibatalkan!",
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'complaintAppointment' => "null!",
            ], 500);
        }
    }

    // BATAL TEMUJANJI OLEH PENGHUNI !
    public function cancelAppointmentByTenant(Request $request)
    {
        $appointment_id = $request->appointmentId;
        $cancel_remarks = $request->tenants_cancel_remarks;

        $complaint_appointment_latest = Api_ComplaintAppointment::latestAppointmentById($appointment_id); //dd($complaint_appointment_latest);
        $complaint = Complaint::select('id', 'ref_no', 'officer_id', 'data_status')->where('id', $complaint_appointment_latest->complaint_id)->first();

        if ($complaint_appointment_latest) {
            $complaint_appointment_latest->tenants_cancel_remarks_id  =  $cancel_remarks;
            $complaint_appointment_latest->data_status             =  2;
            $complaint_appointment_latest->delete_by               = auth('sanctum')->user()->id;
            $complaint_appointment_latest->delete_on               = currentDate();
            $saved = $complaint_appointment_latest->save();

            if ($saved) {

                $flag_proses = 2; //batal
                $complaint->officer_Api->notify(new AppointmentApprovalNotification($complaint->ref_no, $complaint_appointment_latest->id, $complaint_appointment_latest->appointment_status_id, $flag_proses, $complaint->id));

                return response()->json([
                    'status' => true,
                    'message' => "Temujanji Aduan berjaya dibatalkan!",
                ], 200);
            } else {
                return response()->json([
                    'status'    => false,
                    'message'   => "Temujanji Aduan tidak berjaya dibatalkan!",
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'complaintAppointment' => "null!",
            ], 500);
        }
    }
}
