@extends('layouts.admin')

@section('title', 'Service & Tiket Pelanggan')
@section('header', 'Kelola Layanan Service & Status Tiket Pelanggan')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Update Customer Service Tickets -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-xl border border-zinc-200 p-6">
                <div class="border-b border-zinc-100 pb-3 mb-4">
                    <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Antrean Pengerjaan</span>
                    <h2 class="text-sm font-semibold text-zinc-950 mt-0.5">Daftar Tiket Service Pelanggan</h2>
                </div>

                <div class="space-y-4">
                    @foreach($tickets as $ticket)
                        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="p-4 rounded-xl bg-zinc-50 border border-zinc-200 space-y-3 text-xs">
                            @csrf
                            @method('PUT')
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded bg-blue-600 text-white font-mono font-semibold text-[11px]">{{ $ticket->ticket_code }}</span>
                                    <strong class="ml-2 text-zinc-950">{{ $ticket->customer_name }} ({{ $ticket->customer_phone }})</strong>
                                </div>
                                <span class="font-mono text-zinc-600">{{ $ticket->device_brand_model }}</span>
                            </div>

                            <p class="text-zinc-600"><span class="text-zinc-400">Keluhan:</span> {{ $ticket->problem_description }}</p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                <div>
                                    <label class="block font-medium text-zinc-700 mb-1">Status Pengerjaan</label>
                                    <select name="status" class="w-full px-2.5 py-1.5 bg-white border border-zinc-300 rounded-lg font-medium focus:border-blue-600 focus:outline-none">
                                        <option value="received" {{ $ticket->status === 'received' ? 'selected' : '' }}>Unit Diterima</option>
                                        <option value="diagnosing" {{ $ticket->status === 'diagnosing' ? 'selected' : '' }}>Sedang Dicek</option>
                                        <option value="waiting_approval" {{ $ticket->status === 'waiting_approval' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                        <option value="qc_testing" {{ $ticket->status === 'qc_testing' ? 'selected' : '' }}>Tahap Tes Akhir</option>
                                        <option value="ready_pickup" {{ $ticket->status === 'ready_pickup' ? 'selected' : '' }}>Selesai - Siap Diambil</option>
                                        <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Sudah Diambil</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-medium text-zinc-700 mb-1">Biaya Service (Rp)</label>
                                    <input type="number" name="final_cost" value="{{ $ticket->final_cost ?: $ticket->estimated_cost }}"
                                           class="w-full px-2.5 py-1.5 font-mono bg-white border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="inline-flex items-center justify-center py-1.5 px-4 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold transition shadow-xs">
                                        Update Status
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block font-medium text-zinc-700 mb-1">Catatan Teknisi (Tampil Saat Pelanggan Cek Tiket)</label>
                                <input type="text" name="diagnosis_notes" value="{{ $ticket->diagnosis_notes }}"
                                       class="w-full px-2.5 py-1.5 bg-white border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Add New Ticket -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-xl border border-zinc-200 p-6">
                <div class="border-b border-zinc-100 pb-3 mb-4">
                    <span class="text-[10px] font-mono uppercase tracking-widest text-blue-600">Input Tiket Baru</span>
                    <h3 class="text-sm font-semibold text-zinc-950 mt-0.5">Buat Tiket Service Baru</h3>
                </div>

                <form action="{{ route('admin.tickets.store') }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" required placeholder="Nama Pelanggan"
                                   class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">No. WhatsApp</label>
                            <input type="text" name="customer_phone" required placeholder="0812..."
                                   class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Jenis Unit</label>
                            <select name="device_type" class="w-full px-3 py-2 border border-zinc-300 rounded-lg bg-white focus:border-blue-600 focus:outline-none">
                                <option value="laptop">Laptop</option>
                                <option value="pc">PC Desktop</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Merk &amp; Tipe</label>
                            <input type="text" name="device_brand_model" required placeholder="ASUS TUF FX505 / Legion"
                                   class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1">Keluhan Kerusakan</label>
                        <input type="text" name="problem_description" required placeholder="Ganti layar LCD 144Hz / Service mesin..."
                               class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Status Awal</label>
                            <select name="status" class="w-full px-3 py-2 border border-zinc-300 rounded-lg bg-white focus:border-blue-600 focus:outline-none">
                                <option value="diagnosing">Sedang Dicek</option>
                                <option value="in_progress">Sedang Dikerjakan</option>
                                <option value="ready_pickup">Siap Diambil</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-zinc-700 mb-1">Perkiraan Biaya (Rp)</label>
                            <input type="number" name="estimated_cost" required value="250000"
                                   class="w-full px-3 py-2 font-mono border border-zinc-300 rounded-lg focus:border-blue-600 focus:outline-none">
                        </div>
                    </div>
                    <div class="pt-1 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-xs">
                            Buat Tiket Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
