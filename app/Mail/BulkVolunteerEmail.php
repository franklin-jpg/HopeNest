<?php

namespace App\Mail;

use App\Models\Volunteer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulkVolunteerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSubject;
    public $emailMessage;
    public $volunteer;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $message, Volunteer $volunteer)
    {
        $this->emailSubject = $subject;
        $this->emailMessage = $message;
        $this->volunteer = $volunteer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.volunteers.bulk-email',
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
