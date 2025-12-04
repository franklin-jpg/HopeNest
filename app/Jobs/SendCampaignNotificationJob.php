<?php

namespace App\Jobs;

use App\Mail\NewCampaignNotification;
use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Campaign $campaign)
    {
        //
    }

    /**
     * Execute the job – send notification to all active subscribers
     */
    public function handle(): void
    {
        // Optional: double-check campaign is still published (in case of rollback)
        if ($this->campaign->status !== 'active') {
            return;
        }

        // Use cursor() to avoid loading all subscribers into memory at once
        Subscriber::active()
            ->select('email', 'name')
            ->cursor()
            ->each(function ($subscriber) {
                Mail::to($subscriber->email)
                    ->queue(new NewCampaignNotification($this->campaign));
            });
    }
}