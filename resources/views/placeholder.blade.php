<x-layout :title="$heading">
    <section class="flex min-h-[70vh] flex-col justify-center px-4 pb-24 pt-40 md:px-10">
        <h1 class="text-section">{{ $heading }}</h1>
        <p class="mt-6 max-w-xl text-lg font-normal tracking-[-0.02em] text-muted">
            This page is being built. In the meantime, explore the
            <a href="{{ route('home') }}" class="text-paper underline">home page</a>.
        </p>
    </section>
</x-layout>
