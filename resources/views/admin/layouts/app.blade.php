<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        @include('admin.layouts.sidebar')

        <div class="main">
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="menu-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="topbar-actions">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> View Site</a>
                    <div class="user-menu">
                        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                        <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>

            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    </script>
    @stack('scripts')
</body>
</html>
