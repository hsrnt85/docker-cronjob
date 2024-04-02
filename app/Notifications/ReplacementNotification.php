<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReplacementNotification extends Notification
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
        $title      = 'PERTUKARAN KUARTERS';
        $body       = 'Makluman, pertukaran unit kuarters anda berjaya. Sila buat pengesahan terimaan kuarters anda. ';
        $action     = 'Klik disini untuk pengesahan terimaan kuarters';
        // $url        = route('quartersAcceptanceApproval.index');
        //$url        = url('/') . '/kuarters/pengesahan';
        $url        = getPortalUrl(). '/kuarters/pengesahan';

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 2
        ];
    }
}
