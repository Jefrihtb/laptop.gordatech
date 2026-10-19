<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'slug',
    'category',
    'icon',
    'estimated_price_start',
    'estimated_price_end',
    'estimated_duration',
    'warranty_info',
    'symptoms',
    'description',
    'thumbnail',
    'is_popular',
    'is_active',
    'sort_order',
])]
class ServiceCatalog extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estimated_price_start' => 'integer',
            'estimated_price_end' => 'integer',
            'symptoms' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function formattedPriceRange(): string
    {
        $start = 'Rp '.number_format((float) $this->estimated_price_start, 0, ',', '.');
        if ($this->estimated_price_end && $this->estimated_price_end > $this->estimated_price_start) {
            return $start.' - Rp '.number_format((float) $this->estimated_price_end, 0, ',', '.');
        }

        return 'Mulai '.$start;
    }

    public function thumbnailUrl(): string
    {
        return Product::resolveMediaUrl($this->thumbnail);
    }
}
