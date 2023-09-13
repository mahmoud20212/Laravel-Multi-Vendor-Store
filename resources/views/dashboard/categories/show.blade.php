@extends('layouts.dashboard')
@section('title', $category->name)
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Show category</li>
    <li class="breadcrumb-item active">{{ $category->name }}</li>
@endsection
@section('content')
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>ID</th>
            <th>Name</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php
            $products = $category->products()->with('store')->latest()->paginate()
        @endphp
        @forelse ($products as $product)
            <tr>
                <td>
                    <img class="rounded shadow-sm" src="{{ asset('storage/' . $product->image) }}" alt="" height="100">
                </td>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->store->name }}</td>
                <td>{{ $product->status }}</td>
                <td>{{ date('Y/M/D', strtotime($product->created_at)) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No Products defined.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}
@endsection
