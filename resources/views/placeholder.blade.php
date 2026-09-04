<x-layout :title="$heading">
    <section class="section placeholder">
        <h1 class="t-section">{{ $heading }}</h1>
        <p class="placeholder__note">
            This page is being built. In the meantime, explore the
            <a href="{{ route('home') }}">home page</a>.
        </p>
    </section>
</x-layout>
