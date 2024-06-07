<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailNotificationChannel extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public int $id, public string $email)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verify-email-notification-channel',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
