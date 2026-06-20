@extends('admin.layouts.app')
@section('title', 'Edit Post')
@section('page_title', 'Edit Post')

@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-pen"></i> Edit Post</h2></div>
    <div class="card-body">
        <form id="post-form" method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection
