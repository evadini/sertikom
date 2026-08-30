<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventUnpublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public User $buyer;

    public function __construct(Event $event, User $buyer)
    {
        $this->event = $event;
        $this->buyer = $buyer;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event ' . $this->event->name . ' telah dibatalkan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-unpublished',
        );
    }
}
