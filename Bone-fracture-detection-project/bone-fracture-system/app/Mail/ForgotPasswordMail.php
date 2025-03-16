<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // Public variable to pass to the view

    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        $this->code = (string) $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Password Reset Code - Bonescope',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        Log::info("Inside Mailable - Reset Code: " . $this->code); // Debugging Log
        return new Content(
            view: 'emails.forgot-password-email',
            with: ['code' => $this->code] // Ensure the code is passed here
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
