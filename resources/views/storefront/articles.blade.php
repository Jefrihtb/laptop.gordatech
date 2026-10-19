@extends('layouts.storefront')

@section('content')
    <section class="bg-slate-950 text-white border-b border-slate-900 py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white leading-snug">
                    Tips, Panduan &amp; Rekomendasi Laptop
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1.5 leading-relaxed">
                    Panduan memilih laptop second berkualitas dan rekomendasi tipe sesuai kebutuhan Anda.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" @click="openConsultModal = true"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                    Konsultasi Gratis
                </button>
                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                   target="_blank" rel="noopener"
                   class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 hover:border-slate-500 text-slate-200 text-xs font-semibold transition">
                    Lokasi Toko
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($articles as $article)
                <article class="group rounded-2xl border border-slate-200 bg-white overflow-hidden flex flex-col justify-between hover:border-blue-600/60 transition shadow-xs h-full">
                    <div>
                        <a href="{{ route('articles.show', $article->slug) }}"
                           class="block aspect-[16/10] bg-slate-100 overflow-hidden border-b border-slate-100">
                            <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500">
                        </a>
                        <div class="p-5 space-y-2.5">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span class="text-blue-600 font-semibold">{{ $article->category }}</span>
                                <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                            </div>
                            <h2 class="text-base font-bold text-slate-950 group-hover:text-blue-600 transition leading-snug">
                                <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                            </h2>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $article->excerpt }}</p>
                        </div>
                    </div>
                    <div class="px-5 pb-5">
                        <a href="{{ route('articles.show', $article->slug) }}"
                           class="block w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-blue-600 text-slate-800 hover:text-white border border-slate-200 hover:border-blue-600 text-xs font-semibold transition text-center">
                            Baca Selengkapnya
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $articles->links() }}
        </div>
    </section>
@endsection
