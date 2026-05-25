<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            <span class="brand-dot"></span>
            <span>{{ $siteAbout?->full_name ?? config('app.name') }}</span>
        </a>

        <button class="nav-toggle" aria-label="Toggle menu"><i class="fas fa-bars"></i></button>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact*') ? 'active' : '' }}">Contact</a></li>
            <li><a href="{{ route('contact') }}" class="btn btn-primary btn-sm">Hire Me</a></li>
        </ul>
    </div>
</nav>
