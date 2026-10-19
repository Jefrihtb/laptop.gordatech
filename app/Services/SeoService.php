<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Product;
use App\Models\Setting;

class SeoService
{
    /**
     * Generate complete SEO metadata array and JSON-LD schemas for any page.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function generateMeta(array $overrides = []): array
    {
        $settings = Setting::allKeyed();
        $siteName = $settings['site_name'] ?? 'Cyber Gaming Store';
        $city = $settings['main_city_name'] ?? 'Jakarta';

        $title = $overrides['title'] ?? ($settings['seo_default_title'] ?? "{$siteName} — Jual Laptop Gaming Second & Baru, PC Rakitan, Sewa Laptop & Service #{$city}");
        $description = $overrides['description'] ?? ($settings['seo_default_description'] ?? '');
        $keywords = $overrides['keywords'] ?? ($settings['seo_default_keywords'] ?? '');
        $canonical = $overrides['canonical'] ?? url()->current();
        $ogImage = $overrides['og_image'] ?? 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=1200&q=85';
        $ogType = $overrides['og_type'] ?? 'website';
        $robots = $overrides['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

        $schemas = [
            self::localBusinessSchema($settings),
            self::websiteSchema($settings),
        ];

        if (! empty($overrides['schemas']) && is_array($overrides['schemas'])) {
            foreach ($overrides['schemas'] as $schema) {
                if (! empty($schema)) {
                    $schemas[] = $schema;
                }
            }
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'canonical' => $canonical,
            'og_image' => Product::resolveMediaUrl($ogImage),
            'og_type' => $ogType,
            'robots' => $robots,
            'site_name' => $siteName,
            'schemas' => $schemas,
        ];
    }

    /**
     * @param  array<string, string>  $settings
     * @return array<string, mixed>
     */
    public static function localBusinessSchema(array $settings): array
    {
        $phone = '+'.WhatsAppService::cleanNumber($settings['whatsapp_number'] ?? '0818880497');

        return [
            '@context' => 'https://schema.org',
            '@type' => ['ComputerStore', 'LocalBusiness'],
            '@id' => url('/').'#store',
            'name' => $settings['site_name'] ?? 'Cyber Gaming Store',
            'description' => $settings['seo_default_description'] ?? '',
            'url' => url('/'),
            'telephone' => $phone,
            'priceRange' => 'Rp 125.000 - Rp 35.000.000',
            'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=1200&q=85',
            'hasMap' => $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings['store_address'] ?? 'Toko Fisik Cyber Gaming Store',
                'addressLocality' => $settings['main_city_name'] ?? 'Jakarta',
                'addressCountry' => 'ID',
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '09:00',
                    'closes' => '21:00',
                ],
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.96',
                'reviewCount' => '540',
                'bestRating' => '5',
                'worstRating' => '1',
            ],
            'sameAs' => array_values(array_filter([
                $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA',
                $settings['instagram_url'] ?? null,
                $settings['tiktok_url'] ?? null,
                $settings['youtube_url'] ?? null,
            ])),
        ];
    }

    /**
     * @param  array<string, string>  $settings
     * @return array<string, mixed>
     */
    public static function websiteSchema(array $settings): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => $settings['site_name'] ?? 'Cyber Gaming Store',
            'inLanguage' => 'id-ID',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('catalog.index').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @param  iterable<int, Product>  $products
     * @return array<string, mixed>
     */
    public static function itemListSchema(iterable $products, string $listName = 'Katalog Unggulan Cyber Gaming Store'): array
    {
        $items = [];
        $pos = 1;
        foreach ($products as $prod) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'url' => route('product.show', $prod->slug),
                'name' => $prod->name,
                'image' => $prod->thumbnailUrl(),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $listName,
            'numberOfItems' => count($items),
            'itemListElement' => $items,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function productSchema(Product $product): array
    {
        $conditionUrl = $product->condition === 'new'
            ? 'https://schema.org/NewCondition'
            : 'https://schema.org/UsedCondition';

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'sku' => $product->sku,
            'mpn' => $product->sku,
            'category' => $product->category->name ?? 'Laptop & PC',
            'image' => $product->galleryUrls(),
            'description' => strip_tags($product->short_description ?: $product->description),
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand,
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => 'IDR',
                'price' => (string) $product->price,
                'priceValidUntil' => now()->addMonths(3)->toDateString(),
                'itemCondition' => $conditionUrl,
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => Setting::getValue('site_name', 'Cyber Gaming Store'),
                ],
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) ($product->rating ?: 4.96),
                'reviewCount' => (string) max(1, $product->review_count),
                'bestRating' => '5',
                'worstRating' => '1',
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function videoObjectSchema(Product $product): ?array
    {
        $embedUrl = $product->embedVideoUrl();
        if (! $embedUrl) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $product->video_review_title ?: "Video Review {$product->name} — Cyber Gaming Store",
            'description' => strip_tags($product->short_description ?: $product->description),
            'thumbnailUrl' => [$product->thumbnailUrl()],
            'uploadDate' => ($product->created_at ?? now())->toIso8601String(),
            'embedUrl' => $embedUrl,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function serviceSchema(string $name, string $description, string $url): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $description,
            'url' => $url,
            'provider' => [
                '@type' => 'ComputerStore',
                'name' => Setting::getValue('site_name', 'Cyber Gaming Store'),
                'telephone' => '+'.WhatsAppService::cleanNumber(Setting::getValue('whatsapp_number', '0818880497')),
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Indonesia',
            ],
        ];
    }

    /**
     * @param  array<int, array{q: string, a: string}>  $faqs
     * @return array<string, mixed>
     */
    public static function faqSchema(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn (array $item): array => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['a'],
                ],
            ], $faqs),
        ];
    }

    /**
     * @param  array<int, array{name: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    public static function breadcrumbSchema(array $crumbs): array
    {
        $elements = [];
        foreach ($crumbs as $index => $crumb) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function articleSchema(Article $article): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'image' => [$article->thumbnailUrl()],
            'datePublished' => ($article->published_at ?? $article->created_at)?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => $article->author_name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Setting::getValue('site_name', 'Cyber Gaming Store'),
            ],
        ];
    }
}
