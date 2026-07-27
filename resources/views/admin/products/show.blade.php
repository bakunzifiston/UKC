@extends('layouts.admin')
@section('title','Product')
@section('heading','Product Details')
@section('actions')<a href="{{ route('admin.products.edit',$product) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Edit</a>@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border bg-white p-6 text-sm">
<div><span class="font-medium text-gray-500">Site:</span> {{ $product->site?->site_name }}</div>
<div><span class="font-medium text-gray-500">Category:</span> {{ $product->hydroponics?->hydroponics_name }}</div>
<div><span class="font-medium text-gray-500">Name:</span> {{ $product->product_name }}</div>
<div><span class="font-medium text-gray-500">Type:</span> {{ $product->product_type }}</div>
<div><span class="font-medium text-gray-500">Quantity:</span> {{ $product->quantity }} kg</div>
<div><span class="font-medium text-gray-500">Unit price:</span> {{ number_format((float)$product->unit_price, 2) }} RWF</div>
</div>@endsection
