@if(!request()->routeIs('hire*'))
    <a href="{{ route('hire') }}" class="sticky-hire-btn btn btn-primary" aria-label="Hire Me">
        <i class="fas fa-handshake"></i> Hire Me
    </a>
@endif
