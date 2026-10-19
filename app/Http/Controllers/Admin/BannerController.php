<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\MediaUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::query()->orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request, MediaUploadService $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:1000'],
            'badge_text' => ['nullable', 'string', 'max:150'],
            'highlight_text' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'cta_primary_text' => ['required', 'string', 'max:100'],
            'cta_primary_url' => ['required', 'string', 'max:255'],
            'cta_secondary_text' => ['nullable', 'string', 'max:100'],
            'cta_secondary_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $imagePath = $uploader->handleMedia(
            $request->file('image_file'),
            $request->input('compressed_image') ?: $request->input('image_url'),
            'banners'
        ) ?: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=1400&q=85';

        Banner::query()->create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'badge_text' => $validated['badge_text'] ?: 'FEATURED HARDWARE SHOWCASE',
            'highlight_text' => $validated['highlight_text'] ?: 'Garansi Toko & Gratis Konsultasi via WhatsApp 0818880497',
            'image_url' => $imagePath,
            'cta_primary_text' => $validated['cta_primary_text'],
            'cta_primary_url' => $validated['cta_primary_url'],
            'cta_secondary_text' => $validated['cta_secondary_text'] ?: 'Konsultasi Gratis via WA',
            'cta_secondary_url' => $validated['cta_secondary_url'],
            'sort_order' => (int) ($validated['sort_order'] ?? 1),
            'is_active' => true,
        ]);

        return back()->with('success', 'Banner Beranda berhasil ditambahkan!');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }
}
