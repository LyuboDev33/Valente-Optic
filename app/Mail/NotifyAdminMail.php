<?php

namespace App\Mail;

use App\Models\Admin\Promocode;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Collection $orderProducts,
        public ?Promocode $promoCode,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                'office@valenteoptic.com',
                'Valente Optics - Нова поръчка'
            ),
            subject: 'Имате нова поръчка #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notify-admin',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
