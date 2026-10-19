<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RentalPackageController as AdminRentalController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

// Storefront & SEO Public Routes
Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/katalog', [StorefrontController::class, 'catalog'])->name('catalog.index');
Route::get('/produk/{slug}', [StorefrontController::class, 'productShow'])->name('product.show');
Route::get('/sewa-laptop', [StorefrontController::class, 'rentalIndex'])->name('rental.index');
Route::get('/service-dan-sparepart', [StorefrontController::class, 'serviceAndSparepart'])->name('service.index');
Route::get('/artikel', [StorefrontController::class, 'articlesIndex'])->name('articles.index');
Route::get('/artikel/{slug}', [StorefrontController::class, 'articleShow'])->name('articles.show');

// Conversion Lead Tracking -> WhatsApp Redirect (0818880497)
Route::post('/lead/whatsapp', [LeadController::class, 'store'])->name('lead.store');

// Dynamic SEO Sitemap & Robots.txt
Route::get('/sitemap.xml', [StorefrontController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [StorefrontController::class, 'robots'])->name('robots');

// Admin Authentication
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function (): void {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/export-sql', [AdminDashboardController::class, 'exportSql'])->name('export.sql');

        // Products (Laptop, PC, Sparepart)
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::post('/products/{product}/toggle', [AdminProductController::class, 'toggleBadge'])->name('products.toggle');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Laptop Rental Packages
        Route::get('/rentals', [AdminRentalController::class, 'index'])->name('rentals.index');
        Route::post('/rentals', [AdminRentalController::class, 'store'])->name('rentals.store');
        Route::delete('/rentals/{rental}', [AdminRentalController::class, 'destroy'])->name('rentals.destroy');

        // Services & Repair Tickets
        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [AdminServiceController::class, 'storeService'])->name('services.store');
        Route::delete('/services/{service}', [AdminServiceController::class, 'destroyService'])->name('services.destroy');
        Route::post('/service-tickets', [AdminServiceController::class, 'storeTicket'])->name('tickets.store');
        Route::put('/service-tickets/{ticket}', [AdminServiceController::class, 'updateTicketStatus'])->name('tickets.update');

        // Homepage Hero Banners
        Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
        Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
        Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

        // SEO Articles / Blog
        Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
        Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
        Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');

        // Store & SEO Settings + Testimonials
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/testimonials', [AdminSettingController::class, 'storeTestimonial'])->name('testimonials.store');
        Route::delete('/testimonials/{testimonial}', [AdminSettingController::class, 'destroyTestimonial'])->name('testimonials.destroy');
    });
});
