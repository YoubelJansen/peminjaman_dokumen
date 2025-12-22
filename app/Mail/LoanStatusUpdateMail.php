<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $statusMessage;
    public $customSubject;

    // Kita terima data loan, pesan khusus, dan subject email
    public function __construct($loan, $statusMessage, $customSubject)
    {
        $this->loan = $loan;
        $this->statusMessage = $statusMessage;
        $this->customSubject = $customSubject;
    }

    public function build()
    {
        return $this->subject($this->customSubject)
                    ->view('emails.loan_status_update');
    }
}