@extends('admin.layouts.app')
@section('title', 'New Post')
@section('page_title', 'New Post')

@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus"></i> Create New Post</h2></div>
    <div class="card-body">
        <form id="post-form" method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection
