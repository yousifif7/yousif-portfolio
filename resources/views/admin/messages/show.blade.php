@extends('admin.layouts.app')
@section('title', 'Message')
@section('page_title', 'Message Details')

@section('content')

<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.messages.index') }}" style="color: var(--primary); display: inline-flex; align-items: center; gap: 0.4rem;">
        <i class="fas fa-arrow-left"></i> Back to all messages
    </a>
</div>

<div class="message-detail">
    <div class="header">
        <h2>{{ $message->subject }}</h2>
        <div class="meta">
            <span><i class="fas fa-user"></i> <strong>{{ $message->name }}</strong></span>
            <span><i class="fas fa-envelope"></i> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></span>
            <span><i class="fas fa-clock"></i> {{ $message->created_at->format('M d, Y H:i') }}</span>
            @if($message->ip_address)
                <span><i class="fas fa-globe"></i> {{ $message->ip_address }}</span>
            @endif
        </div>
        <div style="margin-top: 0.75rem; display: flex; gap: 0.4rem; flex-wrap: wrap;">
            @if($message->is_replied)
                <span class="badge badge-success">Replied {{ $message->replied_at?->diffForHumans() }}</span>
            @else
                <span class="badge badge-warning">Awaiting Reply</span>
            @endif
        </div>
    </div>

    <div class="body">{{ $message->message }}</div>

    <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem; flex-wrap: wrap;">
        <form method="POST" action="{{ route('admin.messages.toggleRead', $message) }}" style="margin:0;">
            @csrf
            <button class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> Mark as {{ $message->is_read ? 'unread' : 'read' }}</button>
        </form>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')" style="margin:0;">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

@if($message->is_replied)
    <div class="reply-box">
        <h3><i class="fas fa-check-circle" style="color: var(--success);"></i> Your reply (sent {{ $message->replied_at?->format('M d, Y H:i') }})</h3>
        <div style="background: rgba(16,185,129,0.06); padding: 1.25rem; border-radius: 8px; border-left: 4px solid var(--success); white-space: pre-wrap; line-height: 1.7;">{{ $message->reply_message }}</div>
        <details style="margin-top: 1rem;">
            <summary style="cursor: pointer; color: var(--primary); font-size: 0.9rem;">Send another reply</summary>
            <form method="POST" action="{{ route('admin.messages.reply', $message) }}" style="margin-top: 1rem;">
                @csrf
                <div class="form-group">
                    <textarea name="reply_message" class="form-control" rows="6" placeholder="Type your reply..." required></textarea>
                    @error('reply_message')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Reply via SMTP</button>
            </form>
        </details>
    </div>
@else
    <div class="reply-box">
        <h3><i class="fas fa-reply"></i> Reply to {{ $message->name }}</h3>
        <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem;">Your reply will be sent to <strong>{{ $message->email }}</strong> via SMTP.</p>
        <form method="POST" action="{{ route('admin.messages.reply', $message) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Your reply <span class="required">*</span></label>
                <textarea name="reply_message" class="form-control" rows="8" placeholder="Hi {{ $message->name }}, thanks for reaching out..." required>{{ old('reply_message') }}</textarea>
                @error('reply_message')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Reply</button>
        </form>
    </div>
@endif

@endsection
