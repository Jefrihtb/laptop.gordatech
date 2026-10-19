<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ticket_code',
    'customer_name',
    'customer_phone',
    'device_type',
    'device_brand_model',
    'serial_number',
    'problem_description',
    'diagnosis_notes',
    'status',
    'estimated_cost',
    'final_cost',
    'technician_name',
    'warranty_until',
])]
class ServiceTicket extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estimated_cost' => 'integer',
            'final_cost' => 'integer',
            'warranty_until' => 'date',
        ];
    }

    public function matchesPhoneLastDigits(string $inputPhoneLastDigits): bool
    {
        $ticketDigits = preg_replace('/\D+/', '', (string) $this->customer_phone) ?? '';
        $inputDigits = preg_replace('/\D+/', '', $inputPhoneLastDigits) ?? '';

        if (strlen($ticketDigits) < 4 || strlen($inputDigits) < 4) {
            return false;
        }

        $ticketLast4 = substr($ticketDigits, -4);
        $inputLast4 = substr($inputDigits, -4);

        return hash_equals($ticketLast4, $inputLast4);
    }

    public function maskedCustomerName(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->customer_name)) ?: [];
        if ($parts === []) {
            return 'Pelanggan';
        }

        $first = $parts[0];
        if (count($parts) === 1) {
            return mb_substr($first, 0, min(3, mb_strlen($first))).'***';
        }

        $secondInitial = mb_substr($parts[1], 0, 1);

        return "{$first} {$secondInitial}***";
    }

    public function maskedCustomerPhone(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->customer_phone) ?? '';
        $last4 = substr($digits, -4);

        return '****-****-'.$last4;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'received' => 'Unit Diterima di Workshop',
            'diagnosing' => 'Sedang Pengecekan & Diagnosa',
            'waiting_approval' => 'Menunggu Konfirmasi Biaya',
            'in_progress' => 'Sedang Proses Pengerjaan Service',
            'qc_testing' => 'Tahap Stress-Test & Quality Control',
            'ready_pickup' => 'Selesai - Siap Diambil / Dikirim',
            'completed' => 'Selesai & Sudah Diserahkan',
            default => 'Dalam Proses',
        };
    }

    public function statusProgressPercentage(): int
    {
        return match ($this->status) {
            'received' => 15,
            'diagnosing' => 30,
            'waiting_approval' => 45,
            'in_progress' => 65,
            'qc_testing' => 85,
            'ready_pickup' => 100,
            'completed' => 100,
            default => 20,
        };
    }
}
