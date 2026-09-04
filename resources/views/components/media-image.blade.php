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
        src="{{ $src }}"
        alt="{{ $alt ?? $media->alt_text ?? '' }}"
        @if ($media->width && $media->height) width="{{ $media->width }}" height="{{ $media->height }}" @endif
        loading="lazy"
        decoding="async"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-center justify-center bg-[#161616] text-[11px] font-normal uppercase tracking-widest text-muted']) }}>
        {{ $label }}
    </div>
@endif
