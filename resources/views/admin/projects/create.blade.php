@extends('admin.layouts.app')
@section('title', 'New Project')
@section('page_title', 'New Project')

@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus"></i> Create New Project</h2></div>
    <div class="card-body">
        <form id="project-form" method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
            @include('admin.projects._form')
        </form>
    </div>
</div>
@endsection
