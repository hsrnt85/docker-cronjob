<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JournalAdjustmentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     public $journal_id, $ref_no, $flag_action, $flag_proses, $tab;

    public function __construct($journal_id, $ref_no, $flag_proses, $flag_action, $tab)
    {
        $this->journal_id   = $journal_id;
        $this->ref_no       = $ref_no;
        $this->flag_proses  = $flag_proses;
        $this->flag_action  = $flag_action;
        $this->tab          = $tab;
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
        // $url        = getAdminUrl().'/JurnalPelarasan/Kemaskini/'.$this->journal_id.'/'.$this->tab;
        $url        = route('journalAdjustment.edit', ['id'=> $this->journal_id, 'tab' => $this->tab]);

        if($this->flag_action == "semak")
        {
            if($this->flag_proses == "baru")
            {
                $title      = 'SEMAKAN JURNAL PELARASAN';
                $body       = 'Jurnal Pelarasan '.$this->ref_no.' telah ditambah. Sila buat semakan jurnal pelarasan.';
                $action     = 'Jurnal Pelarasan '.$this->ref_no.' telah ditambah. Sila buat semakan jurnal pelarasan.';
            }
            if($this->flag_proses == "kemaskini")
            {
                $title      = 'SEMAKAN JURNAL PELARASAN';
                $body       = 'Jurnal Pelarasan '.$this->ref_no.' telah dikemaskini. Sila buat semakan jurnal pelarasan.';
                $action     = 'Jurnal Pelarasan '.$this->ref_no.' telah dikemaskini. Sila buat semakan jurnal pelarasan.';
            }
            if($this->flag_proses == "batal")
            {
                $title      = 'SEMAKAN JURNAL PELARASAN DIBATALKAN';
                $body       = 'Jurnal Pelarasan '.$this->ref_no.' telah dibatalkan. Semakan Jurnal Pelarasan telah ditugaskan kepada pegawai yang lain.';
                $action     = 'Jurnal Pelarasan '.$this->ref_no.' telah dibatalkan. Semakan Jurnal Pelarasan telah ditugaskan kepada pegawai yang lain.';
            }
        }
        else if($this->flag_action == "lulus")
        {
            if($this->flag_proses == "baru")
            {
                $title      = 'KELULUSAN JURNAL PELARASAN';
                $body       = 'Jurnal Pelarasan '.$this->ref_no.' telah disemak. Sila buat kelulusan jurnal pelarasan.';
                $action     = 'Jurnal Pelarasan '.$this->ref_no.' telah disemak. Sila buat kelulusan jurnal pelarasan.';
            }
            if($this->flag_proses == "batal")
            {
                $title      = 'KELULUSAN JURNAL PELARASAN DIBATALKAN';
                $body       = 'Jurnal Pelarasan '.$this->ref_no.' telah dibatalkan. Kelulusan Jurnal Pelarasan telah ditugaskan kepada pegawai yang lain.';
                $action     = 'Jurnal Pelarasan '.$this->ref_no.' telah dibatalkan. Kelulusan Jurnal Pelarasan telah ditugaskan kepada pegawai yang lain.';
            }
        }

        if($this->flag_proses == "kuiri")
        {
            $title      = 'KUIRI JURNAL PELARASAN';
            $body       = 'Jurnal Pelarasan '.$this->ref_no.' dikuiri. Sila buat kuiri jurnal pelarasan.';
            $action     = 'Jurnal Pelarasan '.$this->ref_no.' dikuiri. Sila buat kuiri jurnal pelarasan.';
        }

        return [
            "title" => $title,
            "body" => $body,
            "action" => $action,
            "url" => $url,
            "flag_system" => 1
        ];
    }

}
