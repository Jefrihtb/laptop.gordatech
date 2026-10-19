<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\RentalPackage;
use App\Models\ServiceCatalog;
use App\Models\ServiceTicket;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StorefrontController extends Controller
{
    public function home(Request $request): View
    {
        $settings = Setting::allKeyed();
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $bestSellersWeek = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_best_seller_week', true)
            ->orderByDesc('sold_count')
            ->take(6)
            ->get();

        $popularTypes = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_popular_type', true)
            ->orderByDesc('views_count')
            ->take(6)
            ->get();

        $laptopsAndPcs = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereIn('product_type', ['laptop', 'pc'])
            ->latest()
            ->take(8)
            ->get();

        $spareparts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('product_type', 'sparepart')
            ->orderByDesc('sold_count')
            ->take(4)
            ->get();

        $rentalPackages = RentalPackage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $services = ServiceCatalog::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $articles = Article::query()
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $homeFaqs = [
            [
                'q' => 'Apakah beli laptop second di Cyber Gaming Store ada garansinya?',
                'a' => 'Ya! Seluruh laptop second dan PC rakitan di Cyber Gaming Store telah lulus 35 Titik Quality Control ketat dan dilengkapi Garansi Toko 3 hingga 6 Bulan penuh, plus layanan konsultasi & aftersales seumur hidup.',
            ],
            [
                'q' => 'Apakah setiap produk memiliki minimal 3 foto asli dan video review?',
                'a' => 'Betul! Setiap produk dilengkapi minimal 3 foto detail sudut fisik serta 1 video review & pengujian performa (benchmark/FPS). Anda juga bisa request Video Call WhatsApp ke 0818880497 untuk cek unit secara langsung.',
            ],
            [
                'q' => 'Bagaimana cara sewa laptop harian, mingguan, atau bulanan di Cyber Gaming Store?',
                'a' => 'Anda cukup memilih paket sewa di menu Penyewaan Laptop, masukkan jumlah unit dan durasi di kalkulator simulasi sewa, lalu klik Kirim Penawaran via WhatsApp 0818880497. Tersedia untuk personal, mahasiswa, maupun perusahaan (B2B).',
            ],
            [
                'q' => 'Apakah melayani service laptop segala merk dan jual sparepart original?',
                'a' => 'Ya! Tim teknisi berpengalaman kami melayani perbaikan mesin/motherboard, bersihkan kipas & ganti thermal paste, ganti layar LCD 144Hz/165Hz, hingga penjualan sparepart baru dan copotan original bergaransi.',
            ],
        ];

        $seo = SeoService::generateMeta([
            'title' => $settings['seo_default_title'] ?? 'Cyber Gaming Store - Jual Laptop Gaming Second & Baru, PC Rakitan, Sewa Laptop & Service #1',
            'description' => $settings['seo_default_description'] ?? '',
            'keywords' => $settings['seo_default_keywords'] ?? '',
            'canonical' => route('home'),
            'schemas' => [
                SeoService::itemListSchema($bestSellersWeek, 'Best Seller Minggu Ini — Cyber Gaming Store'),
                SeoService::faqSchema($homeFaqs),
            ],
        ]);

        return view('storefront.home', compact(
            'settings',
            'banners',
            'categories',
            'bestSellersWeek',
            'popularTypes',
            'laptopsAndPcs',
            'spareparts',
            'rentalPackages',
            'services',
            'testimonials',
            'articles',
            'homeFaqs',
            'seo'
        ));
    }

    public function catalog(Request $request): View
    {
        $settings = Setting::allKeyed();
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();

        $query = Product::query()->with('category')->where('is_active', true);

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('processor', 'like', "%{$search}%")
                    ->orWhere('gpu', 'like', "%{$search}%")
                    ->orWhere('ram', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::query()->where('slug', (string) $request->input('category'))->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($request->filled('type') && in_array($request->input('type'), ['laptop', 'pc', 'sparepart'], true)) {
            $query->where('product_type', (string) $request->input('type'));
        }

        if ($request->filled('condition') && in_array($request->input('condition'), ['second_like_new', 'second_good', 'new'], true)) {
            $query->where('condition', (string) $request->input('condition'));
        }

        if ($request->filled('brand')) {
            $query->where('brand', (string) $request->input('brand'));
        }

        if ($request->input('filter') === 'best_seller') {
            $query->where('is_best_seller_week', true);
        } elseif ($request->input('filter') === 'popular_type') {
            $query->where('is_popular_type', true);
        }

        if ($request->filled('budget')) {
            match ((string) $request->input('budget')) {
                'under_7m' => $query->where('price', '<=', 7000000),
                '7m_12m' => $query->whereBetween('price', [7000000, 12500000]),
                'above_12m' => $query->where('price', '>', 12500000),
                default => null,
            };
        }

        $sort = (string) $request->input('sort', 'best_seller');
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->latest(),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->orderByDesc('is_best_seller_week')->orderByDesc('sold_count'),
        };

        $products = $query->paginate(12)->withQueryString();
        $brands = Product::query()->where('is_active', true)->select('brand')->distinct()->pluck('brand');

        $pageTitle = $activeCategory
            ? ($activeCategory->meta_title ?: "{$activeCategory->name} Terbaik & Bergaransi - Cyber Gaming Store")
            : 'Katalog Laptop Gaming Second & Baru, PC Rakitan & Sparepart - Cyber Gaming Store';

        $pageDesc = $activeCategory
            ? ($activeCategory->meta_description ?: $activeCategory->description)
            : 'Daftar harga lengkap laptop gaming second like new, laptop bisnis ThinkPad/MacBook, PC Gaming rakitan, dan sparepart laptop original di Cyber Gaming Store.';

        $seo = SeoService::generateMeta([
            'title' => $pageTitle,
            'description' => (string) $pageDesc,
            'canonical' => route('catalog.index'),
            'schemas' => [
                SeoService::itemListSchema($products->items(), $pageTitle),
                SeoService::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => $activeCategory ? $activeCategory->name : 'Katalog Produk', 'url' => url()->current()],
                ]),
            ],
        ]);

        return view('storefront.catalog', compact(
            'settings',
            'categories',
            'activeCategory',
            'products',
            'brands',
            'seo'
        ));
    }

    public function productShow(string $slug): View
    {
        $settings = Setting::allKeyed();
        $product = Product::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('views_count');

        $relatedProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product): void {
                $q->where('category_id', $product->category_id)
                    ->orWhere('product_type', $product->product_type);
            })
            ->orderByDesc('is_best_seller_week')
            ->take(4)
            ->get();

        $recommendedSpareparts = Product::query()
            ->where('is_active', true)
            ->where('product_type', 'sparepart')
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        $productFaqs = [
            [
                'q' => "Apakah {$product->name} ini masih tersedia dan siap dikirim hari ini?",
                'a' => "Ya, selama tampil di katalog dengan stok tersedia ({$product->stock} unit), {$product->name} siap dipesan, cek video QC via WhatsApp 0818880497, atau dikirim ke seluruh Indonesia dengan packing kayu berasuransi.",
            ],
            [
                'q' => "Apa saja kelengkapan dan garansi untuk {$product->name}?",
                'a' => "Kelengkapan: {$product->completeness}. Dilengkapi {$product->warranty} serta jaminan lulus 35 titik Quality Control Cyber Gaming Store.",
            ],
        ];

        $seo = SeoService::generateMeta([
            'title' => $product->meta_title ?: "Jual {$product->name} ({$product->condition_label}) - Cyber Gaming Store",
            'description' => $product->meta_description ?: "Beli {$product->name} harga {$product->formattedPrice()} kondisi {$product->condition_label}. Spesifikasi: {$product->processor}, {$product->ram}, {$product->storage}, {$product->gpu}. Order via WA 0818880497.",
            'keywords' => $product->meta_keywords ?: "jual {$product->name}, {$product->brand} bekas, cyber gaming store",
            'canonical' => route('product.show', $product->slug),
            'og_image' => $product->thumbnailUrl(),
            'og_type' => 'product',
            'schemas' => [
                SeoService::productSchema($product),
                SeoService::videoObjectSchema($product),
                SeoService::faqSchema($productFaqs),
                SeoService::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Katalog', 'url' => route('catalog.index')],
                    ['name' => $product->category->name ?? 'Produk', 'url' => route('catalog.index', ['category' => $product->category->slug ?? ''])],
                    ['name' => $product->name, 'url' => route('product.show', $product->slug)],
                ]),
            ],
        ]);

        return view('storefront.product-detail', compact(
            'settings',
            'product',
            'relatedProducts',
            'recommendedSpareparts',
            'productFaqs',
            'seo'
        ));
    }

    public function rentalIndex(): View
    {
        $settings = Setting::allKeyed();
        $rentalPackages = RentalPackage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $rentalFaqs = [
            [
                'q' => 'Berapa durasi minimal penyewaan laptop di Cyber Gaming Store?',
                'a' => 'Kami melayani sewa laptop mulai dari Harian (minimal 1 hari untuk event/seminar), Mingguan, hingga kontrak Bulanan dan Tahunan untuk operasional perusahaan maupun mahasiswa.',
            ],
            [
                'q' => 'Bagaimana jika ada unit laptop yang bermasalah saat masa sewa?',
                'a' => 'Tidak perlu khawatir! Setiap penyewaan dilindungi garansi maintenance penuh dan jaminan penggantian unit cadangan (backup replacement) maksimal 1x24 jam tanpa biaya tambahan.',
            ],
            [
                'q' => 'Apa saja syarat sewa laptop untuk perusahaan (B2B) dan perorangan?',
                'a' => 'Untuk perusahaan cukup melampirkan PO/SPK dan identitas PIC. Untuk mahasiswa/perorangan cukup verifikasi identitas KTP/KTM/ID Karyawan melalui WhatsApp Admin Sewa di 0818880497.',
            ],
        ];

        $seo = SeoService::generateMeta([
            'title' => 'Sewa Laptop Harian, Mingguan & Bulanan Murah (Kantor, Event & Gaming) - Cyber Gaming Store',
            'description' => 'Pusat penyewaan laptop harian, mingguan, dan bulanan untuk kantor, pelatihan, CPNS, mahasiswa, dan event gaming. Ready ratusan unit ThinkPad, ROG, dan MacBook. Hubungi WA 0818880497.',
            'keywords' => 'sewa laptop harian, sewa laptop bulanan murah, rental laptop perusahaan, sewa laptop gaming rtx, cyber gaming store',
            'canonical' => route('rental.index'),
            'schemas' => [
                SeoService::serviceSchema(
                    'Layanan Sewa Laptop Harian, Mingguan & Bulanan — Cyber Gaming Store',
                    'Penyewaan laptop untuk perusahaan, pelatihan, seminar, kampus, dan event gaming dengan jaminan ganti unit cadangan 1x24 jam.',
                    route('rental.index')
                ),
                SeoService::faqSchema($rentalFaqs),
                SeoService::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Penyewaan Laptop', 'url' => route('rental.index')],
                ]),
            ],
        ]);

        return view('storefront.rental', compact(
            'settings',
            'rentalPackages',
            'rentalFaqs',
            'seo'
        ));
    }

    public function serviceAndSparepart(Request $request): View
    {
        $settings = Setting::allKeyed();
        $services = ServiceCatalog::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $spareparts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('product_type', 'sparepart')
            ->orderByDesc('sold_count')
            ->get();

        $trackedTicket = null;
        $ticketError = null;
        $ticketQuery = strtoupper(trim((string) $request->input('ticket', '')));
        $phoneLast4 = preg_replace('/\D+/', '', (string) $request->input('phone_last4', '')) ?? '';

        if ($ticketQuery !== '' || $phoneLast4 !== '') {
            if ($ticketQuery === '' || strlen($phoneLast4) < 4) {
                $ticketError = 'Masukkan Nomor Tiket beserta 4 digit terakhir nomor WhatsApp terdaftar untuk verifikasi keamanan.';
            } else {
                $candidateTicket = ServiceTicket::query()
                    ->whereRaw('UPPER(ticket_code) = ?', [$ticketQuery])
                    ->latest()
                    ->first();

                if ($candidateTicket && $candidateTicket->matchesPhoneLastDigits($phoneLast4)) {
                    $trackedTicket = $candidateTicket;
                } else {
                    $ticketError = 'Data service tidak ditemukan atau 4 digit terakhir nomor WhatsApp tidak sesuai dengan Nomor Tiket.';
                }
            }
        }

        $serviceFaqs = [
            [
                'q' => 'Apakah pengecekan kerusakan laptop/PC dikenakan biaya?',
                'a' => '100% GRATIS biaya pengecekan dan diagnosa awal! Teknisi kami akan menginformasikan penyebab kerusakan dan estimasi biaya terlebih dahulu sebelum tindakan perbaikan dilakukan.',
            ],
            [
                'q' => 'Apakah tersedia sparepart laptop second copotan original selain sparepart baru?',
                'a' => 'Tersedia lengkap! Anda bisa memilih sparepart Baru Bergaransi Resmi maupun Sparepart Second Copotan Original (RAM, SSD, LCD, Baterai, Charger, Heatsink, Motherboard, VGA) yang lebih hemat dan 100% presisi.',
            ],
        ];

        $seo = SeoService::generateMeta([
            'title' => 'Service Laptop & PC Bergaransi + Jual Sparepart Baru & Second Ori - Cyber Gaming Store',
            'description' => 'Jasa service laptop gaming & kerja (mati total, overheat, ganti LCD, engsel patah, upgrade RAM/SSD) bisa ditunggu + pusat sparepart laptop baru & second copotan original. Cek status service online!',
            'keywords' => 'service laptop terdekat bergaransi, service laptop gaming, jual sparepart laptop bekas original, ganti lcd laptop, upgrade ram ssd laptop',
            'canonical' => route('service.index'),
            'schemas' => [
                SeoService::serviceSchema(
                    'Pusat Service Laptop, PC & Sparepart Original — Cyber Gaming Store',
                    'Jasa perbaikan laptop mati total, deep cleaning thermal paste, ganti layar LCD 144Hz/165Hz, dan upgrade RAM/SSD NVMe bergaransi.',
                    route('service.index')
                ),
                SeoService::itemListSchema($spareparts, 'Katalog Sparepart Laptop & PC Original'),
                SeoService::faqSchema($serviceFaqs),
                SeoService::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Service & Sparepart', 'url' => route('service.index')],
                ]),
            ],
        ]);

        return view('storefront.service', compact(
            'settings',
            'services',
            'spareparts',
            'trackedTicket',
            'ticketQuery',
            'phoneLast4',
            'ticketError',
            'serviceFaqs',
            'seo'
        ));
    }

    public function articlesIndex(): View
    {
        $settings = Setting::allKeyed();
        $articles = Article::query()
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        $seo = SeoService::generateMeta([
            'title' => 'Blog & Panduan Tips Laptop Gaming, PC Rakitan & Teknologi - Cyber Gaming Store',
            'description' => 'Artikel panduan membeli laptop second berkualitas, rekomendasi laptop paling laris sesuai budget, tips perawatan laptop gaming, dan solusi sewa laptop B2B.',
            'canonical' => route('articles.index'),
        ]);

        return view('storefront.articles', compact('settings', 'articles', 'seo'));
    }

    public function articleShow(string $slug): View
    {
        $settings = Setting::allKeyed();
        $article = Article::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $article->increment('views_count');

        $featuredProducts = Product::query()
            ->where('is_active', true)
            ->where('is_best_seller_week', true)
            ->take(3)
            ->get();

        $seo = SeoService::generateMeta([
            'title' => $article->meta_title ?: "{$article->title} - Cyber Gaming Store",
            'description' => $article->meta_description ?: $article->excerpt,
            'keywords' => $article->meta_keywords ?: 'tips laptop gaming, cyber gaming store',
            'canonical' => route('articles.show', $article->slug),
            'og_image' => $article->thumbnailUrl(),
            'og_type' => 'article',
            'schemas' => [
                SeoService::articleSchema($article),
                SeoService::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Blog & Panduan SEO', 'url' => route('articles.index')],
                    ['name' => $article->title, 'url' => route('articles.show', $article->slug)],
                ]),
            ],
        ]);

        return view('storefront.article-detail', compact('settings', 'article', 'featuredProducts', 'seo'));
    }

    public function sitemap(): Response
    {
        $categories = Category::query()->where('is_active', true)->get();
        $products = Product::query()->where('is_active', true)->latest('updated_at')->get();
        $articles = Article::query()->where('is_published', true)->latest('updated_at')->get();

        $content = view('storefront.sitemap', compact('categories', 'products', 'articles'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/*',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
