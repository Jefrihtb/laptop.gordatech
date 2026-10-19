<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'sku',
    'product_type',
    'condition',
    'condition_label',
    'brand',
    'price',
    'original_price',
    'stock',
    'sold_count',
    'views_count',
    'short_description',
    'description',
    'processor',
    'gpu',
    'ram',
    'storage',
    'display',
    'os',
    'battery_health',
    'warranty',
    'completeness',
    'extra_specs',
    'thumbnail',
    'images',
    'video_review_url',
    'video_review_type',
    'video_review_title',
    'is_best_seller_week',
    'is_popular_type',
    'popular_badge',
    'is_flash_deal',
    'is_featured',
    'is_active',
    'rating',
    'review_count',
    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'original_price' => 'integer',
            'stock' => 'integer',
            'sold_count' => 'integer',
            'views_count' => 'integer',
            'extra_specs' => 'array',
            'images' => 'array',
            'is_best_seller_week' => 'boolean',
            'is_popular_type' => 'boolean',
            'is_flash_deal' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'rating' => 'float',
            'review_count' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function formattedPrice(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    public function formattedOriginalPrice(): ?string
    {
        if (! $this->original_price || $this->original_price <= $this->price) {
            return null;
        }

        return 'Rp '.number_format((float) $this->original_price, 0, ',', '.');
    }

    public function discountPercentage(): int
    {
        if (! $this->original_price || $this->original_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    public function savingsFormatted(): ?string
    {
        if (! $this->original_price || $this->original_price <= $this->price) {
            return null;
        }

        return 'Rp '.number_format((float) ($this->original_price - $this->price), 0, ',', '.');
    }

    public function thumbnailUrl(): string
    {
        return self::resolveMediaUrl($this->thumbnail);
    }

    /**
     * Ensure at least 3 images are always returned with resolved URLs.
     *
     * @return array<int, string>
     */
    public function galleryUrls(): array
    {
        $rawImages = is_array($this->images) ? array_values(array_filter($this->images)) : [];
        if (empty($rawImages) && $this->thumbnail) {
            $rawImages = [$this->thumbnail];
        }

        $urls = array_map(fn (string $path): string => self::resolveMediaUrl($path), $rawImages);

        while (count($urls) < 3 && ! empty($urls)) {
            $urls[] = $urls[0];
        }

        return $urls;
    }

    public function embedVideoUrl(): ?string
    {
        if (empty($this->video_review_url)) {
            return null;
        }

        $url = trim($this->video_review_url);

        if (Str::contains($url, 'youtube.com/watch?v=')) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            if (! empty($query['v'])) {
                return 'https://www.youtube.com/embed/'.$query['v'];
            }
        }

        if (Str::contains($url, 'youtu.be/')) {
            $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
            if ($path !== '') {
                return 'https://www.youtube.com/embed/'.$path;
            }
        }

        if (Str::contains($url, 'youtube.com/shorts/')) {
            $parts = explode('youtube.com/shorts/', $url);
            $videoId = strtok($parts[1] ?? '', '?/');
            if ($videoId) {
                return 'https://www.youtube.com/embed/'.$videoId;
            }
        }

        return self::resolveMediaUrl($url);
    }

    public function isDirectVideoFile(): bool
    {
        if (empty($this->video_review_url)) {
            return false;
        }

        $lower = strtolower($this->video_review_url);

        return Str::endsWith($lower, ['.mp4', '.webm', '.ogg']) || $this->video_review_type === 'upload' || $this->video_review_type === 'mp4';
    }

    public static function resolveMediaUrl(?string $path): string
    {
        if (empty($path)) {
            return 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80';
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
