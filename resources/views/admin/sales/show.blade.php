@extends('layouts.admin')
@section('title','Sale')
@section('heading','Sale Details')
@section('actions')<a href="{{ route('admin.sales.edit',$sale) }}" class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm text-white">Edit</a>@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border bg-white p-6 text-sm">
<div><span class="font-medium text-gray-500">Product:</span> {{ $sale->product?->product_name }}</div>
<div><span class="font-medium text-gray-500">Client:</span> {{ $sale->client?->client_name }}</div>
<div><span class="font-medium text-gray-500">Quantity:</span> {{ $sale->quantity_sold }} kg</div>
<div><span class="font-medium text-gray-500">Total:</span> {{ number_format((float)$sale->total_price, 2) }} RWF</div>
<div><span class="font-medium text-gray-500">Date:</span> {{ $sale->sale_date }}</div>
</div>@endsection
