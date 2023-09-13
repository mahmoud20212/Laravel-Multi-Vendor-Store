@extends('layouts.dashboard')
@section('title', 'Create category')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Create category</li>
@endsection
@section('content')
    <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('dashboard.categories._form')
    </form>
@endsection
