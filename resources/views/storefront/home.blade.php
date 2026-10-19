@extends('layouts.storefront')

@section('content')
    @php
        $slidesData = $banners->map(fn ($b) => [
            'badge' => $b->badge_text ?: 'Garansi Resmi & Toko',
            'title' => $b->title,
            'subtitle' => $b->subtitle,
            'image' => $b->resolvedImageUrl(),
            'cta_text' => $b->cta_primary_text ?: 'Lihat Katalog',
            'cta_url' => $b->cta_primary_url ?: route('catalog.index'),
        ])->values();
    @endphp

    {{-- MINIMALIST & PERSUASIVE HERO --}}
    <section class="bg-slate-950 text-white border-b border-slate-900"
             x-data="{
                activeSlide: 0,
                slides: {{ Js::from($slidesData) }},
                init() {
                    if (this.slides.length > 1) {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                        }, 7000);
                    }
                }
             }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-7 space-y-5">
                    <span class="inline-block px-3.5 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs font-medium text-blue-400"
                          x-text="slides[activeSlide]?.badge">
                        {{ $banners->first()->badge_text ?? 'READY STOCK · GARANSI RESMI & TOKO' }}
                    </span>

                    <h1 class="text-2xl sm:text-4xl font-bold text-white leading-snug"
                        x-text="slides[activeSlide]?.title">
                        {{ $banners->first()->title ?? 'Laptop Gaming & Kerja Second Rasa Baru, Sudah Lulus Cek 35 Titik' }}
                    </h1>

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl"
                       x-text="slides[activeSlide]?.subtitle">
                        {{ $banners->first()->subtitle ?? 'Pilihan laptop second mulus Grade A+, laptop baru segel resmi, dan PC rakitan siap pakai.' }}
                    </p>

                    <div class="pt-1 flex flex-wrap items-center gap-3">
                        <a :href="slides[activeSlide]?.cta_url || '{{ route('catalog.index') }}'"
                           href="{{ $banners->first()->cta_primary_url ?? route('catalog.index') }}"
                           class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs sm:text-sm transition">
                            <span x-text="slides[activeSlide]?.cta_text || 'Lihat Katalog Promo'">{{ $banners->first()->cta_primary_text ?? 'Lihat Katalog Promo' }}</span>
                        </a>

                        <button type="button" @click="openConsultModal = true"
                                class="px-5 py-3 rounded-xl border border-slate-700 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm transition">
                            Konsultasi Gratis
                        </button>

                        <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                           target="_blank" rel="noopener"
                           class="px-3 py-2 text-slate-300 hover:text-white text-xs font-medium underline underline-offset-4">
                            Lokasi Toko (Google Maps)
                        </a>
                    </div>

                    @if($banners->count() > 1)
                        <div class="pt-2 flex items-center gap-2">
                            @foreach($banners as $bIdx => $bItem)
                                <button type="button"
                                        @click="activeSlide = {{ $bIdx }}"
                                        :class="activeSlide === {{ $bIdx }} ? 'w-8 bg-blue-500' : 'w-2.5 bg-slate-800 hover:bg-slate-700'"
                                        class="h-1.5 rounded-full transition-all duration-300"
                                        aria-label="Slide {{ $bIdx + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-2xl overflow-hidden border border-slate-800 bg-slate-900">
                        <div class="aspect-[16/10] overflow-hidden bg-slate-900">
                            <img :src="slides[activeSlide]?.image"
                                 src="{{ $banners->first()?->resolvedImageUrl() }}"
                                 :alt="slides[activeSlide]?.title"
                                 alt="{{ $banners->first()->title ?? 'Cyber Gaming Store' }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 bg-slate-900 border-t border-slate-800 flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-300 leading-normal">Bisa cek unit langsung di toko atau Video Call WA</span>
                            <a href="{{ \App\Services\WhatsAppService::consultationUrl('umum') }}"
                               target="_blank" rel="noopener"
                               class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shrink-0">
                                Chat WA
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CLEAR VALUE PROPOSITION / TRUST BAR --}}
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold text-slate-950">Lulus Cek 35 Titik</p>
                    <p class="text-xs text-slate-500 mt-1">Mesin, layar, suhu, dan baterai sudah diuji stabil.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold text-slate-950">3 Foto &amp; Video Review Asli</p>
                    <p class="text-xs text-slate-500 mt-1">Kondisi fisik dan performa unit terlihat jelas.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold text-slate-950">Garansi Resmi &amp; Toko</p>
                    <p class="text-xs text-slate-500 mt-1">Jaminan garansi tertulis yang aman dan jelas.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold text-slate-950">Jual, Sewa &amp; Service Lengkap</p>
                    <p class="text-xs text-slate-500 mt-1">Melayani pembelian, sewa laptop, dan perbaikan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- BEST SELLER MINGGU INI --}}
    <section id="best-seller" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-semibold text-blue-600">Pilihan Paling Diminati</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-950 mt-1">Best Seller Minggu Ini</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Unit pilihan dengan spesifikasi terbaik dan kondisi siap pakai.</p>
            </div>
            <a href="{{ route('catalog.index', ['filter' => 'best_seller']) }}"
               class="text-xs font-semibold text-blue-600 hover:underline shrink-0">
                Lihat Semua Best Seller &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bestSellersWeek as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- PENJUALAN TIPE PALING LARIS BULAN INI --}}
    <section class="bg-slate-950 text-white border-y border-slate-900 py-12 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="text-xs font-semibold text-blue-400">Tren Pembelian Pelanggan</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-white mt-1">
                        Penjualan Tipe Paling Laris Bulan Ini
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        Seri laptop gaming dan kerja yang paling banyak terjual di Cyber Gaming Store.
                    </p>
                </div>
                <button type="button" @click="openConsultModal = true"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition self-start sm:self-auto shrink-0">
                    Minta Rekomendasi Gratis
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($popularTypes as $index => $popProduct)
                    <div class="p-5 rounded-2xl border border-slate-800 bg-slate-900/60 hover:border-blue-500/60 transition flex flex-col justify-between h-full">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span class="text-blue-400 font-semibold">Terlaris #0{{ $index + 1 }}</span>
                                <span>{{ $popProduct->sold_count }} unit terjual</span>
                            </div>
                            <h3 class="text-base font-bold text-white leading-snug">
                                <a href="{{ route('product.show', $popProduct->slug) }}" class="hover:text-blue-400 transition">
                                    {{ $popProduct->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-400 truncate">
                                {{ collect([$popProduct->processor, $popProduct->gpu, $popProduct->ram])->filter()->implode(' · ') }}
                            </p>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-800 flex items-center justify-between gap-3">
                            <span class="text-base font-bold text-white">{{ $popProduct->formattedPrice() }}</span>
                            <a href="{{ route('product.show', $popProduct->slug) }}"
                               class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-blue-600 text-xs font-semibold text-white transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- KATALOG LAPTOP & PC RAKITAN --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-semibold text-blue-600">Stok Siap Kirim</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-950 mt-1">Katalog Laptop &amp; PC Rakitan</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Tersedia pilihan unit second rasa baru dan unit baru garansi resmi.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-blue-600 hover:underline shrink-0">
                Semua Katalog &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($laptopsAndPcs->take(6) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- SEWA LAPTOP & PUSAT SERVICE --}}
    <section class="bg-white border-y border-slate-200 py-12 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left Card: Sewa Laptop --}}
            <div class="p-6 sm:p-8 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-semibold text-blue-600">Rental Perusahaan &amp; Personal</span>
                    <h2 class="text-xl font-bold text-slate-950 mt-1.5">Sewa Laptop Harian, Mingguan &amp; Bulanan</h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Tersedia ratusan unit ThinkPad, ASUS ROG, dan MacBook siap pakai untuk kantor, pelatihan, maupun event dengan jaminan unit cadangan 1x24 jam.
                    </p>
                    <div class="mt-5 space-y-2.5">
                        @foreach($rentalPackages->take(3) as $pkg)
                            <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                                <span class="font-semibold text-slate-900">{{ $pkg->name }}</span>
                                <span class="font-bold text-blue-600 shrink-0">Mulai {{ $pkg->formattedDaily() }}/hr</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('rental.index') }}"
                       class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                        Lihat Paket &amp; Kalkulator Sewa
                    </a>
                </div>
            </div>

            {{-- Right Card: Service & Sparepart --}}
            <div class="p-6 sm:p-8 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-semibold text-blue-600">Gratis Cek Kerusakan</span>
                    <h2 class="text-xl font-bold text-slate-950 mt-1.5">Pusat Service Laptop &amp; Sparepart Original</h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Terima perbaikan laptop mati total, cepat panas, ganti layar LCD, hingga upgrade RAM &amp; SSD NVMe bisa ditunggu di toko.
                    </p>
                    <div class="mt-5 space-y-2.5">
                        @foreach($services->take(3) as $srv)
                            <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                                <span class="font-semibold text-slate-900">{{ $srv->name }}</span>
                                <span class="font-bold text-blue-600 shrink-0">{{ $srv->formattedPriceRange() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-2.5">
                    <a href="{{ route('service.index') }}"
                       class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                        Info Service &amp; Cek Tiket
                    </a>
                    <a href="{{ route('catalog.index', ['type' => 'sparepart']) }}"
                       class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-800 text-xs font-semibold hover:border-blue-600 transition">
                        Katalog Sparepart
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS & STORE LOCATION --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14 space-y-12">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-950 mb-6">Ulasan Pelanggan Kami</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($testimonials as $testi)
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white flex flex-col justify-between h-full">
                        <p class="text-xs text-slate-600 leading-relaxed">"{{ $testi->comment }}"</p>
                        <div class="mt-4 pt-3.5 border-t border-slate-100">
                            <p class="text-xs font-bold text-slate-950">{{ $testi->customer_name }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $testi->customer_city }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl bg-slate-950 text-white p-6 sm:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="max-w-xl">
                <h2 class="text-lg sm:text-xl font-bold text-white">Ingin Cek Unit Langsung di Toko?</h2>
                <p class="text-xs sm:text-sm text-slate-300 mt-1.5 leading-relaxed">
                    Kunjungi toko fisik Cyber Gaming Store ({{ $settings['operating_hours'] ?? 'Senin – Minggu, 09.00 – 21.00 WIB' }}) atau konsultasi langsung lewat WhatsApp.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                   target="_blank" rel="noopener"
                   class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                    Buka Lokasi di Google Maps
                </a>
                <a href="{{ \App\Services\WhatsAppService::consultationUrl('umum') }}"
                   target="_blank" rel="noopener"
                   class="px-5 py-2.5 rounded-xl border border-slate-700 bg-slate-900 hover:border-slate-500 text-white text-xs font-semibold transition">
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </section>
@endsection
