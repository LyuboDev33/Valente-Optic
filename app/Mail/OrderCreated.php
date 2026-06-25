<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}


    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                'office@valenteoptic.com',
                'Valente Optic - Потвърждение за поръчка'
            ),
            subject: 'Потвърждение на поръчка #' . $this->order->order_number,
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'emails.order-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
