<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\MediaUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with('category')->latest();

        if ($request->filled('type')) {
            $query->where('product_type', (string) $request->input('type'));
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('sort_order')->get();
        $product = new Product([
            'product_type' => 'laptop',
            'condition' => 'second_like_new',
            'condition_label' => 'Second Like New 98%',
            'stock' => 2,
            'sold_count' => 5,
            'warranty' => 'Garansi Toko Cyber Gaming Store 3 Bulan',
            'completeness' => 'Fullset Box + Charger Original + Tas Laptop',
            'is_best_seller_week' => true,
            'is_popular_type' => false,
            'is_active' => true,
            'images' => ['', '', ''],
        ]);

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function store(Request $request, MediaUploadService $uploader): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        $galleryFiles = $request->file('image_files', []);
        $galleryUrls = $request->input('image_urls', []);
        $compressedImages = $request->input('compressed_images', []);

        $mergedUrlOrData = [];
        for ($i = 0; $i < 5; $i++) {
            $mergedUrlOrData[$i] = ! empty($compressedImages[$i])
                ? $compressedImages[$i]
                : ($galleryUrls[$i] ?? null);
        }

        $images = $uploader->handleGallery($galleryFiles, $mergedUrlOrData, [], 'products');

        $videoUrl = $request->input('video_review_url');
        if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $videoUrl = $uploader->storeUploadedFile($request->file('video_file'), 'videos');
            $validated['video_review_type'] = 'upload';
        }

        $slug = Str::slug($validated['name']);
        if (Product::query()->where('slug', $slug)->exists()) {
            $slug .= '-'.strtolower(Str::random(4));
        }

        $sku = ($validated['sku'] ?? null) ?: 'CGS-'.strtoupper(Str::random(6));

        Product::query()->create(array_merge($validated, [
            'slug' => $slug,
            'sku' => $sku,
            'thumbnail' => $images[0],
            'images' => $images,
            'video_review_url' => $videoUrl ?: 'https://www.youtube.com/embed/ScMzIvxBSi4',
            'is_best_seller_week' => $request->boolean('is_best_seller_week'),
            'is_popular_type' => $request->boolean('is_popular_type'),
            'is_flash_deal' => $request->boolean('is_flash_deal'),
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => $request->boolean('is_active', true),
        ]));

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan lengkap dengan minimal 3 gambar, video review, dan optimasi SEO!');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('sort_order')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product, MediaUploadService $uploader): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product->id);

        $galleryFiles = $request->file('image_files', []);
        $galleryUrls = $request->input('image_urls', []);
        $compressedImages = $request->input('compressed_images', []);

        $mergedUrlOrData = [];
        for ($i = 0; $i < 5; $i++) {
            $mergedUrlOrData[$i] = ! empty($compressedImages[$i])
                ? $compressedImages[$i]
                : ($galleryUrls[$i] ?? null);
        }

        $existingImages = is_array($product->images) ? $product->images : [];
        $images = $uploader->handleGallery($galleryFiles, $mergedUrlOrData, $existingImages, 'products');

        $videoUrl = $request->input('video_review_url', $product->video_review_url);
        if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $videoUrl = $uploader->storeUploadedFile($request->file('video_file'), 'videos');
            $validated['video_review_type'] = 'upload';
        }

        $product->update(array_merge($validated, [
            'thumbnail' => $images[0],
            'images' => $images,
            'video_review_url' => $videoUrl,
            'is_best_seller_week' => $request->boolean('is_best_seller_week'),
            'is_popular_type' => $request->boolean('is_popular_type'),
            'is_flash_deal' => $request->boolean('is_flash_deal'),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    public function toggleBadge(Request $request, Product $product): RedirectResponse
    {
        $field = (string) $request->input('field', 'is_best_seller_week');
        if (in_array($field, ['is_best_seller_week', 'is_popular_type', 'is_flash_deal', 'is_active'], true)) {
            $product->update([
                $field => ! $product->{$field},
            ]);
        }

        return back()->with('success', "Status label pada '{$product->name}' berhasil diubah!");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'product_type' => ['required', 'in:laptop,pc,sparepart'],
            'condition' => ['required', 'in:second_like_new,second_good,new'],
            'condition_label' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sold_count' => ['nullable', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:600'],
            'description' => ['required', 'string'],
            'processor' => ['nullable', 'string', 'max:255'],
            'gpu' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'storage' => ['nullable', 'string', 'max:255'],
            'display' => ['nullable', 'string', 'max:255'],
            'os' => ['nullable', 'string', 'max:255'],
            'battery_health' => ['nullable', 'string', 'max:255'],
            'warranty' => ['nullable', 'string', 'max:255'],
            'completeness' => ['nullable', 'string', 'max:255'],
            'video_review_url' => ['nullable', 'string', 'max:500'],
            'video_review_type' => ['nullable', 'string', 'max:50'],
            'video_review_title' => ['nullable', 'string', 'max:255'],
            'popular_badge' => ['nullable', 'string', 'max:120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
