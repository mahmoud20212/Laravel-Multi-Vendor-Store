@extends('layouts.dashboard')
@section('title', 'Categories')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
@endsection
@section('content')

<x-alert type="success" />


<div style="gap: 16px;" class="mb-4 d-flex">
    <a href="{{ route('dashboard.categories.create') }}" class="btn btn-outline-primary">
        Create
    </a>
    
    <a href="{{ route('dashboard.categories.trash') }}" class="btn btn-outline-dark">
        Trash
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
            <th>Name</th>
            <th>Parnet</th>
            <th>Products Count</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>
                    @if (Storage::disk('public')->exists($category->image))
                        <img class="rounded shadow-sm" src="{{ asset('storage/' . $category->image) }}" alt="" height="100">
                    @elseif ($category->image)
                        <img class="rounded shadow-sm" src="{{ $category->image }}" alt="" height="100">
                    @endif
                </td>
                <td>
                    <a href="{{ route('dashboard.categories.show', $category->id) }}">{{ $category->name }}</a>
                </td>
                <td>{{ $category->parent_id }}</td>
                <td>{{ $category->products_count }}</td>
                <td>{{ $category->status }}</td>
                <td>{{ date('Y/M/D', strtotime($category->created_at)) }}</td>
                <td>{{ date('Y/M/D', strtotime($category->updated_at)) }}</td>
                <td>
                    <div style="gap: 16px;" class="d-flex">
                        <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-success">
                            edit
                        </a>
                        <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm btn-outline-danger">delete</button>
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