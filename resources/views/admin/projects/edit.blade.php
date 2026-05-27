@extends('admin.layouts.app')
@section('title', 'Edit Project')
@section('page_title', 'Edit Project')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-pen"></i> Edit: {{ $project->title }}</h2>
        <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> Preview</a>
    </div>
    <div class="card-body">
        <form id="project-form" method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.projects._form')
        </form>
    </div>
</div>

@foreach($project->images as $img)
    <form id="delete-img-{{ $img->id }}" method="POST" action="{{ route('admin.projects.images.destroy', $img) }}" style="display: none;">
        @csrf @method('DELETE')
    </form>
@endforeach
@endsection
