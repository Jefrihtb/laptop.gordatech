<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('admin')->after('email');
            $table->string('phone')->nullable()->after('role');
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('laptop')->index();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('product_type')->default('laptop')->index();
            $table->string('condition')->default('second_like_new')->index();
            $table->string('condition_label')->default('Second Like New 98%');
            $table->string('brand')->index();
            $table->unsignedBigInteger('price')->index();
            $table->unsignedBigInteger('original_price')->nullable();
            $table->integer('stock')->default(1);
            $table->integer('sold_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->string('processor')->nullable();
            $table->string('gpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('display')->nullable();
            $table->string('os')->nullable();
            $table->string('battery_health')->nullable();
            $table->string('warranty')->nullable();
            $table->string('completeness')->nullable();
            $table->json('extra_specs')->nullable();
            $table->string('thumbnail');
            $table->json('images');
            $table->string('video_review_url')->nullable();
            $table->string('video_review_type')->default('youtube');
            $table->string('video_review_title')->nullable();
            $table->boolean('is_best_seller_week')->default(false)->index();
            $table->boolean('is_popular_type')->default(false)->index();
            $table->string('popular_badge')->nullable();
            $table->boolean('is_flash_deal')->default(false)->index();
            $table->boolean('is_featured')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->decimal('rating', 3, 2)->default(4.95);
            $table->integer('review_count')->default(15);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        });

        Schema::create('rental_packages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('target_audience');
            $table->string('brand_model');
            $table->string('processor');
            $table->string('ram');
            $table->string('storage');
            $table->string('gpu');
            $table->string('display');
            $table->unsignedBigInteger('price_daily');
            $table->unsignedBigInteger('price_weekly');
            $table->unsignedBigInteger('price_monthly');
            $table->integer('min_rental_days')->default(1);
            $table->integer('available_units')->default(25);
            $table->string('thumbnail');
            $table->json('images')->nullable();
            $table->string('video_review_url')->nullable();
            $table->json('benefits');
            $table->text('description')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_catalogs', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('hardware');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('estimated_price_start');
            $table->unsignedBigInteger('estimated_price_end')->nullable();
            $table->string('estimated_duration');
            $table->string('warranty_info');
            $table->json('symptoms')->nullable();
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_tickets', function (Blueprint $table): void {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('device_type')->default('laptop');
            $table->string('device_brand_model');
            $table->string('serial_number')->nullable();
            $table->text('problem_description');
            $table->text('diagnosis_notes')->nullable();
            $table->string('status')->default('received')->index();
            $table->unsignedBigInteger('estimated_cost')->default(0);
            $table->unsignedBigInteger('final_cost')->nullable();
            $table->string('technician_name')->default('Tim Teknisi Cyber Gaming');
            $table->date('warranty_until')->nullable();
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('subtitle');
            $table->string('badge_text')->nullable();
            $table->string('highlight_text')->nullable();
            $table->string('image_url');
            $table->string('video_url')->nullable();
            $table->string('cta_primary_text')->default('Lihat Katalog Promo');
            $table->string('cta_primary_url')->default('#katalog');
            $table->string('cta_secondary_text')->default('Konsultasi Gratis via WA');
            $table->string('cta_secondary_url')->nullable();
            $table->string('accent_color')->default('blue');
            $table->string('position')->default('hero');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_role');
            $table->string('customer_city');
            $table->string('service_type')->default('beli_laptop');
            $table->string('item_purchased');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('comment');
            $table->string('avatar_url')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Panduan & Tips');
            $table->text('excerpt');
            $table->longText('content');
            $table->string('thumbnail');
            $table->string('author_name')->default('Tim Pakar Cyber Gaming Store');
            $table->integer('read_time_minutes')->default(5);
            $table->integer('views_count')->default(120);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('lead_code')->unique();
            $table->string('lead_type')->index();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('subject');
            $table->json('items_summary')->nullable();
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->text('whatsapp_message');
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('service_tickets');
        Schema::dropIfExists('service_catalogs');
        Schema::dropIfExists('rental_packages');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'phone']);
        });
    }
};
