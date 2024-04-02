<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectorStatementNotification extends Notification
{
    use Queueable;

     public $collector_statement_id, $ref_no, $flag_proses, $flag_action,$tab_id;

    public function __construct($collector_statement_id, $ref_no, $flag_proses, $flag_action, $tab_id)
    {
        $this->collector_statement_id   = $collector_statement_id;
        $this->ref_no                   = $ref_no;
        $this->flag_proses              = $flag_proses;
        $this->flag_action              = $flag_action;
        $this->tab_id                   = $tab_id;
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
        // $url        = getAdminUrl().'/PenyataPemungut/Kemaskini/'.$this->collector_statement_id.'/'.$this->tab_id;
        $url        = route('collectorStatement.edit', ['id'=> $this->collector_statement_id, 'tab' => $this->tab_id]);

        if($this->flag_action == "semak")
        {
            if($this->flag_proses == "baru")
            {
                $title      = 'SEMAKAN PENYATA PEMUNGUT';
                $body       = 'Penyata Pemungut '.$this->ref_no.' telah ditambah. Sila buat semakan penyata pemungut.';
                $action     = 'Penyata Pemungut '.$this->ref_no.' telah ditambah. Sila buat semakan penyata pemungut.';
            }
            if($this->flag_proses == "kemaskini")
            {
                $title      = 'SEMAKAN PENYATA PEMUNGUT';
                $body       = 'Penyata Pemungut '.$this->ref_no.' telah dikemaskini. Sila buat semakan penyata pemungut';
                $action     = 'Penyata Pemungut '.$this->ref_no.' telah dikemaskini. Sila buat semakan penyata pemungut.';
            }
            if($this->flag_proses == "batal")
            {
                $title      = 'SEMAKAN PENYATA PEMUNGUT DIBATALKAN';
                $body       = 'Penyata Pemungut '.$this->ref_no.' telah dibatalkan. Semakan Penyata Pemungut telah ditugaskan kepada pegawai yang lain.';
                $action     = 'Penyata Pemungut '.$this->ref_no.' telah dibatalkan. Semakan Penyata Pemungut telah ditugaskan kepada pegawai yang lain.';
            }
        }
        else if($this->flag_action == "lulus")
        {
            if($this->flag_proses == "baru")
            {
                $title      = 'KELULUSAN PENYATA PEMUNGUT';
                $body       = 'Penyata Pemungut '.$this->ref_no.' telah disemak. Sila buat kelulusan penyata pemungut.';
                $action     = 'Penyata Pemungut '.$this->ref_no.' telah disemak. Sila buat kelulusan penyata pemungut.';
            }
            if($this->flag_proses == "batal")
            {
                $title      = 'KELULUSAN PENYATA PEMUNGUT DIBATALKAN';
                $body       = 'Penyata Pemungut '.$this->ref_no.' telah dibatalkan. Kelulusan Penyata Pemungut telah ditugaskan kepada pegawai yang lain.';
                $action     = 'Penyata Pemungut '.$this->ref_no.' telah dibatalkan. Kelulusan Penyata Pemungut telah ditugaskan kepada pegawai yang lain.';
            }
        }

        if($this->flag_proses == "kuiri")
        {
            $title      = 'KUIRI PENYATA PEMUNGUT';
            $body       = 'Penyata Pemungut '.$this->ref_no.' dikuiri. Sila buat kuiri penyata pemungut.';
            $action     = 'Penyata Pemungut '.$this->ref_no.' dikuiri. Sila buat kuiri penyata pemungut.';
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
