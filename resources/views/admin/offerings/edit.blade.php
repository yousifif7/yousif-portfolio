@extends('admin.layouts.app')
@section('title', 'Edit Offering')
@section('page_title', 'Edit Development Offering')

@section('content')
<div class="card" style="max-width: 700px;">
    <form method="POST" action="{{ route('admin.offerings.update', $offering) }}">
        @method('PUT')
        @include('admin.offerings._form')
    </form>
</div>
@endsection
