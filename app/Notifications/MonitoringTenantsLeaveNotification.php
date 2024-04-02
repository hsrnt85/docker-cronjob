<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonitoringTenantsLeaveNotification extends Notification
{
    use Queueable;

    public $new_ic;
    public $category_id;
    public $tenant_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($new_ic, $category_id, $tenant_id)
    {
        $this->new_ic = $new_ic;
        $this->category_id = $category_id;
        $this->tenant_id = $tenant_id;
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

        $title      = "PENGESAHAN PEMANTAUAN PENGHUNI KELUAR";
        $body       = "Pemantauan Penghuni Keluar " . $this->new_ic . " telah dibuat dan perlu diluluskan";
        $action     = "Pemantauan Penghuni Keluar " . $this->new_ic . " telah dibuat dan perlu diluluskan";
        $url        = route('tenant.leaveApproval', ['category' => $this->category_id, 'tenant' => $this->tenant_id]);

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 1
        ];
    }
}
