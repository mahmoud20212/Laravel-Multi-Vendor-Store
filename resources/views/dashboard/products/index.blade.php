@extends('layouts.dashboard')
@section('title', 'Products')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Products</li>
@endsection
@section('content')

<x-alert type="success" />


<div style="gap: 16px;" class="mb-4 d-flex">
    <a href="{{ route('dashboard.products.create') }}" class="btn btn-outline-primary">
        Create
    </a>
    
    {{-- <a href="{{ route('dashboard.product.trash') }}" class="btn btn-outline-dark">
        Trash
    </a> --}}
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
            <th>Category</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
            <tr>
                <td>
                    <img class="rounded shadow-sm" src="{{ asset('storage/' . $product->image) }}" alt="" height="100">
                </td>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->store->name }}</td>
                <td>{{ $product->status }}</td>
                <td>{{ date('Y/M/D', strtotime($product->created_at)) }}</td>
                <td>
                    <div style="gap: 16px;" class="d-flex">
                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-sm btn-outline-success">
                            edit
                        </a>
                        <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST">
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

{{ $products->withQueryString()->appends(['search' => 1])->links() }}
@endsection