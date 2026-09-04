@php
    use App\Models\SiteLink;

    $links = SiteLink::query()->where('is_active', true)->with('media')->get()->keyBy('key');

    $instagram = $links->get('instagram');
    $email = $links->get('email');
    $mediaKit = $links->get('media_kit');

    $primary = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Fashion', 'route' => 'fashion'],
        ['label' => 'Print', 'route' => 'print'],
        ['label' => 'Submit', 'route' => 'submit'],
        ['label' => 'Partnerships', 'route' => 'partnerships'],
    ];

    $legal = [
        ['label' => 'Privacy Policy', 'route' => 'legal.privacy'],
        ['label' => 'Cookie Policy', 'route' => 'legal.cookies'],
        ['label' => 'Terms & Conditions', 'route' => 'legal.terms'],
        ['label' => 'FAQ', 'route' => 'faq'],
    ];

    $mediaKitUrl = $mediaKit?->media
        ? \Illuminate\Support\Facades\Storage::disk($mediaKit->media->disk)->url($mediaKit->media->path)
        : null;
@endphp

<footer class="bg-ink px-4 pb-6 pt-20 text-paper md:px-10">
    <div class="mx-auto flex max-w-[1628px] flex-col items-center gap-12">
        <div class="flex w-full flex-col items-center gap-8">
            <x-logo class="h-24 w-full max-w-[1194px] md:h-40" title="17:23 MAG" />
            <p class="text-center text-2xl uppercase tracking-[-0.04em] md:text-[57px] md:leading-[0.85]">
                An Independent Fashion &amp; Art Magazine
            </p>
        </div>

        <div class="flex w-full flex-col gap-8 text-lg font-normal tracking-[-0.03em] md:flex-row md:flex-wrap md:items-center md:justify-between md:text-2xl">
            <nav aria-label="Footer" class="flex flex-wrap gap-x-7 gap-y-2">
                @foreach ($primary as $item)
                    <a href="{{ route($item['route']) }}" class="hover:opacity-60">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex flex-wrap gap-x-7 gap-y-2">
                @if ($instagram)
                    <a href="{{ $instagram->url }}" target="_blank" rel="noopener" class="hover:opacity-60">Instagram</a>
                @endif
                @if ($email)
                    <a href="{{ $email->url }}" class="hover:opacity-60">Email</a>
                @endif
                @if ($mediaKitUrl)
                    <a href="{{ $mediaKitUrl }}" target="_blank" rel="noopener" class="hover:opacity-60">Media Kit</a>
                @endif
            </div>

            <nav aria-label="Legal" class="flex flex-wrap gap-x-7 gap-y-2">
                @foreach ($legal as $item)
                    <a href="{{ route($item['route']) }}" class="hover:opacity-60">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>

        <p class="w-full text-right text-sm tracking-[-0.03em] text-muted md:text-lg">
            &copy; {{ now()->year }} 17:23 MAG. All rights reserved.
        </p>
    </div>
</footer>
