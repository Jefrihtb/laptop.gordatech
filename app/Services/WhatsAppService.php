<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;

class WhatsAppService
{
    public static function cleanNumber(?string $raw = null): string
    {
        $number = $raw ?: Setting::getValue('whatsapp_number', '0818880497');
        $digits = preg_replace('/\D+/', '', $number) ?: '0818880497';

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }

    public static function displayPhone(): string
    {
        return Setting::getValue('whatsapp_number', '0818880497');
    }

    public static function buildUrl(string $message, ?string $customPhone = null): string
    {
        $phone = self::cleanNumber($customPhone);

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    public static function productDirectUrl(Product $product, string $intent = 'beli'): string
    {
        $storeName = Setting::getValue('site_name', 'Cyber Gaming Store');

        if ($intent === 'nego') {
            $message = "Halo Tim {$storeName},\n\n"
                ."Saya ingin menanyakan penawaran harga terbaik dan ketersediaan untuk unit berikut:\n\n"
                ."Nama Unit: {$product->name}\n"
                ."Kode SKU: {$product->sku}\n"
                ."Kondisi: {$product->condition_label}\n"
                ."Harga Katalog: {$product->formattedPrice()}\n"
                .'Tautan Produk: '.route('product.show', $product->slug)."\n\n"
                .'Mohon informasi ketersediaan unit dan opsi penawaran harganya. Terima kasih.';
        } else {
            $message = "Halo Tim {$storeName},\n\n"
                ."Saya tertarik untuk memesan unit berikut:\n\n"
                ."Nama Produk: {$product->name}\n"
                ."Kode SKU: {$product->sku}\n"
                ."Kondisi: {$product->condition_label}\n"
                ."Spesifikasi: {$product->processor} / {$product->ram} / {$product->storage} / {$product->gpu}\n"
                ."Harga: {$product->formattedPrice()}\n"
                .'Tautan Produk: '.route('product.show', $product->slug)."\n\n"
                .'Mohon informasi ketersediaan stok, dokumentasi QC unit, serta prosedur transaksinya. Terima kasih.';
        }

        return self::buildUrl($message);
    }

    public static function consultationUrl(string $topic = 'umum'): string
    {
        $storeName = Setting::getValue('site_name', 'Cyber Gaming Store');

        $message = match ($topic) {
            'sewa' => "Halo Tim Corporate & Rental {$storeName},\nSaya ingin berkonsultasi mengenai layanan penyewaan laptop (Harian / Mingguan / Bulanan). Mohon informasi katalog unit dan penawaran harganya.",
            'service' => "Halo Tim Teknis {$storeName},\nSaya ingin berkonsultasi mengenai perbaikan perangkat / estimasi biaya service.\n- Tipe Perangkat:\n- Detail Kendala:",
            'sparepart' => "Halo Tim Sparepart {$storeName},\nSaya sedang mencari komponen / sparepart laptop atau PC (Baru / Second Original).\n- Tipe Perangkat:\n- Komponen yang dibutuhkan:",
            default => "Halo Tim {$storeName},\nSaya ingin berkonsultasi untuk memilih perangkat laptop / PC yang paling sesuai dengan kebutuhan kerja dan anggaran saya. Mohon rekomendasinya.",
        };

        return self::buildUrl($message);
    }
}
