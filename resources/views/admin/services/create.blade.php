@extends('admin.layouts.app')
@section('title', 'New Service')
@section('page_title', 'New Service')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus"></i> Add a Service</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.services.store') }}">@include('admin.services._form')</form>
    </div>
</div>
@endsection
