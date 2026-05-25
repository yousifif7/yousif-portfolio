@extends('admin.layouts.app')
@section('title', 'Experience')
@section('page_title', 'Work Experience')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>Work Experience</h2>
        <div class="subtitle">Manage your career timeline.</div>
    </div>
    <a href="{{ route('admin.experiences.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Experience</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Position</th><th>Company</th><th>Period</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($experiences as $exp)
                    <tr>
                        <td><strong>{{ $exp->position }}</strong>@if($exp->location)<div style="font-size: 0.8rem; color: var(--gray-500);">{{ $exp->location }}</div>@endif</td>
                        <td>{{ $exp->company }}</td>
                        <td><span class="badge badge-info">{{ $exp->period }}</span></td>
                        <td>@if($exp->is_current)<span class="badge badge-success">Current</span>@else<span class="badge badge-secondary">Past</span>@endif</td>
                        <td class="actions">
                            <a href="{{ route('admin.experiences.edit', $exp) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.experiences.destroy', $exp) }}" style="display:inline;" onsubmit="return confirm('Delete this experience?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-history"></i><p>No experience added yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $experiences->links() }}</div>

@endsection
