@extends('layouts.storefront')

@section('content')
    @php
        $gallery = $product->galleryUrls();
        $embedUrl = $product->embedVideoUrl();
        $waOrderUrl = \App\Services\WhatsAppService::productDirectUrl($product, 'beli');
    @endphp

    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between text-xs text-slate-500">
            <nav class="flex items-center gap-2 truncate">
                <a href="{{ route('home') }}" class="hover:text-blue-600 font-medium">Cyber Gaming Store</a>
                <span>/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 font-medium">Katalog</a>
                @if($product->category)
                    <span>/</span>
                    <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-blue-600 font-medium">{{ $product->category->name }}</a>
                @endif
                <span>/</span>
                <span class="text-slate-900 font-semibold truncate">{{ $product->name }}</span>
            </nav>
            <span class="text-xs text-slate-400 shrink-0 hidden sm:inline ml-4">SKU: {{ $product->sku }}</span>
        </div>
    </div>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ activeMedia: 'photo-0' }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            {{-- LEFT: GALLERY (MIN 3 PHOTOS + 1 VIDEO REVIEW) --}}
            <div class="lg:col-span-7 space-y-4">
                <div class="aspect-[16/10] rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shadow-xs">
                    @foreach($gallery as $idx => $imgUrl)
                        <div x-show="activeMedia === 'photo-{{ $idx }}'" class="w-full h-full" @if($idx > 0) x-cloak @endif>
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }} - Foto {{ $idx + 1 }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach

                    @if($product->video_review_url)
                        <div x-show="activeMedia === 'video'" x-cloak class="w-full h-full bg-slate-950 flex items-center justify-center">
                            @if($product->isDirectVideoFile())
                                <video src="{{ $embedUrl }}" controls class="w-full h-full object-contain"></video>
                            @elseif($embedUrl)
                                <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- THUMBNAILS (MIN 3 PHOTOS + 1 VIDEO REVIEW) --}}
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    @foreach($gallery as $idx => $imgUrl)
                        <button type="button"
                                @click="activeMedia = 'photo-{{ $idx }}'"
                                :class="activeMedia === 'photo-{{ $idx }}' ? 'ring-2 ring-blue-600 border-blue-600' : 'border-slate-200 opacity-75 hover:opacity-100'"
                                class="aspect-[4/3] rounded-xl overflow-hidden border bg-slate-100 transition relative">
                            <img src="{{ $imgUrl }}" alt="Foto {{ $idx + 1 }}" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-slate-950/80 text-white text-[10px] font-medium">Foto {{ $idx + 1 }}</span>
                        </button>
                    @endforeach

                    @if($product->video_review_url)
                        <button type="button"
                                @click="activeMedia = 'video'"
                                :class="activeMedia === 'video' ? 'ring-2 ring-blue-600 border-blue-600' : 'border-slate-800 bg-slate-950 text-white hover:bg-slate-900'"
                                class="aspect-[4/3] rounded-xl overflow-hidden border bg-slate-950 text-white transition flex flex-col items-center justify-center p-2 text-center">
                            <span class="text-[11px] text-blue-400 font-semibold">Putar</span>
                            <span class="text-xs font-bold mt-0.5">Video Review</span>
                        </button>
                    @endif
                </div>

                {{-- SPECIFICATION DETAILS --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 mt-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-4 mb-5">
                        <div>
                            <span class="text-xs font-semibold text-blue-600">Detail Spesifikasi</span>
                            <h2 class="text-lg font-bold text-slate-950 mt-0.5">Spesifikasi Lengkap &amp; Kondisi Unit</h2>
                        </div>
                        <span class="text-xs text-slate-500">{{ count($gallery) }} Foto Asli &middot; 1 Video Review</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        @if($product->processor)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="block text-slate-400">Prosesor (CPU)</span>
                                <span class="font-bold text-slate-900 mt-1 block">{{ $product->processor }}</span>
                            </div>
                        @endif
                        @if($product->gpu)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="block text-slate-400">Kartu Grafis (VGA)</span>
                                <span class="font-bold text-slate-900 mt-1 block">{{ $product->gpu }}</span>
                            </div>
                        @endif
                        @if($product->ram)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="block text-slate-400">Memori (RAM)</span>
                                <span class="font-bold text-slate-900 mt-1 block">{{ $product->ram }}</span>
                            </div>
                        @endif
                        @if($product->storage)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="block text-slate-400">Penyimpanan (SSD)</span>
                                <span class="font-bold text-slate-900 mt-1 block">{{ $product->storage }}</span>
                            </div>
                        @endif
                        @if($product->display)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="block text-slate-400">Layar</span>
                                <span class="font-bold text-slate-900 mt-1 block">{{ $product->display }}</span>
                            </div>
                        @endif
                        @if($product->battery_health)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="block text-slate-400">Kondisi Baterai / Power</span>
                                <span class="font-bold text-slate-900 mt-1 block">{{ $product->battery_health }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-200">
                        <h3 class="text-xs font-semibold text-blue-600 mb-2">Hasil Pengecekan 35 Titik &amp; Penjelasan Unit</h3>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: CONVERSION & WHATSAPP ORDER BOX --}}
            <div class="lg:col-span-5 lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 space-y-5 shadow-xs">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold">
                                {{ $product->condition_label }}
                            </span>
                            @if($product->is_best_seller_week)
                                <span class="px-2.5 py-1 rounded-md bg-blue-600 text-white text-xs font-semibold">
                                    Best Seller
                                </span>
                            @endif
                        </div>

                        <h1 class="text-xl sm:text-2xl font-bold text-slate-950 leading-snug">
                            {{ $product->name }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-1.5">
                            {{ $product->brand }} &middot; SKU {{ $product->sku }} &middot; Terjual {{ $product->sold_count }} unit
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="block text-xs text-slate-500">Harga Promo Terbaik</span>
                        <div class="flex items-baseline gap-3 mt-1">
                            <span class="text-2xl sm:text-3xl font-bold text-slate-950">{{ $product->formattedPrice() }}</span>
                            @if($product->formattedOriginalPrice())
                                <span class="text-xs text-slate-400 line-through">{{ $product->formattedOriginalPrice() }}</span>
                            @endif
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-200/80 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Stok Tersedia</span>
                            <span class="font-bold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $product->stock > 0 ? 'Siap Kirim (' . $product->stock . ' Unit)' : 'Stok Habis' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs text-slate-600">
                        <div class="flex items-start justify-between gap-4 py-2 border-b border-slate-100">
                            <span class="text-slate-400 shrink-0">Jaminan Garansi</span>
                            <span class="font-semibold text-slate-900 text-right">{{ $product->warranty ?? 'Garansi Toko Resmi' }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-2 border-b border-slate-100">
                            <span class="text-slate-400 shrink-0">Kelengkapan</span>
                            <span class="font-semibold text-slate-900 text-right">{{ $product->completeness ?? 'Unit + Charger Original + Tas' }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-2 border-b border-slate-100">
                            <span class="text-slate-400 shrink-0">Cek Kondisi</span>
                            <span class="font-semibold text-slate-900 text-right">Bisa Cek di Toko / Video Call WA</span>
                        </div>
                    </div>

                    {{-- Proportional, Elegant, Eye-Catching Buttons --}}
                    <div class="pt-1 flex flex-wrap items-center gap-2.5">
                        <a href="{{ $waOrderUrl }}" target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center py-3 px-5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-sm">
                            Beli via WhatsApp
                        </a>

                        <button type="button"
                                @click="addToCart({
                                    id: {{ $product->id }},
                                    name: '{{ addslashes($product->name) }}',
                                    sku: '{{ addslashes($product->sku) }}',
                                    price: {{ (int) $product->price }},
                                    condition: '{{ addslashes($product->condition_label) }}',
                                    image: '{{ $product->thumbnailUrl() }}'
                                })"
                                class="inline-flex items-center justify-center py-3 px-4 rounded-xl border border-slate-300 bg-white hover:border-blue-600 hover:text-blue-600 text-slate-800 font-semibold text-xs transition">
                            + Keranjang
                        </button>

                        <button type="button"
                                @click="openConsultModal = true"
                                class="inline-flex items-center justify-center py-3 px-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition">
                            Tanya Admin
                        </button>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                        <span class="text-slate-500">Ingin coba unit langsung di toko?</span>
                        <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                           target="_blank" rel="noopener"
                           class="font-semibold text-blue-600 hover:text-blue-700 underline">
                            Buka Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- RELATED PRODUCTS --}}
        @if($relatedProducts->isNotEmpty())
            <div class="mt-14 pt-12 border-t border-slate-200">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-semibold text-blue-600">Pilihan Serupa</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-950 mt-1">Rekomendasi Unit Lainnya</h2>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-800 hover:border-blue-600 hover:text-blue-600 transition">
                        Lihat Semua Katalog
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedProducts->take(3) as $rel)
                        @include('partials.product-card', ['product' => $rel])
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
