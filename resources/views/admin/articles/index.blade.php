@extends('layouts.admin')

@section('title', 'Tips & Artikel SEO')
@section('header', 'Kelola Tips Beli Laptop & Artikel SEO Google')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-5 bg-white rounded-xl border border-zinc-200 p-6">
            <div class="border-b border-zinc-100 pb-3 mb-4">
                <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Artikel Baru</span>
                <h2 class="text-sm font-semibold text-zinc-950 mt-0.5">Tulis Tips / Artikel Baru</h2>
            </div>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Judul Artikel</label>
                    <input type="text" name="title" required placeholder="Contoh: 7 Tips Membeli Laptop Gaming Second Berkualitas"
                           class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Kategori Artikel</label>
                    <input type="text" name="category" required value="Tips Beli Laptop"
                           class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Ringkasan Singkat (Tampil di Google)</label>
                    <textarea name="excerpt" rows="2" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Isi Lengkap Artikel</label>
                    <textarea name="content" rows="5" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none"></textarea>
                </div>
                <div class="p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 space-y-2">
                    <label class="block font-medium text-zinc-700">Foto Artikel (Kompres Otomatis atau Pakai Link URL)</label>
                    <input type="file" name="thumbnail_file" accept="image/*"
                           onchange="window.compressImageForSharedHosting(this, 'compressed_art_thumb', 'preview_art', 'status_art')"
                           class="w-full text-xs file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                    <input type="hidden" name="compressed_thumbnail" id="compressed_art_thumb">
                    <span id="status_art" class="hidden"></span>
                    <img id="preview_art" class="hidden w-full h-28 object-cover rounded-lg border border-zinc-200">
                    <input type="text" name="thumbnail_url" placeholder="Atau tempel link URL gambar (https://...)"
                           class="w-full px-3 py-1.5 font-mono bg-white border border-zinc-300 rounded-lg">
                </div>
                <div class="pt-1 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                        Terbitkan Artikel
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-7 space-y-4">
            @foreach($articles as $art)
                <div class="bg-white rounded-xl border border-zinc-200 p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <img src="{{ $art->thumbnailUrl() }}" alt="{{ $art->title }}" class="w-20 h-16 rounded-lg object-cover shrink-0 border border-zinc-200">
                        <div class="min-w-0">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-blue-600">{{ $art->category }} &middot; {{ $art->views_count }} pembaca</span>
                            <h3 class="text-sm font-semibold text-zinc-950 truncate mt-0.5">
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="hover:text-blue-600">{{ $art->title }}</a>
                            </h3>
                            <p class="text-xs text-zinc-500 line-clamp-1 mt-0.5">{{ $art->excerpt }}</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.articles.destroy', $art) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-md border border-red-200 bg-red-50 text-red-700 text-xs font-medium shrink-0 hover:bg-red-100 transition">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
