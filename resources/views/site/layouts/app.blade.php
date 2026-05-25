<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteAbout?->full_name ?? config('app.name'))</title>
    <meta name="description" content="@yield('description', $siteAbout?->short_bio ?? 'Portfolio')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('styles')
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
