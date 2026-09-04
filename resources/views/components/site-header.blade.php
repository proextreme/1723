@php
    $nav = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Fashion', 'route' => 'fashion'],
        ['label' => 'Print', 'route' => 'print'],
        ['label' => 'Submit', 'route' => 'submit'],
        ['label' => 'Partnerships', 'route' => 'partnerships'],
    ];
@endphp

<header class="absolute inset-x-0 top-0 z-40 text-paper">
    <div class="flex items-center justify-between px-4 py-5 md:px-5">
        <a href="{{ route('home') }}" aria-label="17:23 MAG — home" class="shrink-0">
            <x-logo class="h-4 md:h-[52px]" />
        </a>

        <nav aria-label="Primary" class="hidden items-center gap-9 md:flex">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   @class([
                       'text-xl font-medium tracking-[-0.02em] transition-opacity hover:opacity-60',
                       'border-b border-paper' => request()->routeIs($item['route']),
                   ])>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <button type="button" data-nav-toggle aria-expanded="false" aria-controls="nav-drawer"
                aria-label="Open menu" class="md:hidden">
            <svg width="19" height="14" viewBox="0 0 19 14" fill="none" aria-hidden="true">
                <path d="M0 1h19M0 7h19M0 13h19" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </button>
    </div>

    <div id="nav-drawer" data-nav-drawer hidden
         class="fixed inset-0 z-50 flex flex-col bg-ink px-4 py-5 md:hidden">
        <div class="flex items-center justify-between">
            <x-logo class="h-4" />
            <button type="button" data-nav-toggle aria-label="Close menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M1 1l18 18M19 1L1 19" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </button>
        </div>

        <nav aria-label="Primary" class="mt-16 flex flex-col items-end gap-8">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}" class="text-2xl font-medium capitalize tracking-[-0.02em]">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-auto flex flex-col gap-3 pt-10">
            <x-cta-button :href="route('submit')" class="w-full">Submit Your Work</x-cta-button>
            <x-cta-button :href="route('partnerships')" class="w-full">Explore Partnerships</x-cta-button>
        </div>
    </div>
</header>
