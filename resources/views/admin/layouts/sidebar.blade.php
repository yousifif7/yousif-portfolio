@php
    $unreadCount = \App\Models\ContactMessage::unread()->count();
    $pendingReviewsCount = \App\Models\Review::pending()->count();
    $unreadHireCount = \App\Models\HireRequest::unread()->count();
@endphp
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo"><i class="fas fa-code"></i></div>
        <div>Portfolio Admin</div>
    </div>

    <nav>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a></li>

            <li><a href="{{ route('admin.stats.index') }}" class="{{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Visitor Stats
            </a></li>

            <div class="nav-section">Content</div>

            <li><a href="{{ route('admin.about.edit') }}" class="{{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i> Profile / About
            </a></li>

            <li><a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Projects
            </a></li>

            <li><a href="{{ route('admin.skills.index') }}" class="{{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                <i class="fas fa-code"></i> Skills
            </a></li>

            <li><a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i> Services
            </a></li>

            <li><a href="{{ route('admin.offerings.index') }}" class="{{ request()->routeIs('admin.offerings.*') ? 'active' : '' }}">
                <i class="fas fa-laptop-code"></i> Hire Offerings
            </a></li>

            <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Reviews
                @if($pendingReviewsCount > 0)
                    <span class="badge">{{ $pendingReviewsCount }}</span>
                @endif
            </a></li>

            <li><a href="{{ route('admin.experiences.index') }}" class="{{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Experience
            </a></li>

            <li><a href="{{ route('admin.sections.index') }}" class="{{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> Custom Sections
            </a></li>

            <div class="nav-section">Communication</div>

            <li><a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Messages
                @if($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
            </a></li>

            <li><a href="{{ route('admin.hire-requests.index') }}" class="{{ request()->routeIs('admin.hire-requests.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> Hire Requests
                @if($unreadHireCount > 0)
                    <span class="badge">{{ $unreadHireCount }}</span>
                @endif
            </a></li>

            <div class="nav-section">Configuration</div>

            <li><a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> Site Settings
            </a></li>
        </ul>
    </nav>
</aside>
