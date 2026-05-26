@php
    $iconMap = [
        'github_url' => ['GitHub', 'fab fa-github'],
        'linkedin_url' => ['LinkedIn', 'fab fa-linkedin-in'],
        'twitter_url' => ['Twitter / X', 'fab fa-twitter'],
        'facebook_url' => ['Facebook', 'fab fa-facebook-f'],
        'instagram_url' => ['Instagram', 'fab fa-instagram'],
        'stackoverflow_url' => ['Stack Overflow', 'fab fa-stack-overflow'],
        'upwork_url' => ['Upwork', 'fab fa-upwork'],
        'fiverr_url' => ['Fiverr', 'fas fa-bolt'],
        'freelancer_url' => ['Freelancer', 'fas fa-laptop-code'],
        'mostaql_url' => ['Mostaql', 'fas fa-handshake'],
        'khamsat_url' => ['Khamsat', 'fas fa-tasks'],
    ];
    $only = $only ?? null;
@endphp

@foreach($iconMap as $key => [$label, $icon])
    @continue($only && ! in_array($key, $only))
    @php($url = $siteSettings[$key] ?? null)
    @if($url)
        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}"><i class="{{ $icon }}"></i></a>
    @endif
@endforeach
