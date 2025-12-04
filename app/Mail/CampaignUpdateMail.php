<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $update;

    /**
     * Create a new message instance.
     */
    public function __construct(Campaign $campaign, CampaignUpdate $update)
    {
        $this->campaign = $campaign;
        $this->update = $update;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Update: ' . $this->campaign->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Emails.campaign-update',
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