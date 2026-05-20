<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $pageTitle = trim(View::yieldContent('title', 'Portfolio'));
    $appName = config('app.name', 'Gabriel');
    $fullTitle = $pageTitle . ' - ' . $appName;
    $description = trim(View::yieldContent('description', 'O laboratório de um desenvolvedor full stack em constante compilação'));
    $canonical = trim(View::yieldContent('canonical')) ?: url()->current();
    $ogImage = trim(View::yieldContent('og_image')) ?: asset('og-default.png');
    $ogType = trim(View::yieldContent('og_type')) ?: 'website';
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $appName }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="pt_BR">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@yield('extra_head')

<link rel="preload" as="font" type="font/woff2" href="/fonts/google-sans-flex.woff2" crossorigin>

<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="{{ $appName }}" />
<link rel="manifest" href="/site.webmanifest" />

{{-- Feeds --}}
<link rel="alternate" type="application/rss+xml" title="{{ $appName }} — RSS" href="{{ url('/feed.xml') }}" />
<link rel="alternate" type="application/atom+xml" title="{{ $appName }} — Atom" href="{{ url('/atom.xml') }}" />
<link rel="alternate" type="application/json" title="{{ $appName }} — JSON Feed" href="{{ url('/feed.json') }}" />

{{-- View Transitions --}}
<meta name="view-transition" content="same-origin" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('scripts')
@stack('jsonld')
@stack('extra_head')

@include('partials.google-analytics')
@include('partials.adsense')
