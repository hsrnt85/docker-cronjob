<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Api_MailSenaraiPenyataPemungut2d extends Mailable
{
    use Queueable, SerializesModels;

    public $nama_sistem, $nama_sistem_short, $maklumat_agensi, $email_subject, $email_ispeks, $dataIntegrasiIspeks;

    public function __construct($nama_sistem, $nama_sistem_short, $maklumat_agensi, $email_subject, $email_ispeks, $dataIntegrasiIspeks)
    {

        $this->nama_sistem = strtoupper($nama_sistem);
        $this->nama_sistem_short = strtoupper($nama_sistem_short);
        $this->maklumat_agensi  = $maklumat_agensi;
        $this->email_subject = $email_subject;
        $this->email_ispeks = $email_ispeks;
        $this->dataIntegrasiIspeks = $dataIntegrasiIspeks;
        
    }

    public function build()
    {
        $email_from = config('env.mail_username');
        $email_sender = config('env.mail_sender');

        return $this->from($email_from, $email_sender)
                    ->view('email.senarai-penyata-pemungut-2d')
                    ->subject($this->nama_sistem_short.' - '.$this->email_subject)
                    ->mailer('css_inliner');
    }
}
