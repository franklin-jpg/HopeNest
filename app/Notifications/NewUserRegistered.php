<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New User Registration - ' . $this->user->name)
            ->greeting('Hello Admin!')
            ->line('A new user has registered on the platform.')
            ->line('**Name:** ' . $this->user->name)
            ->line('**Email:** ' . $this->user->email)
            ->line('**Role:** ' . ucfirst($this->user->role))
            ->line('**Registered:** ' . $this->user->created_at->format('M d, Y h:i A'))
            ->action('View User', url('/admin/users/' . $this->user->id))
            ->line('Thank you for managing the platform!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'user_registered',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'user_role' => $this->user->role,
            'message' => 'New user registered: ' . $this->user->name,
            'url' => url('/admin/users/' . $this->user->id),
        ];
    }
}

