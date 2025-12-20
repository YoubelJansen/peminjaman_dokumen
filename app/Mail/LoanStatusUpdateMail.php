<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $customMessage;

    public function __construct($loan, $customMessage = null)
    {
        $this->loan = $loan;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        // Subject email otomatis berubah sesuai status dokumen
        return $this->subject('Update Status Peminjaman: ' . $this->loan->status)
                    ->view('emails.loan_status_update');
    }
}