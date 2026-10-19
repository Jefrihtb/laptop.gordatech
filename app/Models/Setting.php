<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

#[Fillable([
    'key',
    'value',
    'group',
])]
class Setting extends Model
{
    /**
     * Retrieve all settings as key => value array with sensible defaults.
     *
     * @return array<string, string>
     */
    public static function allKeyed(): array
    {
        $defaults = [
            'site_name' => 'Cyber Gaming Store',
            'site_tagline' => 'Pusat Jual Beli Laptop Gaming & Kerja, PC Rakitan, Sewa Laptop & Service Bergaransi',
            'whatsapp_number' => '0818880497',
            'whatsapp_international' => '62818880497',
            'target_city' => 'Jakarta & Pengiriman Aman ke Seluruh Indonesia',
            'main_city_name' => 'Jakarta',
            'store_address' => 'Kunjungi Toko Fisik Cyber Gaming Store (Klik Petunjuk Google Maps)',
            'maps_url' => 'https://maps.app.goo.gl/uxDzw1oHj52Uz7dRA',
            'operating_hours' => 'Senin – Minggu, 09.00 – 21.00 WIB',
            'store_email' => 'halo@cybergamingstore.id',
            'instagram_url' => 'https://instagram.com/cybergamingstore',
            'tiktok_url' => 'https://tiktok.com/@cybergamingstore',
            'youtube_url' => 'https://youtube.com/@cybergamingstore',
            'seo_default_title' => 'Cyber Gaming Store — Jual Laptop Gaming Second & Baru, PC Rakitan, Sewa Laptop & Service Bergaransi',
            'seo_default_description' => 'Toko laptop gaming & kerja second rasa baru (sudah lulus cek 35 titik & bergaransi), PC rakitan siap pakai, sewa laptop harian/bulanan, serta service laptop & sparepart original. Chat WhatsApp 0818880497.',
            'seo_default_keywords' => 'jual laptop second berkualitas, laptop gaming bekas bergaransi, cyber gaming store, sewa laptop murah, service laptop bergaransi, sparepart laptop original, pc gaming rakitan',
            'google_verification' => '',
            'promo_top_bar' => 'Semua Unit Lulus Cek 35 Titik & Bergaransi Resmi/Toko  ·  Bisa Cek Unit di Toko atau Video Call WhatsApp 0818880497',
        ];

        try {
            $stored = Cache::remember('cyber_gaming_settings', 300, function (): array {
                return self::query()->pluck('value', 'key')->map(fn ($v) => (string) $v)->all();
            });

            return array_merge($defaults, array_filter($stored, fn ($v) => $v !== ''));
        } catch (Throwable) {
            return $defaults;
        }
    }

    public static function getValue(string $key, string $default = ''): string
    {
        $all = self::allKeyed();

        return $all[$key] ?? $default;
    }

    public static function setValue(string $key, ?string $value, string $group = 'general'): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value ?? '', 'group' => $group]
        );

        Cache::forget('cyber_gaming_settings');
    }
}
