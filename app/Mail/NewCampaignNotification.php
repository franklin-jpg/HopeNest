<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCampaignNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Campaign Launched: ' . $this->campaign->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-campaign',
            with: [
                'campaign' => $this->campaign,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

}