<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentApprovalNotification extends Notification
{
    use Queueable;

    public $complaint_ref_no;
    public $appointment_id;
    public $appointment_status_id;
    public $flag_proses;
    public $complaint_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($complaint_ref_no, $appointment_id, $appointment_status_id, $flag_proses, $complaint_id)
    {
        $this->complaint_ref_no  = $complaint_ref_no;
        $this->appointment_id = $appointment_id;
        $this->appointment_status_id = $appointment_status_id;
        $this->flag_proses = $flag_proses;
        $this->complaint_id = $complaint_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title      = "";
        $body       = "";
        $action     = "";
        $url        = "";

        if ($this->flag_proses == 1) //update
        {
            $title      = 'TEMUJANJI ADUAN';

            if($this->appointment_status_id == 1){ //setuju
                $body       = $this->complaint_ref_no.' : Temujanji aduan telah disahkan.';
                $action     = 'Temujanji aduan telah disahkan.';
                $url        = route('complaintAppointment.view', ['id'=> $this->appointment_id]);
            }
            else if($this->appointment_status_id == 2) // tidaksetuju
            {
                $body       = $this->complaint_ref_no.' : Temujanji aduan ditolak, sila kemaskini maklumat temujanji yang baru.';
                $action     = 'Temujanji aduan ditolak, sila kemaskini maklumat temujanji yang baru.';
                $url        = route('complaintAppointment.edit', ['id'=> $this->appointment_id]);
            }
        }
        else if($this->flag_proses == 2 ) //cancel
        {
            $title      = 'BATAL TEMUJANJI OLEH PENGADU';
            $body       = $this->complaint_ref_no.' : Temujanji pemantauan aduan telah dibatalkan oleh pengadu, sila kemaskini maklumat temujanji aduan yang baru.';
            $action     = 'Temujanji pemantauan aduan telah dibatalkan oleh pengadu, sila kemaskini maklumat temujanji aduan yang baru.';
            $url        = route('complaintAppointment.create', ['id'=> $this->complaint_id]);
        }


        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 1
        ];
    }
}
