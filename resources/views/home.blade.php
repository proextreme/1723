<x-layout>
    {{-- 1 — Hero (dark) --}}
    <section class="relative flex min-h-[100svh] flex-col bg-ink text-paper">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <img src="{{ $home->image('hero_image') ?? asset('images/home-hero.jpg') }}" alt="" width="1400" height="1867"
                 class="absolute left-1/2 top-0 h-full w-full max-w-none -translate-x-1/2 object-contain object-top opacity-95 md:left-[24%] md:w-[46%] md:translate-x-0 md:object-cover md:object-center">
        </div>

        <div class="relative flex flex-1 flex-col px-4 pb-28 pt-28 md:px-5 md:pb-16 md:pt-32">
            <div class="my-auto text-center">
                <p class="text-display">17:23 MAG</p>
                <p class="mt-6 text-statement">An Independent Fashion &amp; Art Magazine</p>
            </div>

            <div class="grid gap-4 border-t border-paper pt-5 md:grid-cols-[minmax(0,1fr)_auto] md:items-end md:gap-8">
                <p class="text-2xl leading-[0.95] tracking-[-0.04em] md:text-[32px]">
                    {{ $home->text('hero_tagline') }}
                </p>
                <div class="grid gap-3 sm:grid-cols-2 md:flex md:shrink-0">
                    <x-cta-button :href="route('submit')" class="w-full sm:w-[280px]">Submit Your Work</x-cta-button>
                    <x-cta-button :href="route('partnerships')" class="w-full sm:w-[280px]">Explore Partnerships</x-cta-button>
                </div>
            </div>
        </div>
    </section>

    {{-- 2 — Magazine statement + editorial preview (light) --}}
    <section class="bg-paper px-4 py-20 text-ink md:px-5 md:py-24">
        <h1 class="max-w-[14ch] text-section">{{ $home->text('statement_heading') }}</h1>
        <p class="mt-10 max-w-6xl text-standfirst text-muted">{{ $home->text('statement_body') }}</p>

        @if ($articles->isNotEmpty())
            <ul class="-mx-4 mt-14 grid grid-cols-2 gap-[2px] md:-mx-5 md:grid-cols-3">
                @foreach ($articles as $article)
                    <li>
                        <a href="{{ route('fashion') }}" class="group block aspect-[3/4] overflow-hidden bg-[#ededed] text-ink"
                           aria-label="{{ $article->translation?->title ?? 'Untitled article' }}">
                            <x-media-image :media="$article->media->first()" :alt="$article->translation?->title"
                                           class="transition-transform duration-500 group-hover:scale-[1.03]" />
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-14 text-lg font-normal text-muted">The first stories are being prepared.</p>
        @endif

        <div class="mt-12 flex justify-center">
            <x-cta-button :href="route('fashion')" variant="dark" class="w-full max-w-[740px]">More Fashion</x-cta-button>
        </div>
    </section>

    {{-- 3 — Front covers (dark) --}}
    <section class="bg-ink px-4 py-20 text-paper md:px-5 md:py-24">
        <h2 class="text-section">{{ $home->text('covers_heading') }}</h2>
        <p class="mt-10 max-w-6xl text-standfirst text-muted">{{ $home->text('covers_body') }}</p>

        <div class="mt-14 grid gap-5 md:grid-cols-3">
            @forelse ($covers as $cover)
                <div class="aspect-[3/4] overflow-hidden bg-[#161616]">
                    <x-media-image :media="$cover->media->first()" :alt="$cover->translation?->title" label="Cover coming soon" />
                </div>
            @empty
                @for ($i = 0; $i < 3; $i++)
                    <div class="aspect-[3/4] bg-[#161616]"></div>
                @endfor
            @endforelse
        </div>

        <div class="mt-14 flex justify-center">
            <x-cta-button :href="route('partnerships')" class="w-full max-w-[740px]">Apply for a Cover</x-cta-button>
        </div>
    </section>

    {{-- 4 — Print editions teaser (light) --}}
    <section class="bg-paper px-4 py-20 text-ink md:px-5 md:py-24">
        <h2 class="text-headline">{{ $home->text('print_heading') }}</h2>

        <div class="mt-12 ml-auto aspect-[4/3] w-full max-w-[1200px] overflow-hidden bg-[#ededed] md:w-[86%]">
            @if ($home->image('print_image'))
                <img src="{{ $home->image('print_image') }}" alt="17:23 MAG print edition"
                     class="h-full w-full object-cover" loading="lazy" decoding="async">
            @else
                <div class="flex h-full w-full items-center justify-center text-[11px] font-normal uppercase tracking-widest text-muted">
                    Print feature coming soon
                </div>
            @endif
        </div>

        <div class="mt-14">
            <p class="max-w-[30ch] text-balance text-lead capitalize">{{ $home->text('print_quote') }}</p>
            <p class="mt-6 max-w-6xl text-standfirst text-muted">{{ $home->text('print_body') }}</p>
        </div>

        <div class="mt-12 flex justify-center">
            <x-cta-button :href="route('print')" variant="dark" class="w-full max-w-[740px]">View Print Editions</x-cta-button>
        </div>
    </section>

    {{-- 5 — Final CTA (dark, optional background image) --}}
    <section class="relative overflow-hidden bg-ink px-4 py-28 text-center text-paper md:px-5 md:py-40">
        @if ($home->image('beseen_image'))
            <img src="{{ $home->image('beseen_image') }}" alt=""
                 class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-60" loading="lazy" decoding="async">
        @endif
        <div class="relative">
            <h2 class="text-headline">{{ $home->text('beseen_heading') }}</h2>
            <div class="mt-12 flex justify-center">
                <x-cta-button :href="route('submit')" class="w-full max-w-[740px]">Submit Your Work</x-cta-button>
            </div>
        </div>
    </section>

    {{-- 6 — Newsletter (light) --}}
    <section class="bg-paper px-4 py-20 text-center text-ink md:px-5 md:py-24">
        <h2 class="text-section">{{ $home->text('newsletter_heading') }}</h2>
        <p class="mx-auto mt-10 max-w-5xl text-2xl leading-[1.05] tracking-[-0.04em] md:text-[42px]">
            {{ $home->text('newsletter_body') }}
        </p>

        <form method="POST" action="{{ route('newsletter.store') }}"
              class="mx-auto mt-14 flex max-w-[1246px] flex-col gap-3 sm:flex-row">
            @csrf
            <label for="newsletter-email" class="sr-only">Email address</label>
            <input type="email" name="email" id="newsletter-email" required autocomplete="email"
                   placeholder="Enter email address"
                   class="h-[55px] flex-1 border border-ink bg-paper px-5 text-lg font-normal tracking-[-0.02em] text-ink placeholder:text-muted">
            <button type="submit"
                    class="h-[55px] shrink-0 bg-ink px-8 text-lg font-bold uppercase tracking-[-0.03em] text-paper sm:w-[320px]">
                Stay Close
            </button>
        </form>

        @if (session('newsletter_status'))
            <p class="mt-4 text-sm font-normal">{{ session('newsletter_status') }}</p>
        @endif
        @error('email')
            <p class="mt-4 text-sm font-normal text-[#b00]">{{ $message }}</p>
        @enderror

        <p class="mt-6 text-2xl font-normal tracking-[-0.03em] text-muted">A closer circle. More access.</p>

        <p class="mx-auto mt-20 max-w-6xl text-2xl uppercase leading-[0.95] tracking-[-0.04em] text-muted md:text-[57px]">
            {{ $home->text('newsletter_tagline') }}
        </p>
    </section>
</x-layout>
