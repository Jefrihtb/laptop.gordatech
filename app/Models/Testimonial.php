<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'customer_name',
    'customer_role',
    'customer_city',
    'service_type',
    'item_purchased',
    'rating',
    'comment',
    'avatar_url',
    'is_featured',
])]
class Testimonial extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function serviceLabel(): string
    {
        return match ($this->service_type) {
            'beli_laptop' => 'Pembeli Laptop Gaming / Kerja',
            'beli_pc' => 'Pembeli PC Gaming / Rakitan',
            'sewa_laptop' => 'Penyewa Laptop Korporat / Event',
            'service_sparepart' => 'Pelanggan Service & Sparepart',
            default => 'Pelanggan Terverifikasi',
        };
    }
}
