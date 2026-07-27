@extends('layouts.admin')
@section('title','Edit Product')
@section('heading','Edit Product')
@section('content')
<form method="POST" action="{{ route('admin.products.update',$product) }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf @method('PUT')
<div><label class="block text-sm font-medium">Site</label>
<select name="site_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
@foreach($sites as $site)<option value="{{ $site->id }}" @selected(old('site_id',$product->site_id)==$site->id)>{{ $site->site_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="site_id" />
<div><label class="block text-sm font-medium">Category</label>
<select name="hydroponics_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
@foreach($hydroponics as $cat)<option value="{{ $cat->id }}" @selected(old('hydroponics_id',$product->hydroponics_id)==$cat->id)>{{ $cat->hydroponics_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="hydroponics_id" />
<div><label class="block text-sm font-medium">Product name</label><input name="product_name" value="{{ old('product_name',$product->product_name) }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="product_name" />
<div><label class="block text-sm font-medium">Product type</label><input name="product_type" value="{{ old('product_type',$product->product_type) }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="product_type" />
<div><label class="block text-sm font-medium">Quantity (kg)</label><input type="number" name="quantity" value="{{ old('quantity',$product->quantity) }}" required min="0" step="1" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="quantity" />
<div><label class="block text-sm font-medium">Unit price (RWF)</label><input type="number" name="unit_price" value="{{ old('unit_price',$product->unit_price) }}" required min="0" step="0.01" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="unit_price" />
<div class="flex gap-3"><button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Update</button><a href="{{ route('admin.products.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
