{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('catalog.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('rental.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('service.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('articles.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    @foreach($categories as $cat)
    <url>
        <loc>{{ route('catalog.index', ['category' => $cat->slug]) }}</loc>
        <lastmod>{{ ($cat->updated_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.85</priority>
    </url>
    @endforeach

    @foreach($products as $prod)
    <url>
        <loc>{{ route('product.show', $prod->slug) }}</loc>
        <lastmod>{{ ($prod->updated_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
        @foreach($prod->galleryUrls() as $imgUrl)
        <image:image>
            <image:loc>{{ $imgUrl }}</image:loc>
            <image:title>{{ e($prod->name) }}</image:title>
        </image:image>
        @endforeach
    </url>
    @endforeach

    @foreach($articles as $art)
    <url>
        <loc>{{ route('articles.show', $art->slug) }}</loc>
        <lastmod>{{ ($art->updated_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
