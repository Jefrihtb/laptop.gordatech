<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCatalog;
use App\Models\ServiceTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = ServiceCatalog::query()->orderBy('sort_order')->get();
        $tickets = ServiceTicket::query()->latest()->get();

        return view('admin.services.index', compact('services', 'tickets'));
    }

    public function storeService(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'estimated_price_start' => ['required', 'numeric', 'min:0'],
            'estimated_price_end' => ['nullable', 'numeric', 'min:0'],
            'estimated_duration' => ['required', 'string', 'max:150'],
            'warranty_info' => ['required', 'string', 'max:150'],
            'symptoms_text' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'thumbnail_url' => ['nullable', 'string', 'max:500'],
        ]);

        $symptoms = array_values(array_filter(array_map('trim', explode("\n", (string) ($validated['symptoms_text'] ?? '')))));

        ServiceCatalog::query()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.strtolower(Str::random(3)),
            'category' => $validated['category'],
            'estimated_price_start' => (int) $validated['estimated_price_start'],
            'estimated_price_end' => ! empty($validated['estimated_price_end']) ? (int) $validated['estimated_price_end'] : null,
            'estimated_duration' => $validated['estimated_duration'],
            'warranty_info' => $validated['warranty_info'],
            'symptoms' => $symptoms,
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail_url'] ?: 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=900&q=80',
            'is_popular' => $request->boolean('is_popular'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Layanan Service baru berhasil ditambahkan!');
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_code' => ['nullable', 'string', 'max:50'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'device_type' => ['required', 'in:laptop,pc'],
            'device_brand_model' => ['required', 'string', 'max:200'],
            'problem_description' => ['required', 'string'],
            'diagnosis_notes' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'technician_name' => ['nullable', 'string', 'max:150'],
        ]);

        $code = $validated['ticket_code'] ?: 'CGS-SRV-'.now()->format('ym').rand(10, 99);

        ServiceTicket::query()->create([
            'ticket_code' => strtoupper($code),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'device_type' => $validated['device_type'],
            'device_brand_model' => $validated['device_brand_model'],
            'problem_description' => $validated['problem_description'],
            'diagnosis_notes' => $validated['diagnosis_notes'] ?? 'Unit sedang dalam pengecekan teknisi.',
            'status' => $validated['status'],
            'estimated_cost' => (int) $validated['estimated_cost'],
            'final_cost' => (int) $validated['estimated_cost'],
            'technician_name' => $validated['technician_name'] ?: 'Tim Teknisi Cyber Gaming',
            'warranty_until' => now()->addDays(90)->toDateString(),
        ]);

        return back()->with('success', "Tiket Service {$code} berhasil dibuat dan bisa dicek pelanggan secara live!");
    }

    public function updateTicketStatus(Request $request, ServiceTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'diagnosis_notes' => ['nullable', 'string'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'diagnosis_notes' => $validated['diagnosis_notes'] ?? $ticket->diagnosis_notes,
            'final_cost' => isset($validated['final_cost']) ? (int) $validated['final_cost'] : $ticket->final_cost,
        ]);

        return back()->with('success', "Status Tiket {$ticket->ticket_code} berhasil diperbarui!");
    }

    public function destroyService(ServiceCatalog $service): RedirectResponse
    {
        $service->delete();

        return back()->with('success', 'Katalog service berhasil dihapus.');
    }
}
