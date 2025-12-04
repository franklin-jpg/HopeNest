<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Message - ' . ucfirst($this->contact->subject))
            ->greeting('Hello Admin!')
            ->line('A new contact message has been received.')
            ->line('**From:** ' . $this->contact->first_name . ' ' . $this->contact->last_name)
            ->line('**Email:** ' . $this->contact->email)
            ->line('**Phone:** ' . ($this->contact->phone ?? 'N/A'))
            ->line('**Subject:** ' . ucfirst($this->contact->subject))
            ->line('**Message:**')
            ->line($this->contact->message)
            ->action('View & Reply', route('admin.contacts.show', $this->contact->id))
            ->line('Please respond as soon as possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'contact_message',
            'contact_id' => $this->contact->id,
            'sender_name' => $this->contact->first_name . ' ' . $this->contact->last_name,
            'sender_email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message' => substr($this->contact->message, 0, 100) . '...',
            'url' => route('admin.contacts.show', $this->contact->id),
        ];
    }
}

