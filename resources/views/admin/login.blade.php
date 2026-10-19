<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk Admin — Cyber Gaming Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>
<body class="min-h-screen bg-zinc-950 flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md bg-white rounded-2xl overflow-hidden border border-zinc-800 shadow-2xl">
        <div class="p-7 bg-zinc-950 text-white border-b border-zinc-900">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-bold">CG</div>
                <div>
                    <h1 class="text-base font-bold text-white">Cyber Gaming Store</h1>
                    <p class="text-xs text-zinc-400">Manajemen Toko &amp; Katalog</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.login.post') }}" method="POST" class="p-7 space-y-5">
            @csrf

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="admin_email" class="block text-xs font-medium text-zinc-700 mb-1.5">Email Admin</label>
                <input type="email" id="admin_email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="Masukkan email admin"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-zinc-300 text-xs font-medium text-zinc-900 focus:border-blue-600 focus:outline-none">
            </div>

            <div>
                <label for="admin_pass" class="block text-xs font-medium text-zinc-700 mb-1.5">Kata Sandi</label>
                <input type="password" id="admin_pass" name="password" required
                       placeholder="Masukkan kata sandi"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-zinc-300 text-xs font-medium text-zinc-900 focus:border-blue-600 focus:outline-none">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-zinc-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-zinc-300 text-blue-600">
                    <span>tetap masuk di perangkat ini</span>
                </label>
            </div>

            <div class="pt-1 flex items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="text-xs font-medium text-zinc-500 hover:text-zinc-950">&larr; Kembali ke Toko</a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-sm">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</body>
</html>
