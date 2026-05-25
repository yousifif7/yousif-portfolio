@extends('admin.layouts.app')
@section('title', 'Skills')
@section('page_title', 'Skills')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>Skills &amp; Languages</h2>
        <div class="subtitle">Manage the technologies displayed on your site.</div>
    </div>
    <a href="{{ route('admin.skills.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Skill</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Icon</th><th>Name</th><th>Category</th><th>Proficiency</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse($skills as $skill)
                    <tr>
                        <td style="font-size: 1.5rem; color: {{ $skill->color ?: 'inherit' }};">
                            @if($skill->icon)<i class="{{ $skill->icon }}"></i>@else <i class="fas fa-code text-muted"></i>@endif
                        </td>
                        <td><strong>{{ $skill->name }}</strong></td>
                        <td>{{ $skill->category ?? '—' }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="flex: 1; height: 6px; background: var(--gray-200); border-radius: 999px; overflow: hidden; max-width: 120px;">
                                    <div style="height: 100%; width: {{ $skill->proficiency }}%; background: linear-gradient(90deg, var(--primary), var(--secondary));"></div>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 600;">{{ $skill->proficiency }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($skill->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Hidden</span>
                            @endif
                        </td>
                        <td>{{ $skill->sort_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" style="display:inline;" onsubmit="return confirm('Delete this skill?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="fas fa-code"></i><p>No skills yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $skills->links() }}</div>

@endsection
