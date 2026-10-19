@extends('layouts.storefront')

@section('content')
    <section class="bg-slate-950 text-white border-b border-slate-900 py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white leading-snug">
                    @if($activeCategory)
                        {{ $activeCategory->name }}
                    @elseif(request('filter') === 'best_seller')
                        Best Seller Minggu Ini
                    @elseif(request('condition') === 'second_like_new')
                        Katalog Laptop &amp; PC Second Rasa Baru
                    @elseif(request('condition') === 'new')
                        Katalog Unit Baru
                    @else
                        Katalog Laptop, PC &amp; Sparepart
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1.5 leading-relaxed">
                    Semua unit telah lulus cek 35 titik dan bergaransi. Klik <strong>Lihat Detail</strong> untuk melihat foto lengkap, spesifikasi, dan video review.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" @click="openConsultModal = true"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-xs font-semibold text-white transition">
                    Bantu Pilih Unit
                </button>
                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                   target="_blank" rel="noopener"
                   class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 hover:border-slate-500 text-xs font-semibold text-slate-200 transition">
                    Lokasi Toko
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        {{-- SINGLE MINIMALIST FILTER BAR --}}
        <form action="{{ route('catalog.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 mb-8 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <div class="lg:col-span-4">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tipe laptop, RTX, ThinkPad..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-900 focus:outline-none focus:border-blue-600">
                </div>

                <div class="lg:col-span-3">
                    <select name="category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:border-blue-600">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <select name="condition" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:border-blue-600">
                        <option value="">Semua Kondisi</option>
                        <option value="second_like_new" @selected(request('condition') === 'second_like_new')>Second Mulus</option>
                        <option value="new" @selected(request('condition') === 'new')>Baru Resmi</option>
                    </select>
                </div>

                <div class="lg:col-span-3 flex gap-2">
                    <select name="sort" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:border-blue-600">
                        <option value="best_seller" @selected(request('sort') === 'best_seller')>Terlaris</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga Terendah</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga Tertinggi</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Terbaru</option>
                    </select>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shrink-0">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'condition', 'brand', 'sort', 'type', 'filter']))
                        <a href="{{ route('catalog.index') }}" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-950 text-xs font-medium shrink-0">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- MINIMALIST PRODUCT GRID --}}
        @if($products->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-md mx-auto my-6">
                <h2 class="text-base font-bold text-slate-950">Tipe yang Dicari Belum Tampil?</h2>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                    Chat tim kami untuk cek stok gudang terbaru yang siap dikirim hari ini.
                </p>
                <div class="mt-5 flex justify-center gap-2.5">
                    <a href="{{ route('catalog.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700">
                        Semua Unit
                    </a>
                    <button type="button" @click="openConsultModal = true" class="px-4 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-semibold">
                        Tanya via WA
                    </button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </section>
@endsection
