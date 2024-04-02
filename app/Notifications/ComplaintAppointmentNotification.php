<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintAppointmentNotification extends Notification
{
    use Queueable;

    public $complaint_ref_no;
    public $flag_proses;
    public $appointment_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($complaint_ref_no, $flag_proses, $appointment_id)
    {
        $this->complaint_ref_no  = $complaint_ref_no;
        $this->flag_proses  = $flag_proses;
        $this->appointment_id = $appointment_id;
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
        $title      = "";
        $body       = "";
        $action     = "";
        $url        = "";

        if ($this->flag_proses == "store")
        {
            $title      = 'TEMUJANJI ADUAN';
            $body       = $this->complaint_ref_no.' : Aduan anda telah diterima dan maklumat temujanji telah dikemaskini. Sila sahkan temujanji aduan anda.';
            $action     = 'Aduan anda telah diterima dan maklumat temujanji telah dikemaskini. Sila sahkan temujanji aduan anda.';
            $url        = getPortalUrl().'/PengesahanTemujanji/Kemaskini/AduanKerosakan/'.$this->appointment_id;
        }
        else
        {
            $title      = 'BATAL TEMUJANJI OLEH PEGAWAI ';
            $body       = $this->complaint_ref_no.' : Temujanji pemantauan aduan telah dibatalkan. Maklumat temujanji aduan akan dipinda ke tarikh yang baru.';
            $action     = 'Temujanji pemantauan aduan telah dibatalkan. Maklumat temujanji aduan akan dipinda ke tarikh yang baru.';
            $url        = getPortalUrl().'/PengesahanTemujanji/Papar/AduanKerosakan/'.$this->appointment_id;
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
