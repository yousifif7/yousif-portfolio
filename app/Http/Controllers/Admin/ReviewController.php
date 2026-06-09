<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $reviews = Review::query()
            ->when($filter === 'pending', fn ($q) => $q->where('status', 'pending'))
            ->when($filter === 'approved', fn ($q) => $q->where('status', 'approved'))
            ->when($filter === 'rejected', fn ($q) => $q->where('status', 'rejected'))
            ->when($request->query('q'), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%")
                          ->orWhere('content', 'like', "%$search%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'filter'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Review approved and now visible on the site.');
    }

    public function reject(Review $review)
    {
        $review->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted.');
    }
}
