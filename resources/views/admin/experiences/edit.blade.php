@extends('admin.layouts.app')
@section('title', 'Edit Experience')
@section('page_title', 'Edit Experience')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-pen"></i> Edit: {{ $experience->position }} @ {{ $experience->company }}</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.experiences.update', $experience) }}">@method('PUT')@include('admin.experiences._form')</form>
    </div>
</div>
@endsection
