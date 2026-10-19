<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Primary SEO Meta Tags -->
    <title>{{ $seo['title'] ?? 'Cyber Gaming Store — Jual Laptop Gaming Second & Baru, PC Rakitan, Sewa Laptop & Service Bergaransi' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? '' }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' }}">
    <meta name="author" content="{{ $settings['site_name'] ?? 'Cyber Gaming Store' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">

    <!-- Local SEO Geo Meta -->
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="{{ $settings['main_city_name'] ?? 'Jakarta' }}">

    @if(!empty($settings['google_verification']))
        <meta name="google-site-verification" content="{{ $settings['google_verification'] }}">
    @endif

    <!-- Open Graph / WhatsApp / Social Preview -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:title" content="{{ $seo['title'] ?? '' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:site_name" content="{{ $settings['site_name'] ?? 'Cyber Gaming Store' }}">
    <meta property="og:image" content="{{ $seo['og_image'] ?? '' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $seo['og_image'] ?? '' }}">

    <!-- Single Clean Modern Font Family: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        mono: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            overflow-x: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: normal;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 {
            letter-spacing: normal;
            line-height: 1.4;
        }
        p, li {
            line-height: 1.65;
        }
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
        }
        .backdrop-blur-xs {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
    </style>

    <!-- Schema.org JSON-LD Rich Snippets -->
    @if(!empty($seo['schemas']))
        @foreach($seo['schemas'] as $schema)
            <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endforeach
    @endif
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-blue-600 selection:text-white flex flex-col min-h-screen"
      x-data="cyberStoreApp()">

    <!-- Minimalist Top Bar -->
    <div class="bg-slate-950 text-slate-300 border-b border-slate-800/80 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-10 flex items-center justify-between gap-4">
            <span class="text-slate-300 truncate leading-normal">
                Garansi Resmi &amp; Toko &middot; Lulus Cek 35 Titik
            </span>
            <div class="flex items-center gap-5 shrink-0">
                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                   target="_blank" rel="noopener"
                   class="text-blue-400 hover:text-blue-300 font-medium transition">
                    Lokasi Toko (Maps)
                </a>
                <span class="text-slate-700">|</span>
                <a href="https://wa.me/{{ \App\Services\WhatsAppService::cleanNumber($settings['whatsapp_number'] ?? '0818880497') }}"
                   target="_blank" rel="noopener"
                   class="text-white hover:text-blue-300 font-medium transition">
                    WA: {{ $settings['whatsapp_number'] ?? '0818880497' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Clean Minimalist Navbar (Without "Beranda") -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-18 gap-4">
                <!-- Brand Identity -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-bold group-hover:bg-slate-950 transition">
                        CG
                    </div>
                    <span class="text-base font-bold text-slate-950">
                        Cyber Gaming Store
                    </span>
                </a>

                <!-- Desktop Navigation (5 Clean Items) -->
                <nav class="hidden lg:flex items-center gap-1.5 text-sm font-medium text-slate-600">
                    <a href="{{ route('catalog.index', ['filter' => 'best_seller']) }}"
                       class="whitespace-nowrap px-3.5 py-2 rounded-lg transition {{ request('filter') === 'best_seller' ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:text-slate-950 hover:bg-slate-50' }}">
                        Best Seller
                    </a>
                    <a href="{{ route('catalog.index') }}"
                       class="whitespace-nowrap px-3.5 py-2 rounded-lg transition {{ request()->routeIs('catalog.*') && !request('filter') ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:text-slate-950 hover:bg-slate-50' }}">
                        Katalog Laptop &amp; PC
                    </a>
                    <a href="{{ route('rental.index') }}"
                       class="whitespace-nowrap px-3.5 py-2 rounded-lg transition {{ request()->routeIs('rental.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:text-slate-950 hover:bg-slate-50' }}">
                        Sewa Laptop
                    </a>
                    <a href="{{ route('service.index') }}"
                       class="whitespace-nowrap px-3.5 py-2 rounded-lg transition {{ request()->routeIs('service.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:text-slate-950 hover:bg-slate-50' }}">
                        Service &amp; Sparepart
                    </a>
                    <a href="{{ route('articles.index') }}"
                       class="whitespace-nowrap px-3.5 py-2 rounded-lg transition {{ request()->routeIs('articles.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:text-slate-950 hover:bg-slate-50' }}">
                        Tips &amp; Artikel
                    </a>
                </nav>

                <!-- Right Actions -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <button type="button" @click="cartOpen = true"
                            class="whitespace-nowrap inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:border-blue-600 text-xs font-semibold text-slate-800 transition">
                        <span>Keranjang</span>
                        <span x-show="cartCount() > 0" x-text="cartCount()"
                              class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-blue-600 text-white"></span>
                    </button>

                    <button type="button" @click="openConsultModal = true"
                            class="hidden sm:inline-flex whitespace-nowrap items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                        Konsultasi Gratis
                    </button>

                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden whitespace-nowrap inline-flex items-center justify-center px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-900">
                        <span x-text="mobileMenuOpen ? 'Tutup' : 'Menu'">Menu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak
             class="lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-5 space-y-3 shadow-lg">
            <nav class="flex flex-col divide-y divide-slate-100 text-sm font-medium">
                <a href="{{ route('catalog.index', ['filter' => 'best_seller']) }}" class="py-3 text-slate-800 hover:text-blue-600">Best Seller</a>
                <a href="{{ route('catalog.index') }}" class="py-3 text-slate-800 hover:text-blue-600">Katalog Laptop &amp; PC</a>
                <a href="{{ route('rental.index') }}" class="py-3 text-slate-800 hover:text-blue-600">Sewa Laptop</a>
                <a href="{{ route('service.index') }}" class="py-3 text-slate-800 hover:text-blue-600">Service &amp; Sparepart</a>
                <a href="{{ route('articles.index') }}" class="py-3 text-slate-800 hover:text-blue-600">Tips &amp; Artikel</a>
            </nav>
            <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" @click="mobileMenuOpen = false; openConsultModal = true"
                        class="py-2.5 px-4 rounded-lg bg-blue-600 text-white text-xs font-semibold text-center">
                    Konsultasi Gratis
                </button>
                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                   target="_blank" rel="noopener"
                   class="py-2.5 px-4 rounded-lg border border-slate-300 text-slate-800 text-xs font-semibold text-center">
                    Lokasi Toko
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="px-4 py-3.5 rounded-xl bg-emerald-950 text-white text-xs font-medium leading-relaxed">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Main Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Slide-Over WhatsApp Order Drawer -->
    <div x-show="cartOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden">
        <div @click="cartOpen = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-md bg-white border-l border-slate-200 shadow-2xl flex flex-col">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-950">Keranjang Pesanan WhatsApp</h3>
                        <p class="text-xs text-slate-500 mt-1">WhatsApp {{ $settings['whatsapp_number'] ?? '0818880497' }}</p>
                    </div>
                    <button type="button" @click="cartOpen = false" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600">Tutup</button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <template x-if="cart.length === 0">
                        <div class="text-center py-16">
                            <p class="text-sm font-semibold text-slate-900">Keranjang pesanan masih kosong</p>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Pilih unit yang ingin Anda pesan terlebih dahulu.</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex gap-3.5 p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                            <img :src="item.image" :alt="item.name" class="w-16 h-16 rounded-lg object-cover shrink-0 bg-white border border-slate-200">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-950 leading-normal line-clamp-2" x-text="item.name"></p>
                                <p class="text-xs font-bold text-blue-600 mt-1.5" x-text="formatRupiah(item.price)"></p>
                                <div class="flex items-center justify-between mt-2.5">
                                    <div class="inline-flex items-center border border-slate-200 rounded-lg bg-white">
                                        <button type="button" @click="updateQty(index, -1)" class="px-2.5 py-0.5 text-xs text-slate-600">&minus;</button>
                                        <span class="px-2.5 py-0.5 text-xs font-semibold" x-text="item.qty"></span>
                                        <button type="button" @click="updateQty(index, 1)" class="px-2.5 py-0.5 text-xs text-slate-600">+</button>
                                    </div>
                                    <button type="button" @click="removeFromCart(index)" class="text-xs text-slate-400 hover:text-red-600">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="cart.length > 0" class="pt-4 border-t border-slate-200 space-y-3.5">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" x-model="buyerName" placeholder="Nama Anda"
                                   class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:outline-none focus:border-blue-600">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Kota</label>
                                <input type="text" x-model="buyerCity" placeholder="Jakarta"
                                       class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:outline-none focus:border-blue-600">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Pengiriman</label>
                                <select x-model="buyerMethod" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl bg-white">
                                    <option value="Ambil di Toko / COD">Ambil di Toko / COD</option>
                                    <option value="Kirim Ekspedisi">Kirim Ekspedisi</option>
                                    <option value="Kurir Instan">Kurir Instan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="cart.length > 0" class="p-6 bg-slate-50 border-t border-slate-200 space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Total Estimasi</span>
                        <span class="text-base font-bold text-slate-950" x-text="formatRupiah(cartTotal())"></span>
                    </div>
                    <button type="button" @click="checkoutCartToWhatsApp()"
                            class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs transition">
                        Pesan Sekarang via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Konsultasi Gratis -->
    <div x-show="openConsultModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="openConsultModal = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200 z-10">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-950">Konsultasi Gratis Pilihan Unit</h3>
                    <p class="text-xs text-slate-500 mt-1">Kami bantu rekomendasikan unit terbaik sesuai budget Anda</p>
                </div>
                <button type="button" @click="openConsultModal = false" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs text-slate-500">Tutup</button>
            </div>
            <form action="{{ route('lead.store') }}" method="POST" target="_blank" @submit="setTimeout(() => openConsultModal = false, 400)" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="lead_type" value="free_consultation">
                <input type="hidden" name="subject" value="Konsultasi Pemilihan Laptop / PC">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Anda</label>
                        <input type="text" name="customer_name" x-model="consult.name" required placeholder="Nama"
                               class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Kota</label>
                        <input type="text" name="customer_city" x-model="consult.city" placeholder="Jakarta"
                               class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:border-blue-600 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Kebutuhan</label>
                        <select x-model="consult.need" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl bg-white">
                            <option value="Gaming & Live Streaming">Gaming &amp; Streaming</option>
                            <option value="Kerja Kantor & Coding">Kerja &amp; Coding</option>
                            <option value="Desain & Edit Video">Desain &amp; Render 3D</option>
                            <option value="Kuliah & Tugas">Kuliah &amp; Harian</option>
                            <option value="Sewa Laptop">Sewa Laptop</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Budget</label>
                        <select x-model="consult.budget" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl bg-white">
                            <option value="Rp 4 – 7 Juta">Rp 4 – 7 Juta</option>
                            <option value="Rp 7 – 11 Juta">Rp 7 – 11 Juta</option>
                            <option value="Rp 11 – 16 Juta">Rp 11 – 16 Juta</option>
                            <option value="Di atas Rp 16 Juta">&gt; Rp 16 Juta</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Catatan (Opsional)</label>
                    <input type="text" x-model="consult.detail" placeholder="Contoh: cari ThinkPad atau ROG..."
                           class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:border-blue-600 focus:outline-none">
                </div>
                <input type="hidden" name="whatsapp_message"
                       :value="`Halo Tim Cyber Gaming Store,\nSaya ${consult.name || 'Pelanggan'} (${consult.city || '-'}) ingin konsultasi unit:\n- Kebutuhan: ${consult.need}\n- Budget: ${consult.budget}\n- Catatan: ${consult.detail || '-'}`">
                <div class="pt-1 flex justify-end gap-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition">
                        Kirim ke WhatsApp ({{ $settings['whatsapp_number'] ?? '0818880497' }})
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Video Review Player -->
    <div x-show="videoModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="closeVideoModal()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs"></div>
        <div class="relative w-full max-w-3xl bg-slate-950 rounded-2xl shadow-2xl overflow-hidden border border-slate-800 z-10">
            <div class="px-5 py-3.5 flex items-center justify-between border-b border-slate-800 text-white">
                <h3 class="text-xs font-semibold truncate pr-4" x-text="activeVideoTitle"></h3>
                <button type="button" @click="closeVideoModal()" class="px-3 py-1 rounded-lg bg-slate-800 text-xs text-slate-300 hover:text-white">Tutup</button>
            </div>
            <div class="aspect-video bg-black">
                <template x-if="videoModalOpen && activeVideoUrl">
                    <iframe :src="activeVideoUrl" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                </template>
            </div>
        </div>
    </div>

    <!-- Single Direct Floating WhatsApp Button -->
    <a href="https://wa.me/{{ \App\Services\WhatsAppService::cleanNumber($settings['whatsapp_number'] ?? '0818880497') }}?text={{ rawurlencode('Halo Tim Cyber Gaming Store, saya ingin tanya stok laptop/PC dan info layanan.') }}"
       target="_blank" rel="noopener"
       aria-label="Chat WhatsApp Cyber Gaming Store"
       class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-lg transition">
        <span class="w-2 h-2 rounded-full bg-white"></span>
        <span>WhatsApp</span>
    </a>

    <!-- Minimalist Corporate & SEO Footer -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-900 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 pb-10 border-b border-slate-900">
                <div class="lg:col-span-4 space-y-3.5 pr-0 lg:pr-6">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold">CG</div>
                        <span class="text-sm font-bold text-white">Cyber Gaming Store</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pusat jual beli laptop gaming &amp; kerja second rasa baru (Grade A+) serta unit baru segel resmi, PC rakitan custom, penyewaan laptop harian/bulanan, dan pusat service &amp; sparepart original bergaransi.
                    </p>
                    <div class="pt-1">
                        <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white border border-slate-800 text-xs font-medium transition">
                            Buka Lokasi Toko di Google Maps
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-xs font-semibold text-white">Katalog Produk</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('catalog.index', ['filter' => 'best_seller']) }}" class="hover:text-white transition">Best Seller Minggu Ini</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'laptop-gaming-second-baru']) }}" class="hover:text-white transition">Laptop Gaming Second &amp; Baru</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'laptop-bisnis-coding-ultrabook']) }}" class="hover:text-white transition">Laptop Bisnis &amp; Coding</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'pc-gaming-dan-rakitan']) }}" class="hover:text-white transition">PC Gaming &amp; Workstation</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'sparepart-laptop-dan-pc']) }}" class="hover:text-white transition">Sparepart Laptop &amp; PC Original</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2 space-y-3">
                    <h4 class="text-xs font-semibold text-white">Layanan Kami</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('rental.index') }}" class="hover:text-white transition">Sewa Laptop Harian</a></li>
                        <li><a href="{{ route('rental.index') }}" class="hover:text-white transition">Sewa Laptop Perusahaan</a></li>
                        <li><a href="{{ route('service.index') }}" class="hover:text-white transition">Service Laptop &amp; PC</a></li>
                        <li><a href="{{ route('service.index') }}#cek-tiket" class="hover:text-white transition">Cek Status Service</a></li>
                        <li><a href="{{ route('articles.index') }}" class="hover:text-white transition">Tips &amp; Panduan Laptop</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-xs font-semibold text-white">Kontak &amp; Kunjungan Toko</h4>
                    <div class="space-y-2.5 text-xs text-slate-400">
                        <p class="text-white font-semibold">
                            <a href="https://wa.me/{{ \App\Services\WhatsAppService::cleanNumber($settings['whatsapp_number'] ?? '0818880497') }}" target="_blank" rel="noopener" class="hover:text-blue-400">
                                WhatsApp: {{ $settings['whatsapp_number'] ?? '0818880497' }}
                            </a>
                        </p>
                        <p>{{ $settings['operating_hours'] ?? 'Senin – Minggu, 09.00 – 21.00 WIB' }}</p>
                        <p>{{ $settings['target_city'] ?? 'Melayani Kunjungan Toko & Pengiriman ke Seluruh Indonesia' }}</p>
                        <p>
                            <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                               target="_blank" rel="noopener"
                               class="text-blue-400 hover:text-blue-300 underline">
                                Petunjuk Arah Google Maps
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-slate-500">
                <span>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Cyber Gaming Store' }}. Seluruh hak cipta dilindungi.</span>
                <div class="flex items-center gap-6">
                    <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}" target="_blank" rel="noopener" class="hover:text-slate-300">Lokasi Google Maps</a>
                    <a href="{{ route('sitemap') }}" target="_blank" class="hover:text-slate-300">Sitemap XML</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function cyberStoreApp() {
            return {
                cartOpen: false,
                mobileMenuOpen: false,
                openConsultModal: false,
                videoModalOpen: false,
                activeVideoUrl: '',
                activeVideoTitle: '',
                cart: JSON.parse(localStorage.getItem('cgs_wa_cart') || '[]'),
                buyerName: '',
                buyerCity: '',
                buyerMethod: 'Ambil di Toko / COD',
                buyerNote: '',
                consult: {
                    name: '',
                    city: '',
                    need: 'Gaming & Live Streaming',
                    budget: 'Rp 7 – 11 Juta',
                    detail: ''
                },
                saveCart() {
                    localStorage.setItem('cgs_wa_cart', JSON.stringify(this.cart));
                },
                addToCart(product) {
                    const existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        existing.qty += 1;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            sku: product.sku,
                            price: Number(product.price),
                            condition: product.condition,
                            image: product.image,
                            qty: 1
                        });
                    }
                    this.saveCart();
                    this.cartOpen = true;
                },
                updateQty(index, delta) {
                    this.cart[index].qty += delta;
                    if (this.cart[index].qty <= 0) {
                        this.cart.splice(index, 1);
                    }
                    this.saveCart();
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },
                cartCount() {
                    return this.cart.reduce((sum, item) => sum + item.qty, 0);
                },
                cartTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },
                formatRupiah(num) {
                    return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
                },
                openVideoModal(url, title) {
                    this.activeVideoUrl = url;
                    this.activeVideoTitle = title || 'Video Review Produk — Cyber Gaming Store';
                    this.videoModalOpen = true;
                },
                closeVideoModal() {
                    this.videoModalOpen = false;
                    this.activeVideoUrl = '';
                },
                async checkoutCartToWhatsApp() {
                    if (this.cart.length === 0) return;

                    let lines = [];
                    lines.push('Halo Tim Cyber Gaming Store,');
                    lines.push('Saya ingin memesan unit berikut:');
                    lines.push('');
                    this.cart.forEach((item, i) => {
                        lines.push(`${i + 1}. ${item.name} [${item.sku}]`);
                        lines.push(`   Jumlah: ${item.qty} x ${this.formatRupiah(item.price)} = ${this.formatRupiah(item.price * item.qty)}`);
                    });
                    lines.push('');
                    lines.push(`Total Estimasi: ${this.formatRupiah(this.cartTotal())}`);
                    lines.push(`Nama Pemesan: ${this.buyerName || 'Pelanggan Website'}`);
                    lines.push(`Kota Tujuan: ${this.buyerCity || '-'}`);
                    lines.push(`Cara Pembelian: ${this.buyerMethod}`);
                    lines.push('\nMohon info stok dan langkah pembayarannya. Terima kasih.');

                    const message = lines.join('\n');

                    try {
                        const res = await fetch('{{ route('lead.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                lead_type: 'cart_checkout',
                                customer_name: this.buyerName || 'Pelanggan Website',
                                customer_city: this.buyerCity || '-',
                                subject: `Checkout Keranjang WA (${this.cartCount()} Unit)`,
                                items_summary: this.cart,
                                total_amount: this.cartTotal(),
                                whatsapp_message: message
                            })
                        });
                        const data = await res.json();
                        if (data.whatsapp_url) {
                            window.open(data.whatsapp_url, '_blank');
                            return;
                        }
                    } catch (e) {
                        // Fallback direct WhatsApp redirect
                    }

                    const waNumber = '{{ \App\Services\WhatsAppService::cleanNumber($settings['whatsapp_number'] ?? '0818880497') }}';
                    window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`, '_blank');
                }
            };
        }
    </script>
</body>
</html>
