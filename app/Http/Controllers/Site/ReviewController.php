<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewFormRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(ReviewFormRequest $request)
    {
        $data = $request->validated();
        unset($data['website']);

        Review::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'rating' => $data['rating'],
            'content' => $data['content'],
            'company' => $data['company'] ?? null,
            'role' => $data['role'] ?? null,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('home')
            ->withFragment('reviews')
            ->with('review_success', 'Thank you for your review! It will appear on the site once approved.');
    }
}
