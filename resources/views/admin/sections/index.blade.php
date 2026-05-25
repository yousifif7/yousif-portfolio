@extends('admin.layouts.app')
@section('title', 'Custom Sections')
@section('page_title', 'Custom Sections')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>Custom Sections</h2>
        <div class="subtitle">Dynamic sections you can add to your home/about pages.</div>
    </div>
    <a href="{{ route('admin.sections.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Section</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Title</th><th>Subtitle</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse($sections as $section)
                    <tr>
                        <td>
                            @if($section->icon)<i class="{{ $section->icon }}" style="color: var(--primary); margin-right: 0.4rem;"></i>@endif
                            <strong>{{ $section->title }}</strong>
                        </td>
                        <td><span class="text-muted">{{ $section->subtitle ?? '—' }}</span></td>
                        <td>@if($section->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Hidden</span>@endif</td>
                        <td>{{ $section->sort_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" style="display:inline;" onsubmit="return confirm('Delete this section?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-layer-group"></i><p>No custom sections yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $sections->links() }}</div>

@endsection
