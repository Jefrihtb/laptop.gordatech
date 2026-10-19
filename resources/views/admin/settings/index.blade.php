@extends('layouts.admin')

@section('title', 'Pengaturan Toko & SEO')
@section('header', 'Pengaturan Toko, Lokasi Google Maps, WhatsApp & SEO Google')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left 7 Cols: Store Identity, Maps & SEO Settings -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-zinc-200 p-6">
            <div class="border-b border-zinc-100 pb-3 mb-5">
                <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Profil Toko &amp; SEO</span>
                <h2 class="text-sm font-semibold text-zinc-950 mt-0.5">Informasi Toko, Link Google Maps &amp; SEO Utama</h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Nama Toko / Website</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Cyber Gaming Store' }}"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg font-semibold focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-zinc-900 mb-1">Nomor WhatsApp Utama</label>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '0818880497' }}"
                               class="w-full px-3 py-2 border border-blue-600 rounded-lg font-mono font-semibold text-zinc-950 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Kota Utama (Untuk SEO Lokal)</label>
                        <input type="text" name="main_city_name" value="{{ $settings['main_city_name'] ?? 'Jakarta' }}"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Wilayah Layanan Pengiriman</label>
                        <input type="text" name="target_city" value="{{ $settings['target_city'] ?? 'Jakarta & Pengiriman Aman ke Seluruh Indonesia' }}"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Alamat / Keterangan Toko Fisik</label>
                        <input type="text" name="store_address" value="{{ $settings['store_address'] ?? '' }}"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-blue-700 mb-1">Link Lokasi Toko (Google Maps)</label>
                        <input type="url" name="maps_url" value="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                               placeholder="https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA"
                               class="w-full px-3 py-2 border border-blue-300 bg-blue-50/30 rounded-lg font-mono text-zinc-900 focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-zinc-700 mb-1">Teks Promo Baris Atas Website</label>
                    <input type="text" name="promo_top_bar" value="{{ $settings['promo_top_bar'] ?? '' }}"
                           class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>

                <div class="pt-4 border-t border-zinc-100 space-y-3">
                    <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600 block">Pengaturan SEO Google</span>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Judul Utama di Google (Meta Title)</label>
                        <input type="text" name="seo_default_title" value="{{ $settings['seo_default_title'] ?? '' }}"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Deskripsi Toko di Google (Meta Description)</label>
                        <textarea name="seo_default_description" rows="3" class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">{{ $settings['seo_default_description'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Kata Kunci Pencarian (Meta Keywords)</label>
                        <textarea name="seo_default_keywords" rows="2" class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">{{ $settings['seo_default_keywords'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Kode Verifikasi Google Search Console (Opsional)</label>
                        <input type="text" name="google_verification" value="{{ $settings['google_verification'] ?? '' }}"
                               placeholder="Kosongkan jika belum ada"
                               class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                        Simpan Pengaturan Toko
                    </button>
                </div>
            </form>
        </div>

        <!-- Right 5 Cols: Customer Testimonials Management -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-xl border border-zinc-200 p-6">
                <div class="border-b border-zinc-100 pb-3 mb-4">
                    <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Testimoni Pembeli</span>
                    <h3 class="text-sm font-semibold text-zinc-950 mt-0.5">Tambah Testimoni Pelanggan</h3>
                </div>

                <form action="{{ route('admin.testimonials.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" required placeholder="Nama Lengkap"
                                   class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Kota</label>
                            <input type="text" name="customer_city" required placeholder="Jakarta / Bandung"
                                   class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Pekerjaan / Status</label>
                            <input type="text" name="customer_role" required placeholder="Karyawan / Mahasiswa / Gamer"
                                   class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Kategori Layanan</label>
                            <select name="service_type" class="w-full px-3 py-2 border border-zinc-300 rounded-lg bg-white focus:border-blue-600 focus:outline-none">
                                <option value="beli_laptop">Beli Laptop</option>
                                <option value="beli_pc">Beli PC Rakitan</option>
                                <option value="sewa_laptop">Sewa Laptop</option>
                                <option value="service_sparepart">Service &amp; Sparepart</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Produk / Layanan yang Dibeli</label>
                        <input type="text" name="item_purchased" required placeholder="Contoh: ASUS ROG Strix G15 RTX 3060"
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Isi Testimoni</label>
                        <textarea name="comment" rows="3" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none"></textarea>
                    </div>
                    <div class="pt-1 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                            Tambah Testimoni
                        </button>
                    </div>
                </form>

                <div class="mt-6 space-y-2.5">
                    @foreach($testimonials as $testi)
                        <div class="p-3.5 rounded-lg bg-zinc-50 border border-zinc-200 flex items-start justify-between gap-3 text-xs">
                            <div>
                                <span class="font-semibold text-zinc-950">{{ $testi->customer_name }} ({{ $testi->customer_city }})</span>
                                <span class="block text-[11px] font-mono text-blue-600 mt-0.5">{{ $testi->item_purchased }}</span>
                                <p class="text-zinc-600 mt-1 line-clamp-2">"{{ $testi->comment }}"</p>
                            </div>
                            <form action="{{ route('admin.testimonials.destroy', $testi) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-mono text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
