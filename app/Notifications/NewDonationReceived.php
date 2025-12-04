<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDonationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Donation Received - ₦' . number_format($this->donation->amount, 2))
            ->greeting('Hello Admin!')
            ->line('A new donation has been received!')
            ->line('**Donor:** ' . ($this->donation->is_anonymous ? 'Anonymous' : $this->donation->donor_name))
            ->line('**Amount:** ₦' . number_format($this->donation->amount, 2))
            ->line('**Campaign:** ' . $this->donation->campaign->title)
            ->line('**Payment Method:** ' . ucfirst($this->donation->payment_method ?? 'N/A'))
            ->line('**Status:** ' . ucfirst($this->donation->status))
            ->line('**Date:** ' . $this->donation->created_at->format('M d, Y h:i A'))
            ->action('View Donation', url('/admin/donations/' . $this->donation->id))
            ->line('Thank you for your continued support!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'donation_received',
            'donation_id' => $this->donation->id,
            'donor_name' => $this->donation->is_anonymous ? 'Anonymous' : $this->donation->donor_name,
            'amount' => $this->donation->amount,
            'campaign_title' => $this->donation->campaign->title,
            'status' => $this->donation->status,
            'message' => 'New donation of ₦' . number_format($this->donation->amount, 2) . ' received',
            'url' => url('/admin/donations/' . $this->donation->id),
        ];
    }
}


