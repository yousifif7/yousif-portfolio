@extends('admin.layouts.app')
@section('title', 'New Offering')
@section('page_title', 'New Development Offering')

@section('content')
<div class="card" style="max-width: 700px;">
    <form method="POST" action="{{ route('admin.offerings.store') }}">
        @include('admin.offerings._form', ['offering' => new \App\Models\DevelopmentOffering()])
    </form>
</div>
@endsection
