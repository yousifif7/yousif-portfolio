<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = $siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name');
        $metaTitle = $siteSettings['seo_meta_title'] ?? null;
        $metaDescription = $siteSettings['seo_meta_description'] ?? $siteAbout?->short_bio ?? 'Portfolio';
        $ogImage = isset($siteSettings['seo_og_image']) ? \App\Support\PublicUpload::url($siteSettings['seo_og_image']) : null;
        $favicon = isset($siteSettings['site_favicon']) ? \App\Support\PublicUpload::url($siteSettings['site_favicon']) : null;
        $pageTitle = trim($__env->yieldContent('title', $metaTitle ?? $siteName));
        $pageDescription = trim($__env->yieldContent('description', $metaDescription));
        $canonicalUrl = $__env->yieldContent('canonical', url()->current());
        $pageOgImage = trim($__env->yieldContent('og_image')) ?: $ogImage;
        $pageOgType = trim($__env->yieldContent('og_type')) ?: 'website';
        $siteCssVer = @filemtime(public_path('css/site.css')) ?: 1;
        $faVer = @filemtime(public_path('vendor/fontawesome/css/fontawesome.min.css')) ?: 1;
        $criticalCss = @file_get_contents(public_path('css/critical.css')) ?: '';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="{{ $pageOgType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="en_US">
    @if($pageOgImage)
        <meta property="og:image" content="{{ $pageOgImage }}">
    @endif

    <meta name="twitter:card" content="{{ $pageOgImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if($pageOgImage)
        <meta name="twitter:image" content="{{ $pageOgImage }}">
    @endif

    @hasSection('robots')
        <meta name="robots" content="@yield('robots')">
    @endif

    @if(!empty($siteSettings['seo_google_site_verification']))
        <meta name="google-site-verification" content="{{ $siteSettings['seo_google_site_verification'] }}">
    @endif

    @if($favicon)
        <link rel="icon" href="{{ $favicon }}">
    @endif

    @stack('preload')

    <link rel="preload" href="{{ asset('fonts/inter-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    @if($criticalCss)
        <style>{!! $criticalCss !!}</style>
    @endif

    <link rel="preload" href="{{ asset('css/site.css') }}?v={{ $siteCssVer }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ $siteCssVer }}"></noscript>

    <link rel="preload" href="{{ asset('vendor/fontawesome/css/fontawesome.min.css') }}?v={{ $faVer }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('vendor/fontawesome/css/solid.min.css') }}?v={{ $faVer }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('vendor/fontawesome/css/brands.min.css') }}?v={{ $faVer }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/fontawesome.min.css') }}?v={{ $faVer }}">
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/solid.min.css') }}?v={{ $faVer }}">
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/brands.min.css') }}?v={{ $faVer }}">
    </noscript>

    @include('site.partials.brand-colors')

    @stack('styles')
    @stack('head')
</head>
<body>
    @include('site.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('site.partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.nav-toggle');
            const links = document.querySelector('.nav-links');
            if (toggle && links) {
                toggle.addEventListener('click', () => links.classList.toggle('open'));
                links.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => links.classList.remove('open'));
                });
            }

            const bars = document.querySelectorAll('.skill-bar .fill');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        const w = e.target.dataset.width;
                        e.target.style.width = w + '%';
                    }
                });
            });
            bars.forEach(b => observer.observe(b));
        });
    </script>
    @stack('scripts')
</body>
</html>
