<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PublicComplaintNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     public $complaint_ref_no;
     public $complaint_id;
     public $complaint_status;

    public function __construct($complaint_ref_no, $complaint_id, $complaint_status)
    {
        $this->complaint_ref_no  = $complaint_ref_no;
        $this->complaint_id      = $complaint_id;
        $this->complaint_status  = $complaint_status;
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

        if($this->complaint_status == 1){ // diterima
            $title      = 'ADUAN AWAM DITERIMA';
            $body       = $this->complaint_ref_no.' : Aduan anda telah diterima dan tindakan selanjutnya akan diambil.';
            $action     = 'Aduan anda telah diterima dan tindakan selanjutnya akan diambil.';
            $url        = getPortalUrl().'/AduanAwam/Papar/'.$this->complaint_id;
        }
        else if($this->complaint_status == 2) {
            $title      = 'ADUAN AWAM DITOLAK';
            $body       = $this->complaint_ref_no.' : Harap maaf, aduan anda telah ditolak. Tiada tindakan akan diambil.';
            $action     = 'Harap maaf, aduan anda telah ditolak. Tiada tindakan akan diambil.';
            $url        = getPortalUrl().'/AduanAwam/Papar/'.$this->complaint_id;
        }else
        {
            $title      = 'ADUAN AWAM SELESAI';
            $body       = $this->complaint_ref_no.' : Pemantauan aduan anda telah dibuat. Status aduan anda telah dikemaskini.';
            $action     = 'Pemantauan aduan anda telah dibuat. Status aduan anda telah dikemaskini';
            $url        = getPortalUrl().'/AduanAwam/Papar/'.$this->complaint_id;
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

