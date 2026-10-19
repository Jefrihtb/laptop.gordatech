<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'subtitle',
    'badge_text',
    'highlight_text',
    'image_url',
    'video_url',
    'cta_primary_text',
    'cta_primary_url',
    'cta_secondary_text',
    'cta_secondary_url',
    'accent_color',
    'position',
    'sort_order',
    'is_active',
])]
class Banner extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function resolvedImageUrl(): string
    {
        return Product::resolveMediaUrl($this->image_url);
    }
}
