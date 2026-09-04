@props([
    'href' => '#',
    'external' => false,
    'variant' => 'light', // 'light' = white button (dark sections); 'dark' = black button (light sections)
])

<a
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noopener" @endif
    {{ $attributes->class(['btn', 'btn--dark' => $variant === 'dark']) }}
>
    {{ $slot }}
    @if ($external)
        <span class="btn__arrow" aria-hidden="true">&#8599;</span>
    @endif
</a>
