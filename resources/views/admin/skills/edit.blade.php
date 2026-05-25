@extends('admin.layouts.app')
@section('title', 'Edit Skill')
@section('page_title', 'Edit Skill')
@section('content')
<div class="card">
    <div class="card-header"><h2><i class="fas fa-pen"></i> Edit: {{ $skill->name }}</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.skills.update', $skill) }}">@method('PUT') @include('admin.skills._form')</form>
    </div>
</div>
@endsection
