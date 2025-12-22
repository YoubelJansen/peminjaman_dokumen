<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    public function build()
    {
        // Subject email
        return $this->subject('Konfirmasi: Dokumen Telah Anda Terima - LendCore')
                    ->view('emails.document_received');
    }
}