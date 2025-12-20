<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustodyNewTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    // Menerima data pinjaman dari Controller
    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    public function build()
    {
        // Subject Email
        return $this->subject('Permintaan Baru Masuk (Approved) - Segera Proses')
                    ->view('emails.custody_new_task');
    }
}