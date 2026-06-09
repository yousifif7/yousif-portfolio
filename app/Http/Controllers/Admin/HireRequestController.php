<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevelopmentOffering;
use App\Models\HireRequest;
use App\Support\PublicUpload;
use Illuminate\Http\Request;

class HireRequestController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $requests = HireRequest::query()
            ->when($filter === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($request->query('q'), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.hire-requests.index', compact('requests', 'filter'));
    }

    public function show(HireRequest $hireRequest)
    {
        if (! $hireRequest->is_read) {
            $hireRequest->update(['is_read' => true]);
        }

        $offeringMap = DevelopmentOffering::whereIn('id', $hireRequest->offerings ?? [])
            ->pluck('title', 'id');

        return view('admin.hire-requests.show', compact('hireRequest', 'offeringMap'));
    }

    public function toggleRead(HireRequest $hireRequest)
    {
        $hireRequest->update(['is_read' => ! $hireRequest->is_read]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(HireRequest $hireRequest)
    {
        PublicUpload::delete($hireRequest->attachment_path);
        $hireRequest->delete();

        return redirect()->route('admin.hire-requests.index')
            ->with('success', 'Hire request deleted.');
    }
}
