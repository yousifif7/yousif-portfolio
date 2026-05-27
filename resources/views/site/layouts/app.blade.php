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

    @if($favicon)
        <link rel="icon" href="{{ $favicon }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

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
        // Mobile nav toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.nav-toggle');
            const links = document.querySelector('.nav-links');
            if (toggle) {
                toggle.addEventListener('click', () => links.classList.toggle('open'));
            }

            // Animate skill bars when visible
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
