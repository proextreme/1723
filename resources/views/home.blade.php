<x-layout>
    {{-- 1 — Hero --}}
    <section class="hero">
        <div class="hero__media">
            <img class="hero__img" src="{{ $home->image('hero_image') ?? asset('images/home-hero.jpg') }}"
                 alt="" width="1400" height="1867">
        </div>

        <div class="hero__inner">
            <div class="hero__headline">
                <p class="t-display">17:23 MAG</p>
                <p class="hero__subtitle t-statement">An Independent Fashion &amp; Art Magazine</p>
            </div>

            <div class="hero__footer">
                <p class="hero__tagline">{{ $home->text('hero_tagline') }}</p>
                <div class="hero__ctas">
                    <x-cta-button :href="route('submit')">Submit Your Work</x-cta-button>
                    <x-cta-button :href="route('partnerships')">Explore Partnerships</x-cta-button>
                </div>
            </div>
        </div>
    </section>

    {{-- 2 — Statement + editorial preview --}}
    <section class="section statement">
        <h1 class="statement__heading t-section">{{ $home->text('statement_heading') }}</h1>
        <p class="statement__body t-standfirst">{{ $home->text('statement_body') }}</p>

        @if ($gallery->isNotEmpty())
            <ul class="statement__grid">
                @foreach ($gallery as $item)
                    <li>
                        @if ($item->url)
                            <a class="statement__tile" href="{{ $item->url }}" target="_blank" rel="noopener">
                                <img src="{{ $item->publicUrl() }}" alt="{{ $item->alt }}" loading="lazy" decoding="async">
                            </a>
                        @else
                            <div class="statement__tile">
                                <img src="{{ $item->publicUrl() }}" alt="{{ $item->alt }}" loading="lazy" decoding="async">
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @elseif ($articles->isNotEmpty())
            <ul class="statement__grid">
                @foreach ($articles as $article)
                    <li>
                        <a class="statement__tile" href="{{ route('fashion') }}"
                           aria-label="{{ $article->translation?->title ?? 'Untitled article' }}">
                            <x-media-image :media="$article->media->first()" :alt="$article->translation?->title" />
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="statement__empty">The first stories are being prepared.</p>
        @endif

        <div class="section__cta">
            <x-cta-button :href="route('fashion')" variant="dark">More Fashion</x-cta-button>
        </div>
    </section>

    {{-- 3 — Front covers --}}
    <section class="section section--dark covers">
        <h2 class="t-section">{{ $home->text('covers_heading') }}</h2>
        <p class="covers__body t-standfirst">{{ $home->text('covers_body') }}</p>

        <div class="slider covers__slider" data-slider>
            <button type="button" class="slider__arrow slider__arrow--prev" data-slider-prev aria-label="Previous cover">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
            </button>

            <div class="slider__viewport" data-slider-viewport>
                <ul class="slider__track" data-slider-track>
                    @if ($coverImages->isNotEmpty())
                        @foreach ($coverImages as $image)
                            <li class="slider__slide">
                                @if ($image->url)
                                    <a class="covers__tile" href="{{ $image->url }}" target="_blank" rel="noopener">
                                        <img class="media__img" src="{{ $image->publicUrl() }}" alt="{{ $image->alt }}" loading="lazy" decoding="async">
                                    </a>
                                @else
                                    <div class="covers__tile">
                                        <img class="media__img" src="{{ $image->publicUrl() }}" alt="{{ $image->alt }}" loading="lazy" decoding="async">
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    @elseif ($covers->isNotEmpty())
                        @foreach ($covers as $cover)
                            <li class="slider__slide">
                                <div class="covers__tile">
                                    <x-media-image :media="$cover->media->first()" :alt="$cover->translation?->title" label="Cover coming soon" />
                                </div>
                            </li>
                        @endforeach
                    @else
                        @for ($i = 0; $i < 3; $i++)
                            <li class="slider__slide"><div class="covers__tile"></div></li>
                        @endfor
                    @endif
                </ul>
            </div>

            <button type="button" class="slider__arrow slider__arrow--next" data-slider-next aria-label="Next cover">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <div class="section__cta">
            <x-cta-button :href="route('partnerships')">Apply for a Cover</x-cta-button>
        </div>
    </section>

    {{-- 4 — Print editions teaser --}}
    <section class="section print">
        <h2 class="t-headline">{{ $home->text('print_heading') }}</h2>

        <figure class="print__figure media">
            @if ($home->image('print_image'))
                <img class="media__img" src="{{ $home->image('print_image') }}" alt="17:23 MAG print edition"
                     loading="lazy" decoding="async">
            @else
                <div class="media__fallback">Print feature coming soon</div>
            @endif
        </figure>

        <div class="print__copy">
            <p class="print__quote t-lead">{{ $home->text('print_quote') }}</p>
            <p class="print__body t-standfirst">{{ $home->text('print_body') }}</p>
        </div>

        <div class="section__cta">
            <x-cta-button :href="route('print')" variant="dark">View Print Editions</x-cta-button>
        </div>
    </section>

    {{-- 5 — Enter 17:23 Be Seen --}}
    <section class="beseen">
        @if ($home->image('beseen_image'))
            <img class="beseen__img" src="{{ $home->image('beseen_image') }}" alt="" loading="lazy" decoding="async">
        @endif
        <div class="beseen__inner">
            <h2 class="t-headline">{{ $home->text('beseen_heading') }}</h2>
            <div class="beseen__cta">
                <x-cta-button :href="route('submit')">Submit Your Work</x-cta-button>
            </div>
        </div>
    </section>

    {{-- 6 — Newsletter --}}
    <section class="section newsletter">
        <h2 class="t-section">{{ $home->text('newsletter_heading') }}</h2>
        <p class="newsletter__body">{{ $home->text('newsletter_body') }}</p>

        <form class="newsletter__form" method="POST" action="{{ route('newsletter.store') }}">
            @csrf
            <label for="newsletter-email" class="sr-only">Email address</label>
            <input class="newsletter__input" type="email" name="email" id="newsletter-email" required
                   autocomplete="email" placeholder="Enter email address">
            <button class="newsletter__submit" type="submit">Stay Close</button>
        </form>

        @if (session('newsletter_status'))
            <p class="newsletter__note">{{ session('newsletter_status') }}</p>
        @endif
        @error('email')
            <p class="newsletter__note newsletter__note--error">{{ $message }}</p>
        @enderror

        <p class="newsletter__access">A closer circle. More access.</p>
        <p class="newsletter__closing">{{ $home->text('newsletter_tagline') }}</p>
    </section>
</x-layout>
