<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintMonitoringNotification extends Notification
{
    use Queueable;

    public $complaint_ref_no;
    public $complaint_id;
    public $complaint_type;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($complaint_ref_no, $complaint_id, $complaint_type)
    {
        $this->complaint_ref_no  = $complaint_ref_no;
        $this->complaint_id  = $complaint_id;
        $this->complaint_type  = $complaint_type;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable

     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title      = 'PEMANTAUAN ADUAN';
        $body       = $this->complaint_ref_no.' : Pemantauan aduan anda telah dibuat. Status aduan anda telah dikemaskini.';
        $action     = 'Pemantauan aduan anda telah dibuat. Status aduan anda telah dikemaskini.';
        $url        = "";

        if($this->complaint_type == 1)
        {
            $url        = getPortalUrl().'/AduanKerosakan/Papar/'.$this->complaint_id;
            // $url = route('damageComplaint.view', ['id'=> $this->complaint_id]);
        }
        else
        {
            $url        = getPortalUrl().'/AduanAwam/Papar/'.$this->complaint_id;
            // $url        = route('rulesViolationComplaint.view', ['id'=> $this->complaint_id]);
        }

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 2
        ];
    }
}
