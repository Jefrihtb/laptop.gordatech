<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::allKeyed();
        $testimonials = Testimonial::query()->latest()->get();

        return view('admin.settings.index', compact('settings', 'testimonials'));
    }

    public function update(Request $request): RedirectResponse
    {
        $allowedKeys = [
            'site_name',
            'site_tagline',
            'whatsapp_number',
            'target_city',
            'main_city_name',
            'store_address',
            'maps_url',
            'operating_hours',
            'store_email',
            'instagram_url',
            'tiktok_url',
            'youtube_url',
            'seo_default_title',
            'seo_default_description',
            'seo_default_keywords',
            'google_verification',
            'promo_top_bar',
        ];

        foreach ($allowedKeys as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, (string) $request->input($key), str_starts_with($key, 'seo_') ? 'seo' : 'general');
            }
        }

        return back()->with('success', 'Pengaturan Toko, Nomor WhatsApp, dan Konfigurasi SEO No. 1 Google berhasil disimpan!');
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_role' => ['required', 'string', 'max:120'],
            'customer_city' => ['required', 'string', 'max:100'],
            'service_type' => ['required', 'string', 'max:50'],
            'item_purchased' => ['required', 'string', 'max:200'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        Testimonial::query()->create(array_merge($validated, [
            'rating' => 5,
            'is_featured' => true,
        ]));

        return back()->with('success', 'Testimoni pelanggan berhasil ditambahkan!');
    }

    public function destroyTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('success', 'Testimoni dihapus.');
    }
}
