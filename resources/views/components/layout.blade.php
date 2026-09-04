@props([
    'title' => null,
    'description' => null,
])

@php
    $pageTitle = $title ? $title.' — 17:23 MAG' : '17:23 MAG — An Independent Fashion & Art Magazine';
    $pageDescription = $description ?? 'An independent fashion & art magazine. A place of power for creators all over the globe.';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a href="#main" class="skip-link">Skip to content</a>

    <x-site-header />

    <main id="main">
        {{ $slot }}
    </main>

    <x-site-footer />

    <x-cookie-consent />
</body>
</html>
