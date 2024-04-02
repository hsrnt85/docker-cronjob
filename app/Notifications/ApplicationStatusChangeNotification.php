<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangeNotification extends Notification
{
    use Queueable;

    public $application;
    public $actionBy;
    public $date;
    public $status;
    public $url;
    public $application_status_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($application, $status, $url, $actionBy, $date, $application_status_id)
    {
        $this->application  = $application;
        $this->status       = $status;
        $this->url          = $url;
        $this->actionBy     = $actionBy;
        $this->date         = $date;
        $this->application_status_id = $application_status_id;

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

        if($this->application_status_id == 2)
        {
            $title = "SEMAKAN PERMOHONAN";
            $body = "Permohonan " . $this->application->user?->name . " perlu dibuat semakan permohonan.";
        }
        else if($this->application_status_id == 3)
        {
            $title = "KELULUSAN PERMOHONAN";
            $body = "Permohonan " . $this->application->user?->name . " perlu dibuat kelulusan permohonan.";
        }
        else if($this->application_status_id == 1)
        {
            $title = "PEMARKAHAN PERMOHONAN";
            $body = "Permohonan " . $this->application->user?->name . " perlu dibuat pemarkahan permohonan.";
        }

        return [
            "title" => $title,
            "body" => $body,
            "action" => $body,
            "url" => $this->url,
            "flag_system" => 1//to admin system
        ];
    }
}
