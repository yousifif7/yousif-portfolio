<footer class="footer">
    <div class="container">
        <div class="footer-inner">
            <div>
                <h4>{{ $siteAbout?->full_name ?? config('app.name') }}</h4>
                <p style="margin-top: 0.5rem; max-width: 380px;">
                    {{ $siteAbout?->tagline ?? 'Backend developer building reliable APIs and CRMs.' }}
                </p>
                @if($siteAbout)
                <div style="margin-top: 1.25rem; display: flex; gap: 0.75rem;">
                    @if($siteAbout->github_url)
                        <a href="{{ $siteAbout->github_url }}" target="_blank" rel="noopener" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    @endif
                    @if($siteAbout->linkedin_url)
                        <a href="{{ $siteAbout->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    @endif
                    @if($siteAbout->twitter_url)
                        <a href="{{ $siteAbout->twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if($siteAbout->stackoverflow_url)
                        <a href="{{ $siteAbout->stackoverflow_url }}" target="_blank" rel="noopener" aria-label="Stack Overflow"><i class="fab fa-stack-overflow"></i></a>
                    @endif
                </div>
                @endif
            </div>

            <div>
                <h4>Navigation</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('projects.index') }}">Projects</a>
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
            &copy; {{ date('Y') }} {{ $siteAbout?->full_name ?? config('app.name') }}. All rights reserved. Built with Laravel.
        </div>
    </div>
</footer>
