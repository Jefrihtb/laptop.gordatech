@extends('layouts.storefront')

@section('content')
    <section class="bg-slate-950 text-white border-b border-slate-900 py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white leading-snug">
                    Sewa Laptop Harian, Mingguan &amp; Bulanan
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1.5 leading-relaxed">
                    Unit siap pakai untuk kantor, pelatihan, kampus &amp; event. Klik <strong>Detail Paket</strong> untuk melihat spesifikasi lengkap dan fasilitas sewa.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                   target="_blank" rel="noopener"
                   class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 hover:border-slate-500 text-slate-200 text-xs font-semibold transition">
                    Lokasi Toko (Google Maps)
                </a>
            </div>
        </div>
    </section>

    <section id="kalkulator-sewa" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12"
             x-data="{
                selectedPkgName: '{{ addslashes($rentalPackages->first()->name ?? 'Paket Sewa Standar Kantor (Core i5)') }}',
                dailyRate: {{ (int) ($rentalPackages->first()->price_daily ?? 55000) }},
                weeklyRate: {{ (int) ($rentalPackages->first()->price_weekly ?? 275000) }},
                monthlyRate: {{ (int) ($rentalPackages->first()->price_monthly ?? 650000) }},
                qty: 5,
                duration: 3,
                period: 'Harian',
                picName: '',
                picPhone: '',
                picCompany: '',
                notes: '',
                rentalDetailOpen: false,
                activeRental: {},
                openRentalDetail(pkg) {
                    this.activeRental = pkg;
                    this.rentalDetailOpen = true;
                },
                selectPackage(name, d, w, m) {
                    this.selectedPkgName = name;
                    this.dailyRate = d;
                    this.weeklyRate = w;
                    this.monthlyRate = m;
                    this.rentalDetailOpen = false;
                    if (window.innerWidth < 1024) {
                        document.getElementById('form-kalkulator-sewa')?.scrollIntoView({ behavior: 'smooth' });
                    }
                },
                estimatedTotal() {
                    let rate = this.dailyRate;
                    if (this.period === 'Mingguan') rate = this.weeklyRate;
                    if (this.period === 'Bulanan') rate = this.monthlyRate;
                    return rate * this.qty * this.duration;
                },
                formatIdr(val) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                }
             }">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- LEFT 7 COLS: MINIMALIST RENTAL PACKAGE CARDS --}}
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($rentalPackages as $pkg)
                        @php
                            $pkgPayload = [
                                'name' => $pkg->name,
                                'brand_model' => $pkg->brand_model,
                                'target_audience' => $pkg->target_audience,
                                'processor' => $pkg->processor,
                                'ram' => $pkg->ram,
                                'storage' => $pkg->storage,
                                'gpu' => $pkg->gpu,
                                'display' => $pkg->display,
                                'available_units' => $pkg->available_units,
                                'daily_fmt' => $pkg->formattedDaily(),
                                'weekly_fmt' => $pkg->formattedWeekly(),
                                'monthly_fmt' => $pkg->formattedMonthly(),
                                'price_daily' => (int) $pkg->price_daily,
                                'price_weekly' => (int) $pkg->price_weekly,
                                'price_monthly' => (int) $pkg->price_monthly,
                                'benefits' => $pkg->benefits ?? [],
                                'image' => $pkg->thumbnailUrl(),
                            ];
                        @endphp
                        <div :class="selectedPkgName === '{{ addslashes($pkg->name) }}' ? 'border-blue-600 ring-2 ring-blue-600/15' : 'border-slate-200 hover:border-blue-600/50'"
                             class="rounded-2xl border bg-white overflow-hidden flex flex-col justify-between transition shadow-xs h-full">
                            <div>
                                <div class="relative aspect-[16/10] bg-slate-100 overflow-hidden border-b border-slate-100 cursor-pointer"
                                     @click="openRentalDetail({{ Js::from($pkgPayload) }})">
                                    <img src="{{ $pkg->thumbnailUrl() }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-slate-950/85 text-white text-[11px] font-medium">
                                        Ready {{ $pkg->available_units }} Unit
                                    </span>
                                    @if($pkg->is_popular)
                                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md bg-blue-600 text-white text-[10px] font-semibold">
                                            Terlaris
                                        </span>
                                    @endif
                                </div>

                                <div class="p-5 space-y-3">
                                    <div>
                                        <p class="text-xs text-slate-500">{{ $pkg->brand_model }}</p>
                                        <h2 class="text-base font-bold text-slate-950 mt-1 leading-snug">{{ $pkg->name }}</h2>
                                    </div>

                                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                                        <p class="font-medium text-slate-800">{{ $pkg->processor }} &middot; {{ $pkg->ram }}</p>
                                        <p class="text-slate-500">{{ $pkg->storage }} &middot; {{ $pkg->display }}</p>
                                    </div>

                                    <div class="pt-1 flex items-baseline justify-between">
                                        <div>
                                            <span class="block text-[11px] text-slate-400">Tarif Harian</span>
                                            <span class="text-base font-bold text-slate-950">{{ $pkg->formattedDaily() }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-[11px] text-slate-400">Tarif Bulanan</span>
                                            <span class="text-sm font-bold text-blue-600">{{ $pkg->formattedMonthly() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 pb-5 grid grid-cols-2 gap-2.5">
                                <button type="button"
                                        @click="openRentalDetail({{ Js::from($pkgPayload) }})"
                                        class="py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition">
                                    Detail Paket
                                </button>
                                <button type="button"
                                        @click="selectPackage('{{ addslashes($pkg->name) }}', {{ (int)$pkg->price_daily }}, {{ (int)$pkg->price_weekly }}, {{ (int)$pkg->price_monthly }})"
                                        :class="selectedPkgName === '{{ addslashes($pkg->name) }}' ? 'bg-slate-950 text-white' : 'bg-blue-600 hover:bg-blue-500 text-white'"
                                        class="py-2.5 px-3 rounded-xl text-xs font-semibold transition">
                                    <span x-text="selectedPkgName === '{{ addslashes($pkg->name) }}' ? 'Dipilih ✓' : 'Pilih Paket'">Pilih Paket</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT 5 COLS: CLEAN RENTAL CALCULATOR --}}
            <div id="form-kalkulator-sewa" class="lg:col-span-5 lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <h2 class="text-base font-bold text-slate-950">Kalkulator &amp; Pesan Sewa Laptop</h2>
                    <p class="text-xs text-slate-500 mt-1">Hitung estimasi biaya sewa dan kirim langsung ke WhatsApp.</p>

                    <form action="{{ route('lead.store') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="lead_type" value="rental_simulation">
                        <input type="hidden" name="subject" :value="`Sewa Laptop: ${selectedPkgName} (${qty} Unit)`">
                        <input type="hidden" name="total_amount" :value="estimatedTotal()">
                        <input type="hidden" name="whatsapp_message"
                               :value="`Halo Admin Sewa Cyber Gaming Store,\n\nSaya ingin sewa laptop:\n- Paket: ${selectedPkgName}\n- Jumlah: ${qty} Unit\n- Durasi: ${duration} ${period}\n- Estimasi: ${formatIdr(estimatedTotal())}\n- Nama: ${picName}\n- Instansi/Kota: ${picCompany || '-'}\n- Catatan: ${notes || '-'}`">

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Paket Terpilih</label>
                            <input type="text" x-model="selectedPkgName" readonly
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-blue-200 bg-blue-50/50 text-xs font-semibold text-blue-950">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">Unit</label>
                                <input type="number" x-model.number="qty" min="1" max="500" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-900 focus:border-blue-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">Lama</label>
                                <input type="number" x-model.number="duration" min="1" max="365" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-900 focus:border-blue-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">Periode</label>
                                <select x-model="period" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-900 bg-white focus:border-blue-600 focus:outline-none">
                                    <option value="Harian">Harian</option>
                                    <option value="Mingguan">Mingguan</option>
                                    <option value="Bulanan">Bulanan</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-950 text-white flex items-center justify-between">
                            <div>
                                <span class="block text-[11px] text-slate-400">Estimasi Total</span>
                                <span class="text-xs text-slate-300"><span x-text="qty"></span> unit &times; <span x-text="duration"></span> <span x-text="period"></span></span>
                            </div>
                            <span class="text-lg font-bold text-white" x-text="formatIdr(estimatedTotal())"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Anda *</label>
                                <input type="text" name="customer_name" x-model="picName" required placeholder="Nama"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">No. WhatsApp *</label>
                                <input type="text" name="customer_phone" x-model="picPhone" required placeholder="0812xxxx"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Kantor / Kampus &amp; Kota</label>
                            <input type="text" name="customer_city" x-model="picCompany" placeholder="PT Maju Jaya - Jakarta"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none">
                        </div>

                        <button type="submit"
                                class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition">
                            Tanyakan Penawaran via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- POP-UP MODAL: DETAIL SPESIFIKASI & HARGA SEWA LAPTOP --}}
        <div x-show="rentalDetailOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="rentalDetailOpen = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-blue-600" x-text="activeRental.brand_model"></span>
                        <h3 class="text-base font-bold text-slate-950 mt-0.5" x-text="activeRental.name"></h3>
                    </div>
                    <button type="button" @click="rentalDetailOpen = false" class="px-3 py-1 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600">
                        Tutup
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                    <div class="grid grid-cols-3 gap-2.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                        <div>
                            <span class="block text-[11px] text-slate-400">Harian</span>
                            <span class="text-sm font-bold text-slate-900" x-text="activeRental.daily_fmt"></span>
                        </div>
                        <div class="border-x border-slate-200">
                            <span class="block text-[11px] text-slate-400">Mingguan</span>
                            <span class="text-sm font-bold text-slate-900" x-text="activeRental.weekly_fmt"></span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-blue-600 font-medium">Bulanan</span>
                            <span class="text-sm font-bold text-blue-600" x-text="activeRental.monthly_fmt"></span>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400">Cocok Untuk</span>
                            <span class="font-medium text-slate-900 text-right" x-text="activeRental.target_audience"></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400">Prosesor</span>
                            <span class="font-medium text-slate-900 text-right" x-text="activeRental.processor"></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400">RAM &amp; Storage</span>
                            <span class="font-medium text-slate-900 text-right" x-text="activeRental.ram + ' / ' + activeRental.storage"></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400">Grafis &amp; Layar</span>
                            <span class="font-medium text-slate-900 text-right" x-text="activeRental.gpu + ' · ' + activeRental.display"></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400">Stok Tersedia</span>
                            <span class="font-semibold text-emerald-600 text-right" x-text="activeRental.available_units + ' Unit Siap Pakai'"></span>
                        </div>
                    </div>

                    <template x-if="activeRental.benefits && activeRental.benefits.length">
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100 space-y-1.5 text-xs text-slate-700">
                            <span class="font-semibold text-blue-900 block">Fasilitas Sewa:</span>
                            <template x-for="(b, idx) in activeRental.benefits" :key="idx">
                                <p x-text="'• ' + b"></p>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2.5">
                    <button type="button" @click="rentalDetailOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs font-semibold">
                        Tutup
                    </button>
                    <button type="button"
                            @click="selectPackage(activeRental.name, activeRental.price_daily, activeRental.price_weekly, activeRental.price_monthly)"
                            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold">
                        Pilih &amp; Hitung Paket Ini
                    </button>
                </div>
            </div>
        </div>

        @if(!empty($rentalFaqs))
            <div class="mt-14 pt-10 border-t border-slate-200">
                <h2 class="text-lg font-bold text-slate-950 mb-5">Tanya Jawab Seputar Penyewaan Laptop</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($rentalFaqs as $faq)
                        <div class="p-5 rounded-2xl bg-white border border-slate-200">
                            <h3 class="text-xs font-bold text-slate-950 leading-normal">{{ $faq['q'] }}</h3>
                            <p class="text-xs text-slate-600 mt-2 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
