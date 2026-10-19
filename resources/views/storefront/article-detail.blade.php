@extends('layouts.storefront')

@section('content')
    {{-- BREADCRUMB BAR --}}
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between text-xs text-slate-500">
            <nav class="flex items-center gap-2 truncate">
                <a href="{{ route('home') }}" class="hover:text-blue-600 font-medium">Cyber Gaming Store</a>
                <span>/</span>
                <a href="{{ route('articles.index') }}" class="hover:text-blue-600 font-medium">Tips &amp; Artikel</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold truncate">{{ $article->title }}</span>
            </nav>
            <a href="{{ route('articles.index') }}" class="text-blue-600 font-semibold hover:underline shrink-0 ml-4">&larr; Semua Artikel</a>
        </div>
    </div>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            {{-- LEFT 8 COLS: MAIN EDITORIAL ARTICLE --}}
            <article class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 p-6 sm:p-10 shadow-xs">
                <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500">
                    <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-semibold">{{ $article->category }}</span>
                    <span>&middot;</span>
                    <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                    <span>&middot;</span>
                    <span>{{ $article->read_time_minutes ?? 5 }} menit baca</span>
                    <span>&middot;</span>
                    <span>Oleh <strong class="text-slate-800">{{ $article->author_name }}</strong></span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-slate-950 mt-4 leading-snug">
                    {{ $article->title }}
                </h1>

                @if($article->thumbnail)
                    <div class="aspect-[16/9] rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 my-6">
                        <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                {{-- Key Takeaway Box --}}
                @if($article->excerpt)
                    <div class="p-4 sm:p-5 rounded-xl bg-blue-50/70 border border-blue-200/80 mb-6">
                        <span class="block text-xs text-blue-700 font-bold mb-1">Intisari Panduan</span>
                        <p class="text-xs sm:text-sm text-slate-800 font-medium leading-relaxed">{{ $article->excerpt }}</p>
                    </div>
                @endif

                {{-- Well-Spaced Article Paragraphs --}}
                <div class="space-y-4 text-sm sm:text-base text-slate-700 leading-relaxed">
                    @foreach(array_filter(explode("\n", (string) $article->content)) as $paragraph)
                        <p>{{ trim($paragraph) }}</p>
                    @endforeach
                </div>

                {{-- In-Article CTA Box --}}
                <div class="mt-10 p-6 sm:p-8 rounded-2xl bg-slate-950 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div>
                        <span class="text-xs text-blue-400 font-semibold">Konsultasi Gratis</span>
                        <h2 class="text-lg font-bold text-white mt-1">Masih Bingung Memilih Tipe Laptop yang Cocok?</h2>
                        <p class="text-xs text-slate-300 mt-1.5 leading-relaxed">Konsultasikan kebutuhan kerja atau gaming Anda bersama tim kami untuk mendapatkan rekomendasi terbaik.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                        <button type="button" @click="openConsultModal = true"
                                class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-sm">
                            Tanya via WhatsApp
                        </button>
                        <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-700 text-slate-200 hover:text-white text-xs font-semibold transition">
                            Lokasi Toko
                        </a>
                    </div>
                </div>
            </article>

            {{-- RIGHT 4 COLS: STICKY CONVERSION SIDEBAR --}}
            <aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <span class="text-xs text-blue-600 font-semibold block">Rekomendasi Cepat</span>
                    <h2 class="text-lg font-bold text-slate-950 leading-snug">Cari Laptop Sesuai Budget Anda?</h2>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Beritahu kami budget dan pemakaian Anda. Tim kami akan mengirimkan daftar stok terbaik lengkap dengan foto dan video tesnya lewat WhatsApp.
                    </p>
                    <div class="space-y-2.5 pt-1">
                        <button type="button" @click="openConsultModal = true"
                                class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-sm">
                            Minta Rekomendasi via WA
                        </button>
                        <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                           target="_blank" rel="noopener"
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:border-slate-900 text-slate-800 text-xs font-semibold transition">
                            Kunjungi Toko (Google Maps)
                        </a>
                    </div>
                </div>

                @if($featuredProducts->isNotEmpty())
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-950">Unit Rekomendasi Terlaris</h3>
                            <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Semua &rarr;</a>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($featuredProducts as $prod)
                                <a href="{{ route('product.show', $prod->slug) }}" class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-3.5 group">
                                    <img src="{{ $prod->thumbnailUrl() }}" alt="{{ $prod->name }}" class="w-16 h-14 rounded-lg object-cover shrink-0 bg-slate-100 border border-slate-200">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition leading-snug">{{ $prod->name }}</p>
                                        <p class="text-xs font-bold text-blue-600 mt-1">{{ $prod->formattedPrice() }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
