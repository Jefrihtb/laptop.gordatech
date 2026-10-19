<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'ASUS ROG Strix G15 '.fake()->unique()->numerify('RTX####');
        $price = fake()->numberBetween(4500000, 22000000);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => 'CGS-'.strtoupper(Str::random(6)),
            'product_type' => 'laptop',
            'condition' => 'second_like_new',
            'condition_label' => 'Second Like New 98%',
            'brand' => 'ASUS ROG',
            'price' => $price,
            'original_price' => $price + 1500000,
            'stock' => 3,
            'sold_count' => 18,
            'views_count' => 240,
            'short_description' => 'Laptop gaming mulus lolos QC 35 titik siap pakai garansi toko.',
            'description' => 'Unit laptop gaming pilihan dengan kondisi sangat terawat, suhu adem, sudah repasta thermal grizzly.',
            'processor' => 'AMD Ryzen 7 6800H',
            'gpu' => 'NVIDIA GeForce RTX 3060 6GB',
            'ram' => '16GB DDR5 4800MHz',
            'storage' => '512GB SSD NVMe Gen4',
            'display' => '15.6" FHD 165Hz 100% sRGB',
            'os' => 'Windows 11 Original',
            'battery_health' => '94% Normal (3-4 Jam)',
            'warranty' => 'Garansi Cyber Gaming Store 3 Bulan',
            'completeness' => 'Fullset Box + Charger Original + Tas Gaming',
            'thumbnail' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80',
            'images' => [
                'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=900&q=80',
            ],
            'video_review_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'video_review_type' => 'youtube',
            'video_review_title' => 'Video Review Fisik 360° & Test Gaming FPS',
            'is_best_seller_week' => true,
            'is_popular_type' => true,
            'popular_badge' => 'Tipe Paling Laris Minggu Ini',
            'is_flash_deal' => false,
            'is_featured' => true,
            'is_active' => true,
            'rating' => 4.95,
            'review_count' => 24,
            'meta_title' => 'Jual '.$name.' Bergaransi - Cyber Gaming Store',
            'meta_description' => 'Beli '.$name.' kondisi Like New lulus QC 35 titik dengan harga hemat dan garansi resmi toko di Cyber Gaming Store.',
            'meta_keywords' => 'jual laptop gaming second, asus rog bekas, cyber gaming store',
        ];
    }
}
