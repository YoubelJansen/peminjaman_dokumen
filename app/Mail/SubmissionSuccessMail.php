<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loanData;

    // Terima data dari Controller
    public function __construct($loanData)
    {
        $this->loanData = $loanData;
    }

    // Bangun isi email
    public function build()
    {
        return $this->subject('Permintaan Peminjaman Berhasil Disubmit - LendCore')
                    ->view('emails.submission_success'); 
                    // Pastikan file view ini juga sudah dibuat (langkah di bawah)
    }
}