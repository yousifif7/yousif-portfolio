<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $messages = ContactMessage::query()
            ->when($filter === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($filter === 'replied', fn ($q) => $q->where('is_replied', true))
            ->when($filter === 'unreplied', fn ($q) => $q->where('is_replied', false))
            ->when($request->query('q'), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%")
                          ->orWhere('subject', 'like', "%$search%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.messages.index', compact('messages', 'filter'));
    }

    public function show(ContactMessage $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, ContactMessage $message)
    {
        $data = $request->validate([
            'reply_message' => ['required', 'string', 'min:5', 'max:10000'],
        ]);

        try {
            Mail::to($message->email)->send(new ContactReplyMail($message, $data['reply_message']));
        } catch (\Throwable $e) {
            Log::error('Failed to send reply email: '.$e->getMessage());
            return back()->withErrors([
                'reply_message' => 'Failed to send reply via SMTP: '.$e->getMessage(),
            ])->withInput();
        }

        $message->update([
            'is_replied' => true,
            'reply_message' => $data['reply_message'],
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.messages.show', $message)
            ->with('success', 'Reply sent successfully via SMTP.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted.');
    }

    public function toggleRead(ContactMessage $message)
    {
        $message->update(['is_read' => ! $message->is_read]);
        return back()->with('success', 'Status updated.');
    }
}
