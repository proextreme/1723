@props([
    'media' => null,
    'alt' => null,
    'label' => 'Image coming soon',
])

@php
    $disk = $media ? \Illuminate\Support\Facades\Storage::disk($media->disk) : null;
    $src = $media && $disk->exists($media->path) ? $disk->url($media->path) : null;
@endphp

@if ($src)
    <img
        class="media__img"
        src="{{ $src }}"
        alt="{{ $alt ?? $media->alt_text ?? '' }}"
        @if ($media->width && $media->height) width="{{ $media->width }}" height="{{ $media->height }}" @endif
        loading="lazy"
        decoding="async"
    >
@else
    <div class="media__fallback">{{ $label }}</div>
@endif
