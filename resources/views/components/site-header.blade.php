@php
    $nav = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Fashion', 'route' => 'fashion'],
        ['label' => 'Print', 'route' => 'print'],
        ['label' => 'Submit', 'route' => 'submit'],
        ['label' => 'Partnerships', 'route' => 'partnerships'],
    ];
@endphp

<header class="site-header">
    <div class="site-header__bar">
        <a href="{{ route('home') }}" class="site-header__logo" aria-label="17:23 MAG — home">
            <x-logo />
        </a>

        <nav class="nav" aria-label="Primary">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="nav__link @if (request()->routeIs($item['route'])) nav__link--active @endif">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <button type="button" class="nav-toggle" data-nav-toggle="open"
                aria-expanded="false" aria-controls="nav-drawer" aria-label="Open menu">
            <svg width="19" height="14" viewBox="0 0 19 14" fill="none" aria-hidden="true">
                <path d="M0 1h19M0 7h19M0 13h19" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </button>
    </div>

    <div id="nav-drawer" class="nav-drawer" data-nav-drawer hidden>
        <div class="nav-drawer__top">
            <x-logo />
            <button type="button" data-nav-toggle="close" aria-label="Close menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M1 1l18 18M19 1L1 19" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </button>
        </div>

        <nav class="nav-drawer__list" aria-label="Primary">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="nav-drawer__ctas">
            <x-cta-button :href="route('submit')" class="btn--block">Submit Your Work</x-cta-button>
            <x-cta-button :href="route('partnerships')" class="btn--block">Explore Partnerships</x-cta-button>
        </div>
    </div>
</header>
