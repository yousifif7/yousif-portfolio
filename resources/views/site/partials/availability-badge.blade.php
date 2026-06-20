@php
    $availableForHire = ($siteSettings['available_for_hire'] ?? '1') !== '0';
    $variant = $variant ?? ( !empty($compact) ? 'compact' : 'inline');
@endphp
@if($availableForHire)
    @if($variant === 'floating')
        <div class="floating-badge available">
            <span class="availability-dot" aria-hidden="true"></span>
            <span>Available for hire</span>
        </div>
    @else
        <div class="availability-badge{{ $variant === 'compact' ? ' is-compact' : '' }}">
            <span class="availability-dot" aria-hidden="true"></span>
            <span>{{ $variant === 'compact' ? 'Available' : 'Available for hire' }}</span>
        </div>
    @endif
@endif
