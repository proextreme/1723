<x-layout>
    {{-- 1 — Hero --}}
    <section class="relative min-h-[100svh] overflow-hidden bg-ink">
        <img src="{{ asset('images/home-hero.jpg') }}" alt="" width="1400" height="1867"
             class="pointer-events-none absolute left-1/2 top-0 h-full max-w-none -translate-x-1/2 object-cover opacity-95 md:left-[24%] md:w-[46%] md:translate-x-0">

        <div class="relative flex min-h-[100svh] flex-col justify-between px-4 pb-6 pt-28 md:px-5 md:pb-10 md:pt-32">
            <div class="mt-auto text-center">
                <p class="text-display">17:23 MAG</p>
                <p class="mt-2 text-statement">An Independent Fashion &amp; Art Magazine</p>
            </div>

            <div class="mt-auto flex flex-col gap-4 md:flex-row md:items-center md:gap-5">
                <p class="text-2xl leading-[0.85] tracking-[-0.04em] md:text-[40px]">
                    Place of Power for Creators All Over the Globe
                </p>
                <div class="grid gap-3 sm:grid-cols-2 md:ml-auto md:flex md:w-auto">
                    <x-cta-button :href="route('submit')" class="w-full md:w-[320px]">Submit Your Work</x-cta-button>
                    <x-cta-button :href="route('partnerships')" class="w-full md:w-[320px]">Explore Partnerships</x-cta-button>
                </div>
            </div>
        </div>
    </section>

    {{-- 2 — Magazine statement + editorial preview --}}
    <section class="bg-ink px-4 py-24 md:px-5 md:py-32">
        <h1 class="max-w-[24ch] text-section normal-case">Work gains value through placement</h1>
        <p class="mt-8 max-w-2xl text-lg font-normal leading-tight tracking-[-0.02em] text-muted md:text-2xl">
            Publishing fashion editorials, cover stories, photography, art and creative projects from
            photographers, stylists, designers and artists worldwide.
        </p>

        @if ($articles->isNotEmpty())
            <ul class="mt-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <li>
                        <a href="{{ route('home') }}" class="group block">
                            <div class="aspect-[3/4] overflow-hidden bg-[#161616]">
                                <x-media-image :media="$article->media->first()" :alt="$article->translation?->title"
                                               class="transition-transform duration-500 group-hover:scale-[1.03]" />
                            </div>
                            <p class="mt-3 text-xl leading-[0.95] tracking-[-0.03em]">
                                {{ $article->translation?->title ?? 'Untitled' }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-16 text-lg font-normal text-muted">The first stories are being prepared.</p>
        @endif

        <div class="mt-12">
            <x-cta-button :href="route('fashion')">Explore Fashion</x-cta-button>
        </div>
    </section>

    {{-- 3 — Front covers --}}
    <section class="bg-ink px-4 py-24 md:px-5 md:py-32">
        <h2 class="text-section normal-case">Front Covers</h2>
        <p class="mt-6 max-w-3xl text-lg font-normal leading-tight tracking-[-0.02em] text-muted md:text-2xl">
            The highest level of placement at 17:23 MAG. Explore front cover opportunities through
            advertorial collaborations and selected partnerships.
        </p>

        <div class="mt-14 grid gap-4 md:grid-cols-3">
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

        <div class="mt-12">
            <x-cta-button :href="route('partnerships')">Explore Partnerships</x-cta-button>
        </div>
    </section>

    {{-- 4 — Print editions --}}
    <section class="bg-ink px-4 py-24 md:px-5 md:py-32">
        <h2 class="text-section normal-case">Print Editions</h2>

        <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($printEditions as $edition)
                <a href="{{ $edition->magcloud_url }}" target="_blank" rel="noopener" class="group block">
                    <div class="aspect-[3/4] overflow-hidden bg-[#161616]">
                        <x-media-image :media="$edition->coverMedia" :alt="$edition->translation?->title"
                                       label="Cover coming soon"
                                       class="transition-transform duration-500 group-hover:scale-[1.03]" />
                    </div>
                    <p class="mt-3 flex items-baseline justify-between text-lg leading-none tracking-[-0.03em]">
                        <span>{{ $edition->translation?->title ?? ('Issue '.$edition->issue_number) }}</span>
                        @if ($edition->is_current)
                            <span class="text-xs uppercase tracking-widest text-muted">Current</span>
                        @endif
                    </p>
                </a>
            @empty
                <p class="text-lg font-normal text-muted">Print editions are on the way.</p>
            @endforelse
        </div>

        <div class="mt-16 max-w-4xl">
            <p class="text-2xl leading-[0.9] tracking-[-0.04em] md:text-[40px]">
                In a fast digital world, print becomes a form of quiet luxury
            </p>
            <p class="mt-6 text-lg font-normal leading-tight tracking-[-0.02em] text-muted md:text-2xl">
                Limited print editions of 17:23 MAG, bringing together fashion editorials, front covers,
                interviews, advertorials and creative photography from the world's leading and emerging
                creative talent.
            </p>
        </div>

        <div class="mt-12">
            <x-cta-button :href="route('print')">View Print Editions</x-cta-button>
        </div>
    </section>

    {{-- 5 — Final CTA --}}
    <section class="bg-ink px-4 py-28 text-center md:px-5 md:py-40">
        <h2 class="text-display normal-case">Enter 17:23 <span class="block">Be seen</span></h2>
        <div class="mt-12 flex justify-center">
            <x-cta-button :href="route('submit')" class="w-full max-w-[740px]">Submit Your Work</x-cta-button>
        </div>
    </section>

    {{-- 6 — Newsletter --}}
    <section class="bg-ink px-4 py-24 text-center md:px-5 md:py-32">
        <h2 class="text-statement normal-case">What's In It For Me?</h2>
        <p class="mx-auto mt-8 max-w-3xl text-lg font-normal leading-tight tracking-[-0.02em] text-muted md:text-2xl">
            The environment around the work defines its value. Where it appears, who it stands next to,
            and how it is presented define its impact. At 17:23, context becomes outcome.
        </p>

        <form method="POST" action="{{ route('newsletter.store') }}"
              class="mx-auto mt-12 flex max-w-[1246px] flex-col gap-3 sm:flex-row">
            @csrf
            <label for="newsletter-email" class="sr-only">Email address</label>
            <input type="email" name="email" id="newsletter-email" required autocomplete="email"
                   placeholder="Enter email address"
                   class="h-[55px] flex-1 border border-paper bg-ink px-5 text-lg font-normal tracking-[-0.02em] text-paper placeholder:text-muted focus-visible:outline-offset-[-4px]">
            <button type="submit"
                    class="h-[55px] shrink-0 bg-paper px-8 text-lg font-bold uppercase tracking-[-0.03em] text-ink sm:w-[320px]">
                Subscribe
            </button>
        </form>

        @if (session('newsletter_status'))
            <p class="mt-4 text-sm font-normal text-paper">{{ session('newsletter_status') }}</p>
        @endif
        @error('email')
            <p class="mt-4 text-sm font-normal text-paper">{{ $message }}</p>
        @enderror

        <p class="mt-6 text-lg font-normal tracking-[-0.02em] text-muted">A closer circle. More access.</p>

        <p class="mt-20 text-section normal-case leading-[0.9]">
            The right place. The right time. The right eyes on your work.
        </p>
    </section>
</x-layout>
