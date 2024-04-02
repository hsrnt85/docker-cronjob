<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TenantsPaymentNoticeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     public $year, $month, $tenants_payment_notice_id, $payment_notice_no;


    public function __construct($year, $month, $tenants_payment_notice_id, $payment_notice_no)
    {
        $this->year = $year;
        $this->month = $month;
        $this->payment_notice_no = $payment_notice_no;
        $this->tenants_payment_notice_id = $tenants_payment_notice_id;
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

        $title      = 'NOTIS BAYARAN UNTUK '.$this->month.'/'.$this->year;
        $body       = $this->payment_notice_no.' : Notis bayaran untuk '.$this->month.'/'.$this->year.' telah dijana. Sila semak maklumat notis bayaran dan lakukan pembayaran segera.';
        $url        = getPortalUrl().'/MaklumatBayaran/Papar/'.$this->tenants_payment_notice_id;

        return [
            "title" => $title,
            "body" => $body,
            "action" => $body,
            "url" => $url,
            "flag_system" => 2
        ];
    }
}
