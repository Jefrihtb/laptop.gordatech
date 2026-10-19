<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'category',
    'excerpt',
    'content',
    'thumbnail',
    'author_name',
    'read_time_minutes',
    'views_count',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'is_published',
    'published_at',
])]
class Article extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_time_minutes' => 'integer',
            'views_count' => 'integer',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function thumbnailUrl(): string
    {
        return Product::resolveMediaUrl($this->thumbnail);
    }
}
