<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Manajemen Toko') — Cyber Gaming Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        mono: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            letter-spacing: normal;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(15, 23, 42, 0.06);
        }
        .backdrop-blur-xs {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="bg-zinc-50 text-zinc-950 antialiased selection:bg-blue-600 selection:text-white" x-data="{ adminNavOpen: false }">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Mobile Top Header -->
        <div class="lg:hidden bg-zinc-950 text-white px-4 h-16 flex items-center justify-between border-b border-zinc-900 sticky top-0 z-40">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">CG</div>
                <span class="text-xs font-semibold">Cyber Gaming Store</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank" class="px-2.5 py-1.5 rounded-lg border border-zinc-800 text-xs text-zinc-300">
                    Lihat Toko
                </a>
                <button type="button" @click="adminNavOpen = !adminNavOpen" class="px-3 py-1.5 rounded-lg bg-blue-600 text-xs font-semibold text-white">
                    <span x-text="adminNavOpen ? 'Tutup' : 'Menu'">Menu</span>
                </button>
            </div>
        </div>

        <!-- Responsive Sidebar Navigation -->
        <aside :class="adminNavOpen ? 'flex' : 'hidden lg:flex'"
               class="w-full lg:w-64 bg-zinc-950 text-zinc-400 shrink-0 flex-col justify-between border-b lg:border-b-0 lg:border-r border-zinc-900">
            <div>
                <div class="hidden lg:flex px-5 h-16 border-b border-zinc-900 items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">CG</div>
                        <div class="leading-tight">
                            <span class="block text-xs font-bold text-white">Cyber Gaming Store</span>
                            <span class="block text-[11px] text-zinc-500 mt-0.5">Manajemen Toko</span>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="text-xs px-2.5 py-1 rounded-lg border border-zinc-800 hover:border-zinc-700 text-zinc-300 hover:text-white transition">
                        Toko &rarr;
                    </a>
                </div>

                <div class="px-5 pt-5 pb-2">
                    <span class="text-[11px] font-semibold text-zinc-500">Menu Utama</span>
                </div>

                <nav class="px-3 space-y-1 text-xs font-medium">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Ringkasan &amp; Pesanan WA</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Katalog Laptop, PC &amp; Part</span>
                    </a>
                    <a href="{{ route('admin.rentals.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.rentals.*') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Paket Sewa Laptop</span>
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.services.*') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Service &amp; Tiket Pelanggan</span>
                    </a>
                    <a href="{{ route('admin.banners.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.banners.*') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Banner Promo Beranda</span>
                    </a>
                    <a href="{{ route('admin.articles.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.articles.*') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Tips &amp; Artikel SEO</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white font-semibold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <span>Pengaturan Toko &amp; SEO</span>
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-zinc-900 space-y-2 mt-4 lg:mt-0">
                <a href="{{ route('admin.export.sql') }}"
                   class="w-full py-2 px-3.5 rounded-lg border border-zinc-800 bg-zinc-900 hover:border-zinc-700 text-zinc-200 text-xs font-medium flex items-center justify-between transition">
                    <span>Backup Database</span>
                    <span class="text-[11px] text-emerald-400 font-semibold">.SQL</span>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 px-3.5 rounded-lg text-zinc-500 hover:text-white hover:bg-zinc-900 text-xs font-medium text-left transition">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="bg-white border-b border-zinc-200 px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-zinc-950">@yield('header', 'Manajemen Cyber Gaming Store')</h1>
                    <p class="text-xs text-zinc-500 mt-0.5">Kelola stok produk, layanan sewa, tiket service, dan optimasi SEO toko</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-xs">
                        + Tambah Produk Baru
                    </a>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8 flex-1">
                @if(session('success'))
                    <div class="mb-6 px-4 py-3 rounded-xl bg-emerald-950 text-white text-xs font-medium flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <span class="text-[11px] text-emerald-400 font-semibold">Tersimpan</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-900 text-xs space-y-1">
                        <p class="font-semibold">Mohon periksa kembali isian berikut:</p>
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        window.compressImageForSharedHosting = function(fileInput, hiddenInputId, previewImgId, statusId) {
            const file = fileInput.files && fileInput.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            const statusEl = document.getElementById(statusId);
            if (statusEl) {
                statusEl.textContent = 'Memproses foto...';
                statusEl.className = 'text-[11px] text-zinc-500 mt-1 block';
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const maxDim = 1280;
                    let width = img.width;
                    let height = img.height;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    let dataUrl = canvas.toDataURL('image/webp', 0.82);
                    if (!dataUrl.startsWith('data:image/webp')) {
                        dataUrl = canvas.toDataURL('image/jpeg', 0.82);
                    }

                    const hiddenInput = document.getElementById(hiddenInputId);
                    if (hiddenInput) {
                        hiddenInput.value = dataUrl;
                    }

                    const previewImg = document.getElementById(previewImgId);
                    if (previewImg) {
                        previewImg.src = dataUrl;
                        previewImg.classList.remove('hidden');
                    }

                    const kbSize = Math.round((dataUrl.length * 3 / 4) / 1024);
                    if (statusEl) {
                        statusEl.textContent = `Foto siap disimpan (${kbSize} KB)`;
                        statusEl.className = 'text-[11px] text-emerald-600 mt-1 block font-medium';
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };
    </script>
</body>
</html>
