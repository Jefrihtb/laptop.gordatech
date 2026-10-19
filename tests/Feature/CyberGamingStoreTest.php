<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CyberGamingStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_renders_cyber_gaming_store_and_seo_schemas(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Cyber Gaming Store');
        $response->assertSee('0818880497');
        $response->assertSee('https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA');
        $response->assertSee('Best Seller Minggu Ini');
        $response->assertSee('Penjualan Tipe Paling Laris Bulan Ini', false);
        $response->assertDontSee('Tukar Tambah');
        $response->assertDontSee('Trade-In');
        $response->assertDontSee('Laboratorium');
        $response->assertDontSee('laboratorium');
        $response->assertSee('application/ld+json', false);
        $response->assertSee('ComputerStore', false);
        $response->assertSee('ItemList', false);
        $response->assertSee('FAQPage', false);
    }

    public function test_catalog_and_product_detail_have_min_three_images_and_video_review(): void
    {
        $products = Product::query()->get();
        $this->assertGreaterThanOrEqual(12, $products->count());

        foreach ($products as $prod) {
            $this->assertGreaterThanOrEqual(3, count($prod->galleryUrls()));
            $this->assertNotEmpty($prod->embedVideoUrl());
            $this->assertStringContainsString('w=1200&q=85', $prod->thumbnailUrl());
        }

        $product = $products->first();

        $catalogResponse = $this->get(route('catalog.index', ['type' => 'laptop']));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee($product->name);
        $catalogResponse->assertSee('https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA');
        $catalogResponse->assertSee('ItemList', false);
        $catalogResponse->assertDontSee('Laboratorium');

        $detailResponse = $this->get(route('product.show', $product->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($product->name);
        $detailResponse->assertSee('62818880497');
        $detailResponse->assertSee('https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA');
        $detailResponse->assertSee('Video Review');
        $detailResponse->assertSee('VideoObject', false);
        $detailResponse->assertDontSee('Tukar Tambah');
        $detailResponse->assertDontSee('Trade-In');
        $detailResponse->assertDontSee('Laboratorium');
    }

    public function test_rental_service_and_articles_pages_render_professionally(): void
    {
        $rentalResponse = $this->get(route('rental.index'));
        $rentalResponse->assertStatus(200);
        $rentalResponse->assertSee('Kalkulator &amp; Pesan Sewa Laptop', false);
        $rentalResponse->assertSee('Tanya Jawab Seputar Penyewaan Laptop', false);
        $rentalResponse->assertSee('https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA');
        $rentalResponse->assertSee('Service', false);
        $rentalResponse->assertDontSee('Laboratorium');

        // Without WhatsApp last-4 digits, ticket data must NOT be exposed
        $unverifiedTicketResponse = $this->get(route('service.index', ['ticket' => 'CGS-SRV-2601']));
        $unverifiedTicketResponse->assertStatus(200);
        $unverifiedTicketResponse->assertSee('Masukkan Nomor Tiket beserta 4 digit terakhir nomor WhatsApp');
        $unverifiedTicketResponse->assertDontSee('ASUS ROG Zephyrus G14 GA401');

        // With wrong WhatsApp last-4 digits, ticket data must NOT be exposed
        $wrongPhoneResponse = $this->get(route('service.index', [
            'ticket' => 'CGS-SRV-2601',
            'phone_last4' => '9999',
        ]));
        $wrongPhoneResponse->assertStatus(200);
        $wrongPhoneResponse->assertSee('Data service tidak ditemukan atau 4 digit terakhir nomor WhatsApp tidak sesuai');
        $wrongPhoneResponse->assertDontSee('ASUS ROG Zephyrus G14 GA401');

        // With valid ticket AND matching last-4 WhatsApp digits (7711), status is shown with masked personal info
        $serviceResponse = $this->get(route('service.index', [
            'ticket' => 'CGS-SRV-2601',
            'phone_last4' => '7711',
        ]));
        $serviceResponse->assertStatus(200);
        $serviceResponse->assertSee('CGS-SRV-2601');
        $serviceResponse->assertSee('ASUS ROG Zephyrus G14 GA401');
        $serviceResponse->assertSee('Rizky P***');
        $serviceResponse->assertSee('****-****-7711');
        $serviceResponse->assertDontSee('081299887711');
        $serviceResponse->assertSee('Katalog Sparepart Baru &amp; Copotan Original', false);
        $serviceResponse->assertSee('https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA');
        $serviceResponse->assertDontSee('Laboratorium');

        $articlesResponse = $this->get(route('articles.index'));
        $articlesResponse->assertStatus(200);
        $articlesResponse->assertSee('Tips, Panduan &amp; Rekomendasi Laptop', false);
        $articlesResponse->assertSee('https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA');
    }

    public function test_whatsapp_lead_conversion_records_lead_and_returns_wa_url(): void
    {
        $response = $this->postJson(route('lead.store'), [
            'lead_type' => 'cart_checkout',
            'customer_name' => 'Budi Gamer',
            'customer_city' => 'Jakarta',
            'subject' => 'Checkout Keranjang WA (1 Item)',
            'total_amount' => 13850000,
            'whatsapp_message' => 'Halo Admin Cyber Gaming Store, saya ingin beli ROG Strix G15.',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'lead_code', 'whatsapp_url']);

        $this->assertStringContainsString('https://wa.me/62818880497', $response->json('whatsapp_url'));
        $this->assertDatabaseHas('leads', [
            'customer_name' => 'Budi Gamer',
            'lead_type' => 'cart_checkout',
        ]);
    }

    public function test_dynamic_sitemap_and_robots_txt_are_valid(): void
    {
        $sitemap = $this->get(route('sitemap'));
        $sitemap->assertStatus(200);
        $sitemap->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $sitemap->assertSee('<urlset', false);

        $robots = $this->get(route('robots'));
        $robots->assertStatus(200);
        $robots->assertSee('Sitemap:');
    }

    public function test_admin_can_create_product_with_lightweight_compressed_image_and_url(): void
    {
        $admin = User::query()->where('email', 'admin@cybergamingstore.id')->firstOrFail();
        $category = Category::query()->firstOrFail();

        $tinyPngBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'MSI Katana 15 B13VFK Core i7-13620H RTX 4060 8GB',
            'product_type' => 'laptop',
            'condition' => 'second_like_new',
            'condition_label' => 'Second Like New 99%',
            'brand' => 'MSI Gaming',
            'price' => 14900000,
            'original_price' => 17500000,
            'stock' => 2,
            'sold_count' => 9,
            'description' => 'Laptop gaming MSI Katana RTX 4060 mulus bergaransi toko.',
            'compressed_images' => [
                0 => $tinyPngBase64,
            ],
            'image_urls' => [
                1 => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=1200&q=85',
                2 => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=1200&q=85',
            ],
            'video_review_url' => 'https://youtu.be/ScMzIvxBSi4',
            'video_review_title' => 'Review MSI Katana 15 RTX 4060',
            'is_best_seller_week' => '1',
            'is_popular_type' => '1',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $created = Product::query()->where('brand', 'MSI Gaming')->first();
        $this->assertNotNull($created);
        $this->assertGreaterThanOrEqual(3, count($created->galleryUrls()));
        $this->assertStringStartsWith('uploads/products/', $created->thumbnail);
        $this->assertSame('https://www.youtube.com/embed/ScMzIvxBSi4', $created->embedVideoUrl());
    }

    public function test_all_admin_pages_render_cleanly_without_internal_demo_guides(): void
    {
        $loginRes = $this->get(route('admin.login'));
        $loginRes->assertStatus(200);
        $loginRes->assertDontSee('Isi Akun Default');
        $loginRes->assertDontSee('password123');
        $loginRes->assertSee('noindex, nofollow', false);

        $admin = User::query()->where('email', 'admin@cybergamingstore.id')->firstOrFail();

        $routes = [
            route('admin.dashboard'),
            route('admin.products.index'),
            route('admin.products.create'),
            route('admin.rentals.index'),
            route('admin.services.index'),
            route('admin.banners.index'),
            route('admin.articles.index'),
            route('admin.settings.index'),
        ];

        foreach ($routes as $url) {
            $res = $this->actingAs($admin)->get($url);
            $res->assertStatus(200);
            $res->assertDontSee('Isi Akun Default');
            $res->assertDontSee('Siap Pakai di Shared Hosting');
        }
    }
}
