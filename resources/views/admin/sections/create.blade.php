@extends('admin.layouts.app')
@section('title', 'New Section')
@section('page_title', 'New Custom Section')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus"></i> Add a Custom Section</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sections.store') }}" enctype="multipart/form-data">@include('admin.sections._form')</form>
    </div>
</div>
@endsection
