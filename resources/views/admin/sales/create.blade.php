@extends('layouts.admin')
@section('title','Create Sale')
@section('heading','Create Sale')
@section('content')
<form method="POST" action="{{ route('admin.sales.store') }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf
<div><label class="block text-sm font-medium">Product</label>
<select name="product_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
<option value="">Select</option>
@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id')==$product->id)>{{ $product->product_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="product_id" />
<div><label class="block text-sm font-medium">Client</label>
<select name="client_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
<option value="">Select</option>
@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('client_id')==$client->id)>{{ $client->client_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="client_id" />
<div><label class="block text-sm font-medium">Quantity sold (kg)</label><input type="number" name="quantity_sold" value="{{ old('quantity_sold') }}" required min="0" step="1" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="quantity_sold" />
<div><label class="block text-sm font-medium">Total price (RWF)</label><input type="number" name="total_price" value="{{ old('total_price') }}" required min="0" step="0.01" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="total_price" />
<div><label class="block text-sm font-medium">Sale date</label><input type="date" name="sale_date" value="{{ old('sale_date') }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="sale_date" />
<div class="flex gap-3"><button class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm text-white">Save</button><a href="{{ route('admin.sales.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
