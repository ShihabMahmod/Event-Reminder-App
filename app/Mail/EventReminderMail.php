<?php

namespace App\Mail;

use App\Models\Event; // Ensure to import the Event model
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event; 

    public function __construct(Event $event)
    {
        $this->event = $event; 
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Reminder: ' . $this->event->title, 
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder', 
            with: [
                'event' => $this->event, 
            ],
        );
    }

    public function attachments(): array
    {
        return []; // You can add attachments here if needed
    }
}
