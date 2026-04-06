<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $link;
    protected $user;
    protected $body;
    /**
     * Create a new message instance.
     */


    public function __construct($user, $body, $link = null)
    {
        $this->user = $user;
        $this->body = $body;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Special Marketing Offer')
            ->view('emails.marketing')
            ->with([
                'user' => $this->user,
                'body' => $this->body,
                'link' => $this->link ?? 'https://yourwebsite.com'
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Marketing Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
