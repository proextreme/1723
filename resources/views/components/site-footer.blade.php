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

<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <div class="site-footer__mark"><x-logo /></div>
            <p class="site-footer__tagline">An Independent Fashion &amp; Art Magazine</p>
        </div>

        <div class="site-footer__cols">
            <nav aria-label="Footer">
                @foreach ($primary as $item)
                    <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div>
                @if ($instagram)
                    <a href="{{ $instagram->url }}" target="_blank" rel="noopener">Instagram</a>
                @endif
                @if ($email)
                    <a href="{{ $email->url }}">Email</a>
                @endif
                @if ($mediaKitUrl)
                    <a href="{{ $mediaKitUrl }}" target="_blank" rel="noopener">Media Kit</a>
                @endif
            </div>

            <nav aria-label="Legal">
                @foreach ($legal as $item)
                    <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>

        <p class="site-footer__copy">&copy; {{ now()->year }} 17:23 MAG. All rights reserved.</p>
    </div>
</footer>
