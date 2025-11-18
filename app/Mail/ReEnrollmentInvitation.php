<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ReEnrollmentInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public User $parent;

    /**
     * Create a new message instance.
     */
    public function __construct(User $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('mydiaree2026@gmail.com', 'My Diaree - Nextgen Montessori'),
            subject: 'Re-Enrollment 2026 - Complete Your Child\'s Enrollment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.re-enrollment-invitation',
            text: 'emails.re-enrollment-invitation-text'
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
