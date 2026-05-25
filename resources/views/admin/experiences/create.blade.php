@extends('admin.layouts.app')
@section('title', 'New Experience')
@section('page_title', 'New Experience')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus"></i> Add Work Experience</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.experiences.store') }}">@include('admin.experiences._form')</form>
    </div>
</div>
@endsection
