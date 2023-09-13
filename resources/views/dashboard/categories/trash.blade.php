@extends('layouts.dashboard')
@section('title', 'Trashed Categories')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Categories</li>
    <li class="breadcrumb-item active">Trashed</li>
@endsection
@section('content')

<x-alert type="success" />

<div class="mb-4">
    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-primary">
        Back
    </a>
</div>

<form action="{{ URL::current() }}" method="get" style="gap: 16px" class="d-flex justify-content-between mb-4">
    <x-form.input name="name" placeholder="Name" :value="request('name')" />
    <select name="status" class="form-control">
        <option value="">All</option>
        <option value="active" @selected(request('status') == 'active')>Active</option>
        <option value="archived" @selected(request('status') == 'archived')>Archived</option>
    </select>
    <button class="btn btn-dark">Filter</button>
</form>


<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>ID</th>
            <th>Name</th>
            <th>Parnet</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>
                    <img class="rounded shadow-sm" src="{{ asset('storage/' . $category->image) }}" alt="" height="100">
                </td>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent_id }}</td>
                <td>{{ $category->status }}</td>
                <td>{{ date('Y/M/D', strtotime($category->deleted_at)) }}</td>
                <td>
                    <div style="gap: 16px;" class="d-flex">
                        <form action="{{ route('dashboard.categories.restore', $category->id) }}" method="POST">
                            @csrf
                            @method('put')
                            <button class="btn btn-sm btn-outline-dark">restore</button>
                        </form>
                        <form action="{{ route('dashboard.categories.force-delete', $category->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm btn-outline-danger">force delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No Categories defined.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $categories->withQueryString()->appends(['search' => 1])->links() }}
@endsection