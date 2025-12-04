<?php

namespace App\Notifications;

use App\Models\Volunteer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVolunteerApplication extends Notification implements ShouldQueue
{
    use Queueable;

    protected $volunteer;

    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Volunteer Application - ' . $this->volunteer->user->name)
            ->greeting('Hello Admin!')
            ->line('A new volunteer application has been submitted.')
            ->line('**Name:** ' . $this->volunteer->user->name)
            ->line('**Email:** ' . $this->volunteer->user->email)
            ->line('**Phone:** ' . ($this->volunteer->phone ?? 'N/A'))
            ->line('**Skills:** ' . ($this->volunteer->skills ?? 'N/A'))
            ->line('**Status:** ' . ucfirst($this->volunteer->status))
            ->line('**Applied:** ' . $this->volunteer->created_at->format('M d, Y h:i A'))
            ->action('Review Application', route('admin.volunteers.show', $this->volunteer->id))
            ->line('Please review and take appropriate action.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'volunteer_application',
            'volunteer_id' => $this->volunteer->id,
            'volunteer_name' => $this->volunteer->user->name,
            'volunteer_email' => $this->volunteer->user->email,
            'status' => $this->volunteer->status,
            'message' => 'New volunteer application from ' . $this->volunteer->user->name,
            'url' => route('admin.volunteers.show', $this->volunteer->id),
        ];
    }
}

