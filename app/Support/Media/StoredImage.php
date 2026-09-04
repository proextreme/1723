<?php

namespace App\Support\Media;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Derives the `media` row attributes for a file that Filament's FileUpload has
 * already stored on a disk.
 */
class StoredImage
{
    /**
     * @return array<string, mixed>
     */
    public static function attributes(string $path, ?string $originalName = null, string $disk = 'public'): array
    {
        $storage = Storage::disk($disk);
        $dimensions = @getimagesize($storage->path($path)) ?: [null, null];

        return [
            'disk' => $disk,
            'path' => $path,
            'original_name' => $originalName ?? basename($path),
            'mime_type' => $storage->mimeType($path) ?: 'application/octet-stream',
            'size' => $storage->size($path),
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_by' => Auth::id(),
        ];
    }
}
