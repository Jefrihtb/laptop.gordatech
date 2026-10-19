@extends('layouts.admin')

@section('title', 'Paket Sewa Laptop')
@section('header', 'Kelola Paket Sewa Laptop (Harian, Mingguan & Bulanan)')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Add New Rental Package -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-zinc-200 p-6">
            <div class="border-b border-zinc-100 pb-3 mb-4">
                <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Tambah Paket</span>
                <h2 class="text-sm font-semibold text-zinc-950 mt-0.5">Tambah Paket Sewa Baru</h2>
            </div>

            <form action="{{ route('admin.rentals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Nama Paket Sewa</label>
                    <input type="text" name="name" required placeholder="Contoh: Paket Sewa Kantor Core i7 RAM 16GB"
                           class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Tipe Laptop</label>
                        <input type="text" name="brand_model" required placeholder="ThinkPad T14 / ROG Strix"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Cocok Untuk</label>
                        <input type="text" name="target_audience" required placeholder="Kantor, Pelatihan, Kampus"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Prosesor</label>
                        <input type="text" name="processor" required placeholder="Intel Core i7 Gen 11"
                               class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">RAM</label>
                        <input type="text" name="ram" required placeholder="16GB DDR4"
                               class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                </div>
                <input type="hidden" name="storage" value="512GB SSD NVMe">
                <input type="hidden" name="gpu" value="Intel Iris Xe / NVIDIA RTX">
                <input type="hidden" name="display" value="14.0 / 15.6 Inch FHD IPS">

                <div class="grid grid-cols-3 gap-2.5">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Harga/Hari (Rp)</label>
                        <input type="number" name="price_daily" required value="75000"
                               class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Harga/Minggu</label>
                        <input type="number" name="price_weekly" required value="380000"
                               class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-blue-700 mb-1">Harga/Bulan</label>
                        <input type="number" name="price_monthly" required value="850000"
                               class="w-full px-3 py-2 font-mono font-semibold border border-blue-600 rounded-lg focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Jumlah Unit Tersedia</label>
                    <input type="number" name="available_units" required value="50"
                           class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>

                <div class="p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 space-y-2">
                    <label class="block font-medium text-zinc-700">Foto Unit (Kompres Otomatis atau Pakai Link URL)</label>
                    <input type="file" name="thumbnail_file" accept="image/*"
                           onchange="window.compressImageForSharedHosting(this, 'compressed_rental_thumb', 'preview_rental', 'status_rental')"
                           class="w-full text-xs file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                    <input type="hidden" name="compressed_thumbnail" id="compressed_rental_thumb">
                    <span id="status_rental" class="hidden"></span>
                    <img id="preview_rental" class="hidden w-full h-28 object-cover rounded-lg border border-zinc-200">
                    <input type="text" name="thumbnail_url" placeholder="Atau tempel link URL gambar (https://...)"
                           class="w-full px-3 py-1.5 font-mono bg-white border border-zinc-300 rounded-lg">
                </div>

                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Fasilitas Paket (1 baris 1 poin)</label>
                    <textarea name="benefits_text" rows="3" placeholder="Sudah terinstal Windows & Office&#10;Jaminan ganti unit cadangan 1x24 jam&#10;Termasuk tas & mouse wireless"
                              class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none"></textarea>
                </div>

                <label class="flex items-center gap-2 font-medium text-zinc-700">
                    <input type="checkbox" name="is_popular" value="1" checked class="rounded text-blue-600">
                    <span>Tandai sebagai Paket Paling Laris</span>
                </label>

                <div class="pt-1 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                        Simpan Paket Sewa
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Existing Rental Packages -->
        <div class="lg:col-span-7 space-y-4">
            @foreach($packages as $pkg)
                <div class="bg-white rounded-xl border border-zinc-200 p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $pkg->thumbnailUrl() }}" alt="{{ $pkg->name }}" class="w-20 h-20 rounded-lg object-cover shrink-0 border border-zinc-200">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-zinc-950">{{ $pkg->name }}</h3>
                                @if($pkg->is_popular)
                                    <span class="px-2 py-0.5 rounded bg-blue-600 text-white font-mono text-[10px]">Paling Laris</span>
                                @endif
                            </div>
                            <p class="text-xs font-mono text-zinc-500 mt-0.5">{{ $pkg->brand_model }} &middot; Tersedia {{ $pkg->available_units }} Unit</p>
                            <p class="text-xs font-mono text-zinc-700 mt-1.5">
                                Harian: <strong>{{ $pkg->formattedDaily() }}</strong> &middot;
                                Mingguan: <strong>{{ $pkg->formattedWeekly() }}</strong> &middot;
                                Bulanan: <strong class="text-blue-600">{{ $pkg->formattedMonthly() }}</strong>
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('admin.rentals.destroy', $pkg) }}" method="POST" onsubmit="return confirm('Hapus paket sewa ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-md border border-red-200 bg-red-50 text-red-700 text-xs font-medium hover:bg-red-100 transition">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
