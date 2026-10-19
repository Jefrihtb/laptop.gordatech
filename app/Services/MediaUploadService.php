<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaUploadService
{
    /**
     * Store an uploaded file, a client-compressed data URI, or an external URL directly into public/uploads
     * so shared hosting never needs storage:link symlinks.
     */
    public function handleMedia(?UploadedFile $file, ?string $externalOrDataUrl, string $folder = 'products'): ?string
    {
        if (! empty($externalOrDataUrl) && Str::startsWith($externalOrDataUrl, 'data:image/')) {
            return $this->storeBase64Image($externalOrDataUrl, $folder);
        }

        if ($file instanceof UploadedFile && $file->isValid()) {
            return $this->storeUploadedFile($file, $folder);
        }

        if (! empty($externalOrDataUrl)) {
            return trim($externalOrDataUrl);
        }

        return null;
    }

    /**
     * Process multiple gallery slots (each slot can have a file upload, compressed data URI, or external URL).
     * Guarantees at least 3 image entries when fallback is provided.
     *
     * @param  array<int, UploadedFile|null>  $files
     * @param  array<int, string|null>  $urls
     * @param  array<int, string>  $existingImages
     * @return array<int, string>
     */
    public function handleGallery(array $files, array $urls, array $existingImages = [], string $folder = 'products'): array
    {
        $gallery = [];
        $maxSlots = max(count($files), count($urls), count($existingImages), 3);

        for ($i = 0; $i < $maxSlots; $i++) {
            $uploaded = $files[$i] ?? null;
            $urlInput = $urls[$i] ?? null;
            $existing = $existingImages[$i] ?? null;

            $resolved = $this->handleMedia($uploaded, $urlInput, $folder);
            if ($resolved) {
                $gallery[] = $resolved;
            } elseif (! empty($existing)) {
                $gallery[] = $existing;
            }
        }

        $gallery = array_values(array_filter(array_unique($gallery)));

        if (empty($gallery)) {
            $gallery = [
                'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=900&q=80',
            ];
        }

        while (count($gallery) < 3) {
            $gallery[] = $gallery[0];
        }

        return $gallery;
    }

    public function storeUploadedFile(UploadedFile $file, string $folder = 'products'): string
    {
        $targetDir = public_path('uploads/'.trim($folder, '/'));
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $safeExt = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'webm', 'svg'], true) ? $extension : 'jpg';
        $filename = date('Ymd_His').'_'.Str::lower(Str::random(8)).'.'.$safeExt;

        $file->move($targetDir, $filename);

        return 'uploads/'.trim($folder, '/').'/'.$filename;
    }

    public function storeBase64Image(string $dataUri, string $folder = 'products'): ?string
    {
        if (! preg_match('/^data:image\/(\w+);base64,/', $dataUri, $matches)) {
            return null;
        }

        $extension = strtolower($matches[1]);
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        $encodedData = substr($dataUri, strpos($dataUri, ',') + 1);
        $decodedData = base64_decode($encodedData, true);

        if ($decodedData === false) {
            return null;
        }

        $targetDir = public_path('uploads/'.trim($folder, '/'));
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filename = date('Ymd_His').'_'.Str::lower(Str::random(8)).'.'.$extension;
        file_put_contents($targetDir.'/'.$filename, $decodedData);

        return 'uploads/'.trim($folder, '/').'/'.$filename;
    }
}
