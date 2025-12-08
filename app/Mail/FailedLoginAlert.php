<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FailedLoginAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $data)
    {
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            from: env('MAIL_FROM_ADDRESS', 'katibayan.system@gmail.com'),
            replyTo: env('MAIL_FROM_ADDRESS', 'katibayan.system@gmail.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.failed-login-alert',
            with: [
                'data' => $this->data,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}