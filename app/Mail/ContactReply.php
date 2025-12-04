<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $replyMessage;
    public $adminName;

    public function __construct($contact, $replyMessage, $adminName = 'HopeNest Team')
    {
        $this->contact = $contact;
        $this->replyMessage = $replyMessage;
        $this->adminName = $adminName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->contact->email],
            subject: 'Re: ' . ucfirst(str_replace('_', ' ', $this->contact->subject)),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
            with: [
                'contact' => $this->contact,
                'replyMessage' => $this->replyMessage,
                'adminName' => $this->adminName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}