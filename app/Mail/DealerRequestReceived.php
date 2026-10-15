<?php

namespace App\Mail;

use App\Models\DealerRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DealerRequestReceived extends Mailable
{
    public function __construct(public DealerRequest $dealer)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud de distribuidor: '.$this->dealer->fullName().' ('.$this->dealer->city.')',
            replyTo: [$this->dealer->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.dealer-request');
    }
}
