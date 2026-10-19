@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('header', 'Kelola Katalog Laptop, PC Rakitan & Sparepart')

@section('content')
    <div class="bg-white rounded-xl border border-zinc-200 p-4 mb-6 flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk, SKU, merk..."
                   class="px-3.5 py-2 text-xs bg-white border border-zinc-300 rounded-lg focus:outline-none focus:border-blue-600 w-64">
            <select name="type" class="px-3.5 py-2 text-xs bg-white border border-zinc-300 rounded-lg focus:outline-none focus:border-blue-600">
                <option value="">Semua Kategori</option>
                <option value="laptop" {{ request('type') === 'laptop' ? 'selected' : '' }}>Laptop</option>
                <option value="pc" {{ request('type') === 'pc' ? 'selected' : '' }}>PC Rakitan</option>
                <option value="sparepart" {{ request('type') === 'sparepart' ? 'selected' : '' }}>Sparepart</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-xs">Cari</button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-xs">
            + Tambah Produk Baru
        </a>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-zinc-50 text-zinc-500 font-mono text-[10px] uppercase border-b border-zinc-200">
                    <tr>
                        <th class="py-3.5 px-4">Nama Produk &amp; Foto/Video</th>
                        <th class="py-3.5 px-4">Kategori &amp; Kondisi</th>
                        <th class="py-3.5 px-4">Harga &amp; Stok</th>
                        <th class="py-3.5 px-4">Best Seller</th>
                        <th class="py-3.5 px-4">Tipe Laris</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($products as $prod)
                        <tr class="hover:bg-zinc-50/70 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3.5">
                                    <img src="{{ $prod->thumbnailUrl() }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-lg object-cover shrink-0 border border-zinc-200">
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('product.show', $prod->slug) }}" target="_blank" class="font-semibold text-zinc-950 hover:text-blue-600 line-clamp-1">
                                            {{ $prod->name }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-1 text-[11px] font-mono text-zinc-400">
                                            <span>{{ $prod->sku }}</span>
                                            <span>&middot;</span>
                                            <span class="text-zinc-700">{{ count($prod->galleryUrls()) }} Foto</span>
                                            @if($prod->video_review_url)
                                                <span>&middot;</span>
                                                <span class="text-blue-600">1 Video</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-zinc-900 block">{{ $prod->category->name ?? '-' }}</span>
                                <span class="text-[11px] font-mono text-zinc-500">{{ $prod->condition_label }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-semibold text-zinc-950 block">{{ $prod->formattedPrice() }}</span>
                                <span class="text-[11px] font-mono text-zinc-400">Stok {{ $prod->stock }} &middot; Terjual {{ $prod->sold_count }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.products.toggle', $prod) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="field" value="is_best_seller_week">
                                    <button type="submit"
                                            class="px-3 py-1 rounded-md font-mono text-[11px] transition {{ $prod->is_best_seller_week ? 'bg-blue-600 text-white font-semibold' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200' }}">
                                        {{ $prod->is_best_seller_week ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.products.toggle', $prod) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="field" value="is_popular_type">
                                    <button type="submit"
                                            class="px-3 py-1 rounded-md font-mono text-[11px] transition {{ $prod->is_popular_type ? 'bg-blue-600 text-white font-semibold' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200' }}">
                                        {{ $prod->is_popular_type ? 'Tipe Laris' : 'Standar' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5">
                                <a href="{{ route('admin.products.edit', $prod) }}" class="inline-block px-3 py-1.5 rounded-md border border-zinc-300 bg-white text-zinc-800 font-medium hover:border-blue-600 hover:text-blue-600 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $prod) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-md border border-red-200 bg-red-50 text-red-700 font-medium hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-zinc-100">
            {{ $products->links() }}
        </div>
    </div>
@endsection
