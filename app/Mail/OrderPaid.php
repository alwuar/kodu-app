<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderPaid extends Mailable
{
    public function __construct(public Order $order, public bool $forStore = false)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->forStore
                ? 'Nuevo pedido pagado '.$this->order->number
                : 'Recibimos tu pedido '.$this->order->number.' — KODU',
            replyTo: $this->forStore && $this->order->customer_email ? [$this->order->customer_email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.order-paid');
    }
}
