<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalPackage;
use App\Services\MediaUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RentalPackageController extends Controller
{
    public function index(): View
    {
        $packages = RentalPackage::query()->orderBy('sort_order')->get();

        return view('admin.rentals.index', compact('packages'));
    }

    public function store(Request $request, MediaUploadService $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_audience' => ['required', 'string', 'max:255'],
            'brand_model' => ['required', 'string', 'max:255'],
            'processor' => ['required', 'string', 'max:255'],
            'ram' => ['required', 'string', 'max:100'],
            'storage' => ['required', 'string', 'max:100'],
            'gpu' => ['required', 'string', 'max:150'],
            'display' => ['required', 'string', 'max:150'],
            'price_daily' => ['required', 'numeric', 'min:0'],
            'price_weekly' => ['required', 'numeric', 'min:0'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'available_units' => ['required', 'integer', 'min:1'],
            'thumbnail_url' => ['nullable', 'string', 'max:500'],
            'benefits_text' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $thumb = $uploader->handleMedia(
            $request->file('thumbnail_file'),
            $request->input('compressed_thumbnail') ?: $request->input('thumbnail_url'),
            'rentals'
        ) ?: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80';

        $benefits = array_values(array_filter(array_map('trim', explode("\n", (string) ($validated['benefits_text'] ?? '')))));
        if (empty($benefits)) {
            $benefits = [
                'Unit Siap Pakai Terinstal Windows 11 & Office',
                'Garansi Ganti Unit Cadangan 1x24 Jam',
                'Termasuk Tas Laptop, Charger Ori & Mouse Wireless',
            ];
        }

        RentalPackage::query()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.strtolower(Str::random(3)),
            'target_audience' => $validated['target_audience'],
            'brand_model' => $validated['brand_model'],
            'processor' => $validated['processor'],
            'ram' => $validated['ram'],
            'storage' => $validated['storage'],
            'gpu' => $validated['gpu'],
            'display' => $validated['display'],
            'price_daily' => (int) $validated['price_daily'],
            'price_weekly' => (int) $validated['price_weekly'],
            'price_monthly' => (int) $validated['price_monthly'],
            'available_units' => (int) $validated['available_units'],
            'thumbnail' => $thumb,
            'benefits' => $benefits,
            'description' => $validated['description'] ?? '',
            'is_popular' => $request->boolean('is_popular'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Paket Penyewaan Laptop berhasil ditambahkan!');
    }

    public function destroy(RentalPackage $rental): RedirectResponse
    {
        $rental->delete();

        return back()->with('success', 'Paket sewa berhasil dihapus.');
    }
}
