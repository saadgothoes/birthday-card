<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $clientName,
        public string $clientEmail,
        public string $plainPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome! Your Account Credentials');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.client-welcome');
    }
}