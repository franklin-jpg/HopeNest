<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\Volunteer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerAssignedToCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $volunteer;
    public $campaign;

    /**
     * Create a new message instance.
     */
    public function __construct(Volunteer $volunteer, Campaign $campaign)
    {
        $this->volunteer = $volunteer;
        $this->campaign = $campaign;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've Been Assigned to: {$this->campaign->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.volunteers.assigned-campaign',
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

