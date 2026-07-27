@extends('layouts.admin')
@section('title','Create Product Supplier')
@section('heading','Create Product Supplier')
@section('content')
<form method="POST" action="{{ route('admin.product-suppliers.store') }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf
<div><label class="block text-sm font-medium">Product</label>
<select name="product_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
<option value="">Select</option>
@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id')==$product->id)>{{ $product->product_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="product_id" />
<div><label class="block text-sm font-medium">Supplier</label>
<select name="supplier_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
<option value="">Select</option>
@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}" @selected(old('supplier_id')==$supplier->id)>{{ $supplier->supplier_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="supplier_id" />
<div><label class="block text-sm font-medium">Supplied quantity (kg)</label><input type="number" name="supplied_quantity" value="{{ old('supplied_quantity') }}" required min="0" step="1" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="supplied_quantity" />
<div><label class="block text-sm font-medium">Supplied date</label><input type="date" name="supplied_date" value="{{ old('supplied_date') }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="supplied_date" />
<div class="flex gap-3"><button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Save</button><a href="{{ route('admin.product-suppliers.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
