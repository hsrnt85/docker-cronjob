<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ComplaintNotification extends Notification
{
    use Queueable;
    public $complaint_type;
    public $complaint_ref_no;
    public $complaint_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($complaint_type, $complaint_ref_no, $complaint_id)
    {
        $this->complaint_type  = $complaint_type;
        $this->complaint_ref_no  = $complaint_ref_no;
        $this->complaint_id  = $complaint_id;
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

        if($this->complaint_type == 1){
            $title      = 'ADUAN KEROSAKAN BARU';
            $body       = $this->complaint_ref_no.' : Aduan baru telah diterima. Sila buat temujanji aduan. ';
            $action     = 'Aduan baru telah diterima. Sila buat temujanji aduan.';
            $url        = route('complaintAppointment.create', ['id'=> $this->complaint_id]);
        }
        else {
            $title      = 'ADUAN AWAM BARU';
            $body       = $this->complaint_ref_no.' : Aduan baru telah diterima. Sila buat pengesahan aduan.';
            $action     = 'Aduan baru telah diterima. Sila buat pengesahan aduan.';
            $url        = route('rulesViolationComplaintApproval.create', ['id'=> $this->complaint_id]);
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
