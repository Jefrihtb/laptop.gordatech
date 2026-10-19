@extends('layouts.admin')

@section('title', 'Banner Slide Beranda')
@section('header', 'Kelola Banner Slide Promo di Halaman Beranda')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Add New Banner -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-zinc-200 p-6">
            <div class="border-b border-zinc-100 pb-3 mb-4">
                <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Tambah Slide</span>
                <h2 class="text-sm font-semibold text-zinc-950 mt-0.5">Tambah Banner Slide Baru</h2>
            </div>

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Label Kecil Atas</label>
                    <input type="text" name="badge_text" value="READY STOCK · GARANSI RESMI & TOKO"
                           class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Judul Utama Promo</label>
                    <input type="text" name="title" required placeholder="Contoh: Laptop Gaming & Kerja Second Rasa Baru, Lulus Cek 35 Titik"
                           class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Penjelasan Singkat</label>
                    <textarea name="subtitle" rows="3" required placeholder=" Dilengkapi minimal 3 foto asli dan 1 video review sebelum dikirim..."
                              class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Teks Keunggulan Bawah</label>
                    <input type="text" name="highlight_text" value="Bisa Cek di Toko atau Video Call WA · Kirim Aman ke Seluruh Indonesia"
                           class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>

                <div class="p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 space-y-2">
                    <label class="block font-medium text-zinc-800">Foto Banner (Kompres Otomatis atau Pakai Link URL)</label>
                    <input type="file" name="image_file" accept="image/*"
                           onchange="window.compressImageForSharedHosting(this, 'compressed_banner_img', 'preview_banner', 'status_banner')"
                           class="w-full text-xs file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                    <input type="hidden" name="compressed_image" id="compressed_banner_img">
                    <span id="status_banner" class="hidden"></span>
                    <img id="preview_banner" class="hidden w-full h-32 object-cover rounded-lg border border-zinc-200">
                    <input type="text" name="image_url" placeholder="Atau tempel link URL gambar (https://...)"
                           class="w-full px-3 py-1.5 font-mono bg-white border border-zinc-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Teks Tombol Utama</label>
                        <input type="text" name="cta_primary_text" value="Lihat Katalog Promo" required
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Link Tujuan Tombol</label>
                        <input type="text" name="cta_primary_url" value="#best-seller" required
                               class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="pt-1 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                        Simpan Banner Slide
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Existing Banners -->
        <div class="lg:col-span-7 space-y-4">
            @foreach($banners as $banner)
                <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden flex flex-col sm:flex-row">
                    <img src="{{ $banner->resolvedImageUrl() }}" alt="{{ $banner->title }}" class="w-full sm:w-48 h-40 object-cover shrink-0 border-b sm:border-b-0 sm:border-r border-zinc-200">
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">{{ $banner->badge_text }}</span>
                            <h3 class="text-sm font-semibold text-zinc-950 mt-1">{{ $banner->title }}</h3>
                            <p class="text-xs text-zinc-500 mt-1 line-clamp-2">{{ $banner->subtitle }}</p>
                        </div>
                        <div class="mt-3 pt-3 border-t border-zinc-100 flex items-center justify-between">
                            <span class="text-xs font-mono text-zinc-600">Tombol: {{ $banner->cta_primary_text }}</span>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Hapus slide banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 rounded-md border border-red-200 bg-red-50 text-red-700 text-xs font-medium hover:bg-red-100 transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
