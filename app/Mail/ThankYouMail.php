<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation->load('campaign');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->donation->frequency === 'one-time'
                ? 'Thank You for Your Generous Donation to Hopenest'
                : 'Welcome to the Hopenest Family – Your Recurring Support Means the World!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donations.thank-you',
            with: [
                'donation' => $this->donation,
                'donorName' => $this->donation->is_anonymous ? 'Valued Donor' : $this->donation->donor_name,
                'campaignTitle' => $this->donation->campaign->title,
                'amount' => $this->donation->formatted_amount,
                'frequency' => ucfirst(str_replace('-', ' ', $this->donation->frequency)),
                'isRecurring' => $this->donation->frequency !== 'one-time',
                'nextBilling' => $this->donation->frequency !== 'one-time'
                    ? now()->add($this->donation->frequency === 'monthly' ? 'month' : ($this->donation->frequency === 'quarterly' ? '3 months' : 'year'))->format('F j, Y')
                    : null,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}