<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpdateOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $items;
    public $updatedorders;
    public $updatedtotal;
     
    /**
     * Create a new message instance.
     */
    public function __construct($name,$updatedtotal,$items,$updatedorders)
    {
        // dd($updatedorders);
        $this->name = $name;
        $this->items = $items;
        $this->updatedorders = $updatedorders;
        $this->updatedtotal = $updatedtotal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'UpdateOrder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'Updatedorder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
