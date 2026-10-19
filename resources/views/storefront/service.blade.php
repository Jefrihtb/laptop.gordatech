@extends('layouts.storefront')

@section('content')
    <section class="bg-slate-950 text-white border-b border-slate-900 py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-3.5">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white leading-snug">
                        Pusat Service Laptop, PC &amp; Sparepart Original
                    </h1>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed max-w-2xl">
                        Gratis cek kerusakan awal, estimasi biaya disepakati di awal, dan bergaransi. Klik <strong>Detail Layanan</strong> untuk melihat rincian pengerjaan.
                    </p>
                    <div class="pt-1 flex flex-wrap items-center gap-2.5">
                        <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA' }}"
                           target="_blank" rel="noopener"
                           class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                            Bawa ke Toko (Google Maps)
                        </a>
                        <a href="#katalog-sparepart"
                           class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 hover:border-slate-500 text-slate-200 text-xs font-semibold transition">
                            Lihat Sparepart
                        </a>
                    </div>
                </div>

                {{-- LIVE SERVICE TICKET TRACKER WITH WA LAST-4 VERIFICATION --}}
                <div id="cek-tiket" class="lg:col-span-5">
                    <div class="p-5 sm:p-6 rounded-2xl border border-slate-800 bg-slate-900/80 space-y-3.5">
                        <div>
                            <h2 class="text-sm font-bold text-white">Cek Status Service Laptop Anda</h2>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                Masukkan Nomor Tiket dan 4 digit terakhir nomor WhatsApp terdaftar untuk menjaga privasi data Anda.
                            </p>
                        </div>

                        <form action="{{ route('service.index') }}" method="GET" class="space-y-2.5">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                                <div class="sm:col-span-7">
                                    <label class="block text-[11px] font-medium text-slate-400 mb-1">Nomor Tiket Service *</label>
                                    <input type="text" name="ticket" value="{{ $ticketQuery }}" required
                                           placeholder="Kode Tiket (CGS-SRV-...)"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="sm:col-span-5">
                                    <label class="block text-[11px] font-medium text-slate-400 mb-1">4 Digit Akhir WA *</label>
                                    <input type="text" name="phone_last4" value="{{ substr($phoneLast4 ?? '', -4) }}" required
                                           maxlength="4" minlength="4" inputmode="numeric" pattern="[0-9]{4}"
                                           placeholder="Contoh: 1234"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                                Verifikasi &amp; Cek Status Service
                            </button>
                        </form>

                        @if($trackedTicket)
                            <div class="pt-3.5 border-t border-slate-800 space-y-2 text-xs">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-bold text-blue-400">{{ $trackedTicket->ticket_code }}</span>
                                    <span class="px-2.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[11px] font-medium">{{ $trackedTicket->statusLabel() }}</span>
                                </div>
                                <p class="font-semibold text-white">
                                    {{ $trackedTicket->device_brand_model }} &middot; {{ $trackedTicket->maskedCustomerName() }} ({{ $trackedTicket->maskedCustomerPhone() }})
                                </p>
                                <p class="text-slate-300 leading-relaxed">{{ $trackedTicket->diagnosis_notes }}</p>
                                <p class="text-slate-400 pt-1">
                                    Estimasi / Biaya: <strong class="text-white">Rp {{ number_format((float) ($trackedTicket->final_cost ?: $trackedTicket->estimated_cost), 0, ',', '.') }}</strong>
                                    &middot; {{ $trackedTicket->technician_name }}
                                </p>
                            </div>
                        @elseif(!empty($ticketError))
                            <div class="pt-3 border-t border-slate-800">
                                <p class="text-xs text-amber-400 leading-relaxed">{{ $ticketError }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12"
             x-data="{
                srvCustomer: '',
                srvPhone: '',
                srvDevice: '',
                srvCategory: '{{ addslashes($services->first()->name ?? 'Deep Cleaning Kipas & Ganti Pasta Dingin') }}',
                srvProblem: '',
                serviceModalOpen: false,
                activeService: {},
                openServiceDetail(srv) {
                    this.activeService = srv;
                    this.serviceModalOpen = true;
                },
                chooseService(name) {
                    this.srvCategory = name;
                    this.serviceModalOpen = false;
                    if (window.innerWidth < 1024) {
                        document.getElementById('form-booking-service')?.scrollIntoView({ behavior: 'smooth' });
                    }
                }
             }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- LEFT 7 COLS: MINIMALIST SERVICE CARDS --}}
            <div class="lg:col-span-7">
                <h2 class="text-lg font-bold text-slate-950 mb-5">Daftar Layanan &amp; Estimasi Biaya</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($services as $srv)
                        @php
                            $srvPayload = [
                                'name' => $srv->name,
                                'category' => $srv->category,
                                'duration' => $srv->estimated_duration,
                                'warranty' => $srv->warranty_info,
                                'price_range' => $srv->formattedPriceRange(),
                                'description' => $srv->description,
                                'symptoms' => $srv->symptoms ?? [],
                            ];
                        @endphp
                        <div :class="srvCategory === '{{ addslashes($srv->name) }}' ? 'border-blue-600 ring-2 ring-blue-600/15' : 'border-slate-200 hover:border-blue-600/50'"
                             class="p-5 rounded-2xl border bg-white flex flex-col justify-between transition shadow-xs h-full">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $srv->estimated_duration }}</span>
                                    <span class="text-emerald-700 font-medium">{{ $srv->warranty_info }}</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-950 leading-snug">{{ $srv->name }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $srv->description }}</p>
                                <p class="text-base font-bold text-blue-600 pt-1">{{ $srv->formattedPriceRange() }}</p>
                            </div>

                            <div class="mt-4 pt-3.5 border-t border-slate-100 grid grid-cols-2 gap-2.5">
                                <button type="button"
                                        @click="openServiceDetail({{ Js::from($srvPayload) }})"
                                        class="py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition">
                                    Detail Layanan
                                </button>
                                <button type="button"
                                        @click="chooseService('{{ addslashes($srv->name) }}')"
                                        :class="srvCategory === '{{ addslashes($srv->name) }}' ? 'bg-slate-950 text-white' : 'bg-blue-600 hover:bg-blue-500 text-white'"
                                        class="py-2.5 px-3 rounded-xl text-xs font-semibold transition">
                                    <span x-text="srvCategory === '{{ addslashes($srv->name) }}' ? 'Dipilih ✓' : 'Pilih Layanan'">Pilih Layanan</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT 5 COLS: CLEAN BOOKING FORM --}}
            <div id="form-booking-service" class="lg:col-span-5 lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <h2 class="text-base font-bold text-slate-950">Konsultasi Teknisi &amp; Booking Service</h2>
                    <p class="text-xs text-slate-500 mt-1">Tanyakan estimasi biaya perbaikan atau stok sparepart via WhatsApp.</p>

                    <form action="{{ route('lead.store') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="lead_type" value="service_booking">
                        <input type="hidden" name="subject" :value="`Service / Sparepart: ${srvCategory} - ${srvDevice}`">
                        <input type="hidden" name="whatsapp_message"
                               :value="`Halo Tim Service Cyber Gaming Store,\n\nSaya ingin konsultasi service / sparepart:\n- Nama: ${srvCustomer}\n- WA: ${srvPhone}\n- Tipe Unit: ${srvDevice}\n- Layanan: ${srvCategory}\n- Keluhan: ${srvProblem}`">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Anda *</label>
                                <input type="text" name="customer_name" x-model="srvCustomer" required placeholder="Nama"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1.5">No. WhatsApp *</label>
                                <input type="text" name="customer_phone" x-model="srvPhone" required placeholder="0812xxxx"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Tipe Laptop / PC *</label>
                            <input type="text" x-model="srvDevice" required placeholder="Contoh: ASUS ROG Strix G15"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Layanan Pilihan</label>
                            <select x-model="srvCategory" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-medium text-slate-900 bg-white focus:border-blue-600 focus:outline-none">
                                @foreach($services as $srv)
                                    <option value="{{ $srv->name }}">{{ $srv->name }}</option>
                                @endforeach
                                <option value="Cari Sparepart Baru / Copotan Original">Cari Sparepart Baru / Copotan Original</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Keluhan / Sparepart yang Dicari *</label>
                            <textarea x-model="srvProblem" required rows="2" placeholder="Ceritakan keluhan unit Anda..."
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-blue-600 focus:outline-none"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition">
                            Konsultasi Teknisi via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- POP-UP MODAL: DETAIL LAYANAN SERVICE --}}
        <div x-show="serviceModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="serviceModalOpen = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-950 pr-4" x-text="activeService.name"></h3>
                    <button type="button" @click="serviceModalOpen = false" class="px-3 py-1 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 shrink-0">
                        Tutup
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                        <div>
                            <span class="block text-[11px] text-slate-400">Estimasi Biaya</span>
                            <span class="text-base font-bold text-blue-600" x-text="activeService.price_range"></span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[11px] text-slate-400">Garansi</span>
                            <span class="text-xs font-semibold text-slate-900" x-text="activeService.warranty"></span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed" x-text="activeService.description"></p>

                    <template x-if="activeService.symptoms && activeService.symptoms.length">
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100 space-y-1.5 text-xs text-slate-700">
                            <span class="font-semibold text-blue-900 block">Keluhan yang Ditangani:</span>
                            <template x-for="(sym, idx) in activeService.symptoms" :key="idx">
                                <p x-text="'• ' + sym"></p>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2">
                    <button type="button" @click="serviceModalOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs font-semibold">
                        Tutup
                    </button>
                    <button type="button" @click="chooseService(activeService.name)"
                            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold">
                        Pilih Layanan Ini
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- SPAREPART CATALOG SECTION --}}
    @if($spareparts->isNotEmpty())
        <section id="katalog-sparepart" class="bg-white border-t border-slate-200 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-950">Katalog Sparepart Baru &amp; Copotan Original</h2>
                        <p class="text-xs text-slate-500 mt-1">Bisa beli sparepart saja atau sekalian dipasang di toko kami.</p>
                    </div>
                    <a href="{{ route('catalog.index', ['type' => 'sparepart']) }}" class="text-xs font-semibold text-blue-600 hover:underline shrink-0">
                        Semua Sparepart &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($spareparts as $part)
                        @include('partials.product-card', ['product' => $part])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
