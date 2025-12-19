<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Variabel untuk menampung data form

    // Terima data dari Controller
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Judul Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Approval Request - Peminjaman Dokumen Baru',
        );
    }

    // Tampilan Isi Email
    public function content(): Content
    {
        return new Content(
            view: 'emails.submission', // Kita akan buat file view ini di langkah 4
        );
    }
}