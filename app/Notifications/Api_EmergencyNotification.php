<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Api_EmergencyNotification extends Notification
{
    use Queueable;

    public $officer_name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($officer_name)
    {
        $this->officer_name  = $officer_name;
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
        $title      = "KECEMASAN!";
        $body       = "Pegawai $this->officer_name berada di dalam kecemasan dan memerlukan bantuan segera!";
        $action     = "Pegawai $this->officer_name berada di dalam kecemasan dan memerlukan bantuan segera!";
        $url        = "";

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 1,//to admin system
            "flag_emergency" => 1 //for emergency during monitoring
        ];
    }
}
