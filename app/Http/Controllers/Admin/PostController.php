<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Support\PublicUpload;

class PostController extends Controller
{
    public function index()
    {
        $status = request('status', 'all');
        $sort = request('sort', 'newest');

        $posts = Post::query()
            ->withViewCounts()
            ->when($status === 'published', fn ($q) => $q->where('is_published', true))
            ->when($status === 'draft', fn ($q) => $q->where('is_published', false))
            ->when(request('q'), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%$search%")
                        ->orWhere('excerpt', 'like', "%$search%");
                });
            })
            ->when($sort === 'most_views', fn ($q) => $q->orderByDesc('views')->orderByDesc('id'))
            ->when($sort === 'least_views', fn ($q) => $q->orderBy('views')->orderByDesc('id'))
            ->when($sort === 'oldest', fn ($q) => $q->orderBy('published_at')->orderBy('id'))
            ->when(! in_array($sort, ['most_views', 'least_views', 'oldest'], true), fn ($q) => $q->orderByDesc('updated_at'))
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'status', 'sort'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = PublicUpload::store($request->file('cover_image'), 'posts/covers');
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $post->loadCount('viewLogs as logged_unique_views');

        return view('admin.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            PublicUpload::delete($post->cover_image);
            $data['cover_image'] = PublicUpload::store($request->file('cover_image'), 'posts/covers');
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        PublicUpload::delete($post->cover_image);
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
