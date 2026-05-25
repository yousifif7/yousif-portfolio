@extends('admin.layouts.app')
@section('title', 'Edit Section')
@section('page_title', 'Edit Custom Section')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-pen"></i> Edit: {{ $section->title }}</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sections.update', $section) }}" enctype="multipart/form-data">@method('PUT')@include('admin.sections._form')</form>
    </div>
</div>
@endsection
