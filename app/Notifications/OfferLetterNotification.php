<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferLetterNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $title      = 'Permohonan Kuarters Anda Diluluskan';
        $body       = 'Tahniah! Permohonan Kuarters Anda Diluluskan. Sila berikan maklumbalas terimaaan tawaran kuarters dalam tempoh 14 hari dari tarikh surat tawaran.';
        $action     = 'Klik disini untuk jawapan terimaan kuarters';
        $url        = route('quartersAcceptanceApproval.index');

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 1
        ];
    }
}
