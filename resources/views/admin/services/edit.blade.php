@extends('admin.layouts.app')
@section('title', 'Edit Service')
@section('page_title', 'Edit Service')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-pen"></i> Edit: {{ $service->title }}</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.services.update', $service) }}">@method('PUT')@include('admin.services._form')</form>
    </div>
</div>
@endsection
