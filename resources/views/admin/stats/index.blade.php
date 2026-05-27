@extends('admin.layouts.app')

@section('title', 'Visitor Stats')
@section('page_title', 'Visitor Stats')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="icon primary"><i class="fas fa-eye"></i></div>
        <div class="info">
            <div class="label">Total Visits</div>
            <div class="value">{{ number_format($summary['total_visits']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon success"><i class="fas fa-users"></i></div>
        <div class="info">
            <div class="label">Unique Visitors</div>
            <div class="value">{{ number_format($summary['total_unique']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon info"><i class="fas fa-calendar-day"></i></div>
        <div class="info">
            <div class="label">Today</div>
            <div class="value">{{ number_format($summary['today_visits']) }}</div>
            <div style="font-size: 0.8rem; color: var(--gray-500);">{{ number_format($summary['today_unique']) }} unique</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon warning"><i class="fas fa-calendar-week"></i></div>
        <div class="info">
            <div class="label">Last 7 Days</div>
            <div class="value">{{ number_format($summary['week_visits']) }}</div>
            <div style="font-size: 0.8rem; color: var(--gray-500);">{{ number_format($summary['week_unique']) }} unique</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon danger"><i class="fas fa-calendar-alt"></i></div>
        <div class="info">
            <div class="label">Last 30 Days</div>
            <div class="value">{{ number_format($summary['month_visits']) }}</div>
            <div style="font-size: 0.8rem; color: var(--gray-500);">{{ number_format($summary['month_unique']) }} unique</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon primary"><i class="fas fa-briefcase"></i></div>
        <div class="info">
            <div class="label">Project Views</div>
            <div class="value">{{ number_format($summary['total_project_views']) }}</div>
            <div style="font-size: 0.8rem; color: var(--gray-500);">{{ number_format($summary['total_unique_project_views']) }} unique</div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><h2><i class="fas fa-chart-line"></i> Visits — Last 30 Days</h2></div>
    <div class="card-body">
        <div style="position: relative; height: 300px;">
            <canvas id="visitsChart"></canvas>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-file-alt"></i> Top Pages</h2></div>
        <div class="card-body">
            @if($topPages->isEmpty())
                <div class="empty-state"><i class="fas fa-chart-bar"></i><p>No visits recorded yet</p></div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Path</th><th style="text-align:right;">Visits</th><th style="text-align:right;">Unique</th></tr></thead>
                        <tbody>
                            @foreach($topPages as $row)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $row->path }}</div>
                                        @if($row->route_name)
                                            <div style="color: var(--gray-500); font-size: 0.8rem;">{{ $row->route_name }}</div>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">{{ number_format($row->visits) }}</td>
                                    <td style="text-align:right;">{{ number_format($row->unique_visitors) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-external-link-alt"></i> Top Referrers</h2></div>
        <div class="card-body">
            @if($topReferrers->isEmpty())
                <div class="empty-state"><i class="fas fa-link"></i><p>No referrers recorded</p></div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Source</th><th style="text-align:right;">Visits</th></tr></thead>
                        <tbody>
                            @foreach($topReferrers as $row)
                                <tr>
                                    <td style="word-break: break-all;">
                                        <a href="{{ $row->referrer }}" target="_blank" rel="noopener" style="color: var(--primary);">
                                            {{ \Illuminate\Support\Str::limit($row->referrer, 60) }}
                                        </a>
                                    </td>
                                    <td style="text-align:right;">{{ number_format($row->visits) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><h2><i class="fas fa-fire"></i> Most Viewed Projects</h2></div>
    <div class="card-body">
        @if($topProjects->isEmpty())
            <div class="empty-state"><i class="fas fa-folder"></i><p>No projects yet</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Category</th>
                            <th style="text-align:right;">Total Views</th>
                            <th style="text-align:right;">Unique Views</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProjects as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $p->title }}</strong></td>
                                <td>{{ $p->category ?? '—' }}</td>
                                <td style="text-align:right;">{{ number_format($p->views) }}</td>
                                <td style="text-align:right;">{{ number_format($p->unique_views) }}</td>
                                <td>
                                    <span class="badge {{ $p->is_published ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $p->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('admin.projects.edit', $p) }}" class="btn btn-outline btn-sm">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><h2><i class="fas fa-stream"></i> Recent Visits</h2></div>
    <div class="card-body">
        @if($recentVisits->isEmpty())
            <div class="empty-state"><i class="fas fa-stream"></i><p>No visits yet</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>When</th><th>Path</th><th>IP</th><th>User Agent</th></tr></thead>
                    <tbody>
                        @foreach($recentVisits as $v)
                            <tr>
                                <td style="white-space: nowrap;">{{ $v->visited_at->diffForHumans() }}</td>
                                <td>{{ $v->path }}</td>
                                <td style="white-space: nowrap;">{{ $v->ip_address ?? '—' }}</td>
                                <td style="color: var(--gray-500); font-size: 0.85rem;">{{ \Illuminate\Support\Str::limit($v->user_agent, 60) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const canvas = document.getElementById('visitsChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($daily['labels']),
                datasets: [
                    {
                        label: 'Visits',
                        data: @json($daily['visits']),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.12)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Unique Visitors',
                        data: @json($daily['unique']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.10)',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    })();
</script>
@endpush

@endsection
