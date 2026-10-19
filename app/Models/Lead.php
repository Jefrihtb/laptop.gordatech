<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'lead_code',
    'lead_type',
    'customer_name',
    'customer_phone',
    'customer_city',
    'subject',
    'items_summary',
    'total_amount',
    'whatsapp_message',
    'ip_address',
    'status',
])]
class Lead extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'items_summary' => 'array',
            'total_amount' => 'integer',
        ];
    }

    public function typeLabel(): string
    {
        return match ($this->lead_type) {
            'buy_direct' => 'Pemesanan Unit Langsung',
            'cart_checkout' => 'Checkout Keranjang WA',
            'rental_quote', 'rental_simulation' => 'Penawaran Sewa Laptop',
            'service_consultation', 'service_booking' => 'Booking & Konsultasi Service',
            'free_consultation' => 'Konsultasi Spesifikasi Gratis',
            default => 'Prospek WhatsApp',
        };
    }
}
