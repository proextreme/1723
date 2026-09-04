<x-layout>
    {{-- 1 — Hero (dark) --}}
    <section class="relative flex min-h-[100svh] flex-col bg-ink text-paper">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <img src="{{ asset('images/home-hero.jpg') }}" alt="" width="1400" height="1867"
                 class="absolute left-1/2 top-0 h-full max-w-none -translate-x-1/2 object-cover opacity-95 md:left-[24%] md:w-[46%] md:translate-x-0">
        </div>

        <div class="relative flex flex-1 flex-col px-4 pb-28 pt-28 md:px-5 md:pb-16 md:pt-32">
            <div class="my-auto text-center">
                <p class="text-display">17:23 MAG</p>
                <p class="mt-6 text-statement">An Independent Fashion &amp; Art Magazine</p>
            </div>

            <div class="grid gap-4 border-t border-paper pt-5 md:grid-cols-[minmax(0,1fr)_auto] md:items-end md:gap-8">
                <p class="text-2xl leading-[0.95] tracking-[-0.04em] md:text-[32px]">
                    Place of Power for Creators All Over the Globe
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
        <h1 class="max-w-[14ch] text-section">Work gains value through placement</h1>
        <p class="mt-10 max-w-6xl text-standfirst text-muted">
            Publishing fashion editorials, cover stories, photography, art and creative projects from
            photographers, stylists, designers and artists worldwide.
        </p>

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
        <h2 class="text-section">Front Covers</h2>
        <p class="mt-10 max-w-6xl text-standfirst text-muted">
            The highest level of placement at 17:23 MAG. Explore front cover opportunities through
            advertorial collaborations and selected partnerships.
        </p>

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
        <h2 class="text-headline">Print Editions</h2>

        <div class="mt-12 ml-auto aspect-[4/3] w-full max-w-[1200px] overflow-hidden bg-[#ededed] text-ink md:w-[86%]">
            <x-media-image :media="$printEditions->firstWhere('is_current')?->coverMedia ?? $printEditions->first()?->coverMedia"
                           alt="Latest 17:23 MAG print edition" label="Print feature coming soon" />
        </div>

        <div class="mt-14">
            <p class="max-w-[30ch] text-balance text-lead capitalize">
                In a fast digital world, print becomes a form of quiet luxury
            </p>
            <p class="mt-6 max-w-6xl text-standfirst text-muted">
                Limited print editions of 17:23 MAG, bringing together fashion editorials, front covers,
                interviews, advertorials and creative photography from the world's leading and emerging
                creative talent.
            </p>
        </div>

        <div class="mt-12 flex justify-center">
            <x-cta-button :href="route('print')" variant="dark" class="w-full max-w-[740px]">View Print Editions</x-cta-button>
        </div>
    </section>

    {{-- 5 — Final CTA (dark) --}}
    <section class="bg-ink px-4 py-28 text-center text-paper md:px-5 md:py-40">
        <h2 class="text-headline">Enter 17:23 Be Seen</h2>
        <div class="mt-12 flex justify-center">
            <x-cta-button :href="route('submit')" class="w-full max-w-[740px]">Submit Your Work</x-cta-button>
        </div>
    </section>

    {{-- 6 — Newsletter (light) --}}
    <section class="bg-paper px-4 py-20 text-center text-ink md:px-5 md:py-24">
        <h2 class="text-section">What's In It For Me?</h2>
        <p class="mx-auto mt-10 max-w-5xl text-2xl leading-[1.05] tracking-[-0.04em] md:text-[42px]">
            The environment around the work defines its value. Where it appears, who it stands next to,
            and how it is presented define its impact. At 17:23, context becomes outcome.
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

        <p class="mx-auto mt-20 flex max-w-6xl flex-col items-center gap-y-1 text-2xl uppercase leading-[0.9] tracking-[-0.04em] text-muted md:flex-row md:flex-wrap md:justify-center md:gap-x-12 md:text-[57px]">
            <span class="md:whitespace-nowrap">The right place</span>
            <span class="md:whitespace-nowrap">The right time</span>
            <span class="md:whitespace-nowrap">The right eyes on your work</span>
        </p>
    </section>
</x-layout>
