<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentLateApprovalNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     public $appointment_id;
     public $complaint_ref_no;

    public function __construct( $appointment_id, $complaint_ref_no)
    {
        $this->appointment_id = $appointment_id;
        $this->complaint_ref_no  = $complaint_ref_no;
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

        $title      = 'LEWAT SAHKAN TEMUJANJI';
        $body       = $this->complaint_ref_no.' : Temujanji pemantauan aduan anda telah dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan. Sila buat aduan baru untuk tetapan temujanji yang baru.';
        $action     = 'Temujanji pemantauan aduan anda telah dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan. Sila buat aduan baru untuk tetapan temujanji yang baru.';
        $url        = getPortalUrl().'/PengesahanTemujanji/Papar/AduanKerosakan/'.$this->appointment_id;

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 2
        ];
    }
}
