@extends('layouts.admin')
@section('title','Product Supplier')
@section('heading','Product Supplier Details')
@section('actions')<a href="{{ route('admin.product-suppliers.edit',$productSupplier) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Edit</a>@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border bg-white p-6 text-sm">
<div><span class="font-medium text-gray-500">Product:</span> {{ $productSupplier->product?->product_name }}</div>
<div><span class="font-medium text-gray-500">Supplier:</span> {{ $productSupplier->supplier?->supplier_name }}</div>
<div><span class="font-medium text-gray-500">Quantity:</span> {{ $productSupplier->supplied_quantity }} kg</div>
<div><span class="font-medium text-gray-500">Date:</span> {{ $productSupplier->supplied_date }}</div>
</div>@endsection
