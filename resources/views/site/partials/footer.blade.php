@php
    $footerName = $siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name');
    $footerTagline = $siteSettings['site_tagline'] ?? $siteAbout?->tagline ?? 'Backend developer building reliable APIs and CRMs.';
@endphp
<footer class="footer">
    <div class="container">
        <div class="footer-inner">
            <div>
                <h4>{{ $footerName }}</h4>
                <p style="margin-top: 0.5rem; max-width: 380px;">
                    {{ $footerTagline }}
                </p>
                <div style="margin-top: 1.25rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    @include('site.partials.social-icons')
                </div>
            </div>

            <div>
                <h4>Navigation</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('projects.index') }}">Projects</a>
                <a href="{{ route('hire') }}">Hire Me</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>

            <div>
                <h4>Get in touch</h4>
                @if($siteAbout?->email)
                    <a href="mailto:{{ $siteAbout->email }}"><i class="fas fa-envelope"></i> {{ $siteAbout->email }}</a>
                @endif
                @if($siteAbout?->location)
                    <a><i class="fas fa-map-marker-alt"></i> {{ $siteAbout->location }}</a>
                @endif
            </div>
        </div>

        <div class="footer-bottom">
            &copy; {{ date('Y') }} {{ $footerName }}. All rights reserved. Built with Laravel.
        </div>
    </div>
</footer>
