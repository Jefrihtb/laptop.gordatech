@extends('layouts.admin')

@section('title', 'Ringkasan Toko')
@section('header', 'Ringkasan Penjualan, Stok & Pesanan Masuk via WhatsApp')

@section('content')
    <!-- Top Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl border border-zinc-200">
            <span class="text-xs font-medium text-zinc-500">Total Produk Aktif</span>
            <p class="text-2xl font-bold text-zinc-950 mt-1.5">{{ $stats['total_products'] }}</p>
            <span class="text-xs text-zinc-400 mt-1 block">Laptop, PC &amp; Sparepart</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200">
            <span class="text-xs font-medium text-blue-600">Best Seller Minggu Ini</span>
            <p class="text-2xl font-bold text-zinc-950 mt-1.5">{{ $stats['best_sellers_week'] }}</p>
            <span class="text-xs text-zinc-400 mt-1 block">{{ $stats['popular_types'] }} Tipe Paling Laris</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200">
            <span class="text-xs font-medium text-zinc-500">Paket Sewa Laptop</span>
            <p class="text-2xl font-bold text-zinc-950 mt-1.5">{{ $stats['rental_packages'] }}</p>
            <span class="text-xs text-zinc-400 mt-1 block">Harian, Mingguan &amp; Bulanan</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200">
            <span class="text-xs font-medium text-zinc-500">Tiket Service Aktif</span>
            <p class="text-2xl font-bold text-zinc-950 mt-1.5">{{ $stats['active_service_tickets'] }}</p>
            <span class="text-xs text-zinc-400 mt-1 block">{{ $stats['services_count'] }} Layanan Tersedia</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200">
            <span class="text-xs font-medium text-emerald-600">Pesanan &amp; Konsultasi WA</span>
            <p class="text-2xl font-bold text-zinc-950 mt-1.5">{{ $stats['total_leads'] }}</p>
            <span class="text-xs text-zinc-400 mt-1 block">WA 0818880497</span>
        </div>
    </div>

    <!-- Quick Store Operations Bar -->
    <div class="mb-8 p-5 rounded-xl bg-white border border-zinc-200 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-sm font-bold text-zinc-950">Pintasan Operasional Toko</h2>
            <p class="text-xs text-zinc-500 mt-0.5">Kelola katalog produk, pantau tiket service pelanggan, dan perbarui pengaturan SEO Google.</p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                + Tambah Produk
            </a>
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-zinc-300 bg-white hover:border-blue-600 text-zinc-800 text-xs font-semibold transition">
                Update Tiket Service
            </a>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-zinc-300 bg-white hover:border-blue-600 text-zinc-800 text-xs font-semibold transition">
                Pengaturan SEO &amp; Toko
            </a>
            <a href="{{ route('sitemap') }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 text-xs font-medium transition">
                Sitemap XML
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent WhatsApp Leads -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-zinc-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <span class="text-xs font-semibold text-blue-600">Daftar Pesanan</span>
                    <h3 class="text-sm font-bold text-zinc-950 mt-0.5">Pesanan &amp; Konsultasi Masuk via WhatsApp</h3>
                </div>
                <span class="text-xs font-medium text-zinc-500">0818880497</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-zinc-50 text-zinc-500 font-semibold border-y border-zinc-200">
                        <tr>
                            <th class="py-2.5 px-3">Kode</th>
                            <th class="py-2.5 px-3">Pelanggan</th>
                            <th class="py-2.5 px-3">Kategori</th>
                            <th class="py-2.5 px-3">Rincian Pesanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($recentLeads as $lead)
                            <tr>
                                <td class="py-3 px-3 font-semibold text-zinc-900">
                                    {{ $lead->lead_code }}
                                    <span class="block text-[11px] text-zinc-400 font-normal">{{ $lead->created_at?->diffForHumans() }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-medium text-zinc-900 block">{{ $lead->customer_name }}</span>
                                    <span class="text-[11px] text-zinc-400">{{ $lead->customer_city }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2.5 py-0.5 rounded bg-blue-50 text-blue-700 font-medium text-[11px]">{{ $lead->typeLabel() }}</span>
                                </td>
                                <td class="py-3 px-3 text-zinc-700">
                                    {{ $lead->subject }}
                                    @if($lead->total_amount > 0)
                                        <span class="block font-bold text-zinc-950 mt-0.5">Rp {{ number_format((float) $lead->total_amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products Quick Toggle -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-zinc-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <span class="text-xs font-semibold text-blue-600">Stok Unggulan</span>
                    <h3 class="text-sm font-bold text-zinc-950 mt-0.5">Produk Paling Laris</h3>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @foreach($topProducts as $prod)
                    <div class="p-3 rounded-lg bg-zinc-50 border border-zinc-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $prod->thumbnailUrl() }}" alt="{{ $prod->name }}" class="w-11 h-11 rounded-lg object-cover shrink-0 border border-zinc-200">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-zinc-950 truncate">{{ $prod->name }}</p>
                                <p class="text-[11px] text-zinc-500 mt-0.5">{{ $prod->formattedPrice() }} &middot; {{ $prod->sold_count }} terjual</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $prod) }}" class="px-3 py-1.5 rounded-md bg-white border border-zinc-300 text-xs font-medium text-zinc-800 hover:border-blue-600 hover:text-blue-600 shrink-0 transition">
                            Edit
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
