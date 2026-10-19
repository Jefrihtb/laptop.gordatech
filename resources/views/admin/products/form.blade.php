@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk Baru')
@section('header', $product->exists ? 'Edit Produk: '.$product->name : 'Tambah Produk Baru ke Katalog')

@section('content')
    @php
        $existingImages = is_array($product->images) ? $product->images : [];
        for ($i = 0; $i < 4; $i++) {
            if (!isset($existingImages[$i])) {
                $existingImages[$i] = '';
            }
        }
    @endphp

    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-8"
          x-data="{
            prodName: '{{ addslashes(old('name', $product->name)) }}',
            metaTitle: '{{ addslashes(old('meta_title', $product->meta_title)) }}',
            metaDesc: '{{ addslashes(old('meta_description', $product->meta_description)) }}'
          }">
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left 8 Cols: Core Details, Specs & Media -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4">
                    <div class="border-b border-zinc-100 pb-3">
                        <span class="text-xs font-semibold text-blue-600">Informasi Utama</span>
                        <h2 class="text-sm font-bold text-zinc-950 mt-0.5">Identitas &amp; Harga Produk</h2>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" x-model="prodName" value="{{ old('name', $product->name) }}" required
                               placeholder="Contoh: ASUS ROG Strix G15 G513RM"
                               class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Kategori Katalog</label>
                            <select name="category_id" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg bg-white">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (int) old('category_id', $product->category_id) === $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Jenis Perangkat</label>
                            <select name="product_type" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg bg-white">
                                <option value="laptop" {{ old('product_type', $product->product_type) === 'laptop' ? 'selected' : '' }}>Laptop Second / Baru</option>
                                <option value="pc" {{ old('product_type', $product->product_type) === 'pc' ? 'selected' : '' }}>PC Gaming / Workstation</option>
                                <option value="sparepart" {{ old('product_type', $product->product_type) === 'sparepart' ? 'selected' : '' }}>Sparepart Baru / Second</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Brand / Merk</label>
                            <input type="text" name="brand" value="{{ old('brand', $product->brand ?: 'ASUS ROG') }}" required
                                   placeholder="ASUS ROG / Lenovo / Custom PC"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Status Kondisi</label>
                            <select name="condition" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg bg-white">
                                <option value="second_like_new" {{ old('condition', $product->condition) === 'second_like_new' ? 'selected' : '' }}>Second Like New</option>
                                <option value="second_good" {{ old('condition', $product->condition) === 'second_good' ? 'selected' : '' }}>Second Bergaransi</option>
                                <option value="new" {{ old('condition', $product->condition) === 'new' ? 'selected' : '' }}>Baru (Segel Resmi)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Label Kondisi</label>
                            <input type="text" name="condition_label" value="{{ old('condition_label', $product->condition_label ?: 'Second Like New 98%') }}" required
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Kode SKU (Opsional)</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Otomatis jika kosong"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-900 mb-1">Harga Jual (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $product->price ?: 9500000) }}" required
                                   class="w-full px-3 py-2 text-xs font-semibold border border-zinc-900 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Harga Normal (Rp)</label>
                            <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}"
                                   placeholder="11500000"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Stok Tersedia</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 2) }}" required
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Unit Terjual</label>
                            <input type="number" name="sold_count" value="{{ old('sold_count', $product->sold_count ?? 12) }}"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Media Upload Card: Min 3 Photos + 1 Video Review -->
                <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-5">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-zinc-100 pb-3">
                        <div>
                            <span class="text-xs font-semibold text-blue-600">Galeri Visual Produk</span>
                            <h2 class="text-sm font-bold text-zinc-950 mt-0.5">
                                Foto Produk (Minimal 3 Foto) &amp; 1 Video Review
                            </h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @for($i = 0; $i < 4; $i++)
                            <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-200 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-zinc-900">
                                        Foto Produk #0{{ $i + 1 }} {!! $i < 3 ? '<span class="text-zinc-500 text-[11px]">(Wajib)</span>' : '<span class="text-zinc-400 text-[11px]">(Opsional)</span>' !!}
                                    </span>
                                    @if(!empty($existingImages[$i]))
                                        <span class="text-[11px] font-medium text-emerald-600">Tersimpan</span>
                                    @endif
                                </div>

                                <img id="preview_img_{{ $i }}"
                                     src="{{ !empty($existingImages[$i]) ? \App\Models\Product::resolveMediaUrl($existingImages[$i]) : '' }}"
                                     alt="Preview {{ $i + 1 }}"
                                     class="{{ !empty($existingImages[$i]) ? '' : 'hidden' }} w-full h-32 object-cover rounded-lg border border-zinc-200 bg-white">

                                <div>
                                    <label class="block text-[11px] font-medium text-zinc-600 mb-1">Unggah File Foto</label>
                                    <input type="file" name="image_files[{{ $i }}]" accept="image/*"
                                           onchange="window.compressImageForSharedHosting(this, 'compressed_img_{{ $i }}', 'preview_img_{{ $i }}', 'status_img_{{ $i }}')"
                                           class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-zinc-950 file:text-white hover:file:bg-zinc-800">
                                    <input type="hidden" name="compressed_images[{{ $i }}]" id="compressed_img_{{ $i }}">
                                    <span id="status_img_{{ $i }}" class="hidden"></span>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-medium text-zinc-600 mb-1">Atau Link URL Gambar</label>
                                    <input type="text" name="image_urls[{{ $i }}]" value="{{ old("image_urls.$i", $existingImages[$i] ?? '') }}"
                                           placeholder="https://..."
                                           class="w-full px-3 py-1.5 text-xs bg-white border border-zinc-300 rounded-lg">
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- 1 Video Review Section -->
                    <div class="p-4 rounded-xl bg-zinc-950 text-white space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold">Video Review Produk (1 Video)</span>
                            <span class="text-[11px] text-zinc-400">YouTube / MP4</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-zinc-400 mb-1">Judul Video Review</label>
                                <input type="text" name="video_review_title" value="{{ old('video_review_title', $product->video_review_title ?: 'Video Review & Tes Performa Unit') }}"
                                       class="w-full px-3 py-2 text-xs bg-zinc-900 border border-zinc-800 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-zinc-400 mb-1">Link URL Video (YouTube / MP4)</label>
                                <input type="text" name="video_review_url" value="{{ old('video_review_url', $product->video_review_url ?: 'https://www.youtube.com/embed/ScMzIvxBSi4') }}"
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       class="w-full px-3 py-2 text-xs bg-zinc-900 border border-zinc-800 rounded-lg text-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] text-zinc-400 mb-1">Atau Unggah File Video MP4 (Opsional):</label>
                            <input type="file" name="video_file" accept="video/mp4,video/webm"
                                   class="text-xs text-zinc-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-white file:text-zinc-950">
                        </div>
                    </div>
                </div>

                <!-- Technical Specs & Description Card -->
                <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4">
                    <div class="border-b border-zinc-100 pb-3">
                        <span class="text-xs font-semibold text-blue-600">Detail Teknis</span>
                        <h2 class="text-sm font-bold text-zinc-950 mt-0.5">Spesifikasi Lengkap &amp; Kondisi Unit</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Prosesor (CPU)</label>
                            <input type="text" name="processor" value="{{ old('processor', $product->processor) }}" placeholder="AMD Ryzen 7 6800H / Intel Core i7..."
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Kartu Grafis (VGA)</label>
                            <input type="text" name="gpu" value="{{ old('gpu', $product->gpu) }}" placeholder="NVIDIA RTX 3060 6GB..."
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Memori (RAM)</label>
                            <input type="text" name="ram" value="{{ old('ram', $product->ram) }}" placeholder="16GB DDR5"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Penyimpanan (SSD)</label>
                            <input type="text" name="storage" value="{{ old('storage', $product->storage) }}" placeholder="512GB SSD NVMe"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Layar / Monitor</label>
                            <input type="text" name="display" value="{{ old('display', $product->display) }}" placeholder="15.6 FHD IPS 165Hz 100% sRGB"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Kondisi Baterai / Power</label>
                            <input type="text" name="battery_health" value="{{ old('battery_health', $product->battery_health) }}" placeholder="94% Normal Awet"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Garansi</label>
                            <input type="text" name="warranty" value="{{ old('warranty', $product->warranty) }}"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-700 mb-1">Kelengkapan Unit</label>
                            <input type="text" name="completeness" value="{{ old('completeness', $product->completeness) }}"
                                   class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Ringkasan Singkat Produk</label>
                        <textarea name="short_description" rows="2" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Deskripsi Lengkap &amp; Hasil Pengecekan 35 Titik</label>
                        <textarea name="description" rows="5" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right 4 Cols: Marketing Conversion Flags & Live Google SEO Preview -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Marketing Badges Card -->
                <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4">
                    <div class="border-b border-zinc-100 pb-3">
                        <span class="text-xs font-semibold text-blue-600">Penempatan Katalog</span>
                        <h2 class="text-sm font-bold text-zinc-950 mt-0.5">Tampilan Promo di Beranda</h2>
                    </div>

                    <label class="flex items-start gap-3 p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 cursor-pointer">
                        <input type="checkbox" name="is_best_seller_week" value="1" {{ old('is_best_seller_week', $product->is_best_seller_week) ? 'checked' : '' }} class="mt-0.5 rounded text-blue-600">
                        <div>
                            <span class="block text-xs font-semibold text-zinc-950">Best Seller Minggu Ini</span>
                            <span class="block text-[11px] text-zinc-500 mt-0.5">Tampilkan pada bagian Best Seller Minggu Ini di Beranda.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 cursor-pointer">
                        <input type="checkbox" name="is_popular_type" value="1" {{ old('is_popular_type', $product->is_popular_type) ? 'checked' : '' }} class="mt-0.5 rounded text-blue-600">
                        <div>
                            <span class="block text-xs font-semibold text-zinc-950">Tipe Paling Laris</span>
                            <span class="block text-[11px] text-zinc-500 mt-0.5">Masukkan ke daftar Penjualan Tipe Paling Laris Bulan Ini.</span>
                        </div>
                    </label>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Label Keunggulan Produk</label>
                        <input type="text" name="popular_badge" value="{{ old('popular_badge', $product->popular_badge) }}"
                               placeholder="Contoh: Terlaris Kelas 9 Jutaan"
                               class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                    </div>

                    <label class="flex items-center gap-2 text-xs font-medium text-zinc-700 pt-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="rounded text-blue-600">
                        <span>Tampilkan di Katalog &amp; Google Sitemap</span>
                    </label>
                </div>

                <!-- SEO Optimizer + SERP Preview -->
                <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-blue-600">Optimasi Mesin Pencari</span>
                        <h2 class="text-sm font-bold text-zinc-950 mt-0.5">Pengaturan SEO Google</h2>
                    </div>

                    <div class="p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 space-y-1">
                        <span class="text-[11px] text-zinc-400 block">Pratinjau di Google</span>
                        <p class="text-[11px] text-emerald-700 truncate">{{ url('/produk') }}/... </p>
                        <p class="text-xs font-semibold text-blue-700 line-clamp-1" x-text="metaTitle || ('Jual ' + (prodName || 'Laptop / PC') + ' Bergaransi — Cyber Gaming Store')"></p>
                        <p class="text-[11px] text-zinc-600 line-clamp-2" x-text="metaDesc || ('Spesifikasi lengkap dan hasil cek 35 titik untuk ' + (prodName || 'unit ini') + ' di Cyber Gaming Store. Chat WA 0818880497.')"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Judul Meta (Meta Title)</label>
                        <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title', $product->meta_title) }}"
                               placeholder="Otomatis terisi jika dikosongkan"
                               class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Deskripsi Meta (Meta Description)</label>
                        <textarea name="meta_description" x-model="metaDesc" rows="2" placeholder="Otomatis terisi jika dikosongkan"
                                  class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Kata Kunci Pencarian (Meta Keywords)</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}"
                               placeholder="jual rog strix second, laptop rtx 3060, cyber gaming store"
                               class="w-full px-3 py-2 text-xs border border-zinc-300 rounded-lg">
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2.5">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 rounded-lg border border-zinc-300 text-zinc-700 hover:bg-zinc-100 text-xs font-semibold transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                            Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
