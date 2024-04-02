<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Api_MailFailDitolak extends Mailable
{
    use Queueable, SerializesModels;

    public $maklumat_fail, $maklumat_agensi, $email_subject, $email_ispeks, $errorCounter, $errorContent;

    public function __construct($maklumat_fail, $maklumat_agensi, $email_subject, $email_ispeks , $errorCounter, $errorContent)
    {

        $this->maklumat_fail = $maklumat_fail;
        $this->maklumat_agensi  = $maklumat_agensi;
        $this->email_subject = $email_subject;
        $this->email_ispeks = $email_ispeks;
        $this->errorCounter = $errorCounter;
        $this->errorContent = $errorContent;

    }


    public function build()
    {
        $email_from = config('env.mail_username');
        $email_sender = config('env.mail_sender');

        return $this->from($email_from, $email_sender)
                    ->subject($this->email_subject)
                    ->view('email.fail-ditolak');
    }
}
