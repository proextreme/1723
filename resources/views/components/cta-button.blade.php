@props([
    'href' => '#',
    'external' => false,
    'variant' => 'light', // 'light' = white button (on dark sections); 'dark' = black button (on light sections)
])

@php
    $palette = $variant === 'dark'
        ? 'bg-ink text-paper'
        : 'bg-paper text-ink';
@endphp

<a
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noopener" @endif
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center $palette h-[55px] px-6 text-xl font-bold uppercase leading-none tracking-[-0.04em] transition-transform hover:-translate-y-0.5 focus-visible:outline-offset-[-4px]"]) }}
>
    {{ $slot }}
    @if ($external)
        <span aria-hidden="true" class="ml-2">&#8599;</span>
    @endif
</a>
