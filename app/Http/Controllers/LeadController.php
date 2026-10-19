<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    /**
     * Record a conversion lead (Cart Checkout, Rental Simulation, Service Consultation, Trade-In, or Free Consultation)
     * and redirect or return the structured WhatsApp URL to 0818880497.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'lead_type' => ['required', 'string', 'max:50'],
            'customer_name' => ['nullable', 'string', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'customer_city' => ['nullable', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'items_summary' => ['nullable', 'array'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'whatsapp_message' => ['required', 'string', 'max:4000'],
        ]);

        $leadCode = 'WA-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        $storeName = Setting::getValue('site_name', 'Cyber Gaming Store');

        $finalMessage = $validated['whatsapp_message']."\n\nKode Referensi: {$leadCode} ({$storeName})";

        Lead::query()->create([
            'lead_code' => $leadCode,
            'lead_type' => $validated['lead_type'],
            'customer_name' => $validated['customer_name'] ?? 'Pelanggan Website',
            'customer_phone' => $validated['customer_phone'] ?? '-',
            'customer_city' => $validated['customer_city'] ?? '-',
            'subject' => $validated['subject'],
            'items_summary' => $validated['items_summary'] ?? [],
            'total_amount' => (int) ($validated['total_amount'] ?? 0),
            'whatsapp_message' => $finalMessage,
            'ip_address' => $request->ip(),
            'status' => 'new',
        ]);

        $waUrl = WhatsAppService::buildUrl($finalMessage);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'lead_code' => $leadCode,
                'whatsapp_url' => $waUrl,
            ]);
        }

        return redirect()->away($waUrl);
    }
}
