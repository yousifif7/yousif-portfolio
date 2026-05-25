@extends('admin.layouts.app')
@section('title', 'New Skill')
@section('page_title', 'New Skill')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus"></i> Add a Skill</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.skills.store') }}">@include('admin.skills._form')</form>
    </div>
</div>
@endsection
