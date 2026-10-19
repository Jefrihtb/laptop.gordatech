<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'slug',
    'target_audience',
    'brand_model',
    'processor',
    'ram',
    'storage',
    'gpu',
    'display',
    'price_daily',
    'price_weekly',
    'price_monthly',
    'min_rental_days',
    'available_units',
    'thumbnail',
    'images',
    'video_review_url',
    'benefits',
    'description',
    'is_popular',
    'is_active',
    'sort_order',
])]
class RentalPackage extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_daily' => 'integer',
            'price_weekly' => 'integer',
            'price_monthly' => 'integer',
            'min_rental_days' => 'integer',
            'available_units' => 'integer',
            'images' => 'array',
            'benefits' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function thumbnailUrl(): string
    {
        return Product::resolveMediaUrl($this->thumbnail);
    }

    public function formattedDaily(): string
    {
        return 'Rp '.number_format((float) $this->price_daily, 0, ',', '.');
    }

    public function formattedWeekly(): string
    {
        return 'Rp '.number_format((float) $this->price_weekly, 0, ',', '.');
    }

    public function formattedMonthly(): string
    {
        return 'Rp '.number_format((float) $this->price_monthly, 0, ',', '.');
    }
}
