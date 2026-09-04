@props([
    'href' => '#',
    'external' => false,
])

<a
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noopener" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center bg-paper text-ink font-bold uppercase tracking-[-0.04em] px-6 h-[55px] text-lg leading-none transition-transform hover:-translate-y-0.5 focus-visible:outline-offset-[-4px]']) }}
>
    {{ $slot }}
    @if ($external)
        <span aria-hidden="true" class="ml-2">&#8599;</span>
    @endif
</a>
