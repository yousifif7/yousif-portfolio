@php
    $logoPath = $siteSettings['site_logo'] ?? null;
    $navLogo = $logoPath ? \App\Support\PublicUpload::url($logoPath) : null;
    $navName = $siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name');
    $navSubtitle = $siteSettings['site_tagline'] ?? $siteAbout?->title ?? 'Portfolio';
@endphp
<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            @if($navLogo)
                <img src="{{ $navLogo }}" alt="{{ $navName }}" class="navbar-logo" width="160" height="36" decoding="async">
            @else
                <span class="brand-dot"></span>
                <span class="navbar-brand-copy">
                    <span class="navbar-brand-name">{{ $navName }}</span>
                    <span class="navbar-brand-subtitle">{{ $navSubtitle }}</span>
                </span>
            @endif
        </a>

        <button class="nav-toggle" aria-label="Toggle menu"><i class="fas fa-bars"></i></button>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a></li>
            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact*') ? 'active' : '' }}">Contact</a></li>
            @if(($siteSettings['available_for_hire'] ?? '1') !== '0')
                <li class="nav-availability-item">@include('site.partials.availability-badge', ['variant' => 'compact'])</li>
            @endif
            <li><a href="{{ route('hire') }}" class="btn btn-sm nav-hire-btn {{ request()->routeIs('hire*') ? 'btn-outline is-current' : 'btn-primary' }}">Hire Me</a></li>
        </ul>
    </div>
</nav>
