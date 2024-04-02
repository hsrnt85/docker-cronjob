<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoutineInspectionApprovalNotification extends Notification
{
    use Queueable;

    public $ref_no;
    public $id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ref_no, $id)
    {
        $this->ref_no = $ref_no;
        $this->id = $id;
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

        $title      = "PENGESAHAN PEMANTAUAN BERKALA";
        $body       = "Pemantauan Berkala " . $this->ref_no . " telah dibuat dan perlu diluluskan";
        $action     = "Pemantauan Berkala " . $this->ref_no . " telah dibuat dan perlu diluluskan";
        $url        = route('routineInspectionApproval.edit', $this->id);

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 1
        ];
    }
}
