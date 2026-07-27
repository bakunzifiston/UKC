@extends('layouts.admin')
@section('title','Edit Growth Log')
@section('heading','Edit Growth Log')
@section('content')
<form method="POST" action="{{ route('admin.growth-logs.update',$growthLog) }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf @method('PUT')
<div><label class="block text-sm font-medium">Site</label>
<select name="site_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
@foreach($sites as $site)<option value="{{ $site->id }}" @selected(old('site_id',$growthLog->site_id)==$site->id)>{{ $site->site_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="site_id" />
<div><label class="block text-sm font-medium">Category</label>
<select name="hydroponics_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
@foreach($hydroponics as $cat)<option value="{{ $cat->id }}" @selected(old('hydroponics_id',$growthLog->hydroponics_id)==$cat->id)>{{ $cat->hydroponics_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="hydroponics_id" />
<div><label class="block text-sm font-medium">Product</label>
<select name="product_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id',$growthLog->product_id)==$product->id)>{{ $product->product_name }}</option>@endforeach
</select></div>
<x-admin.field-error name="product_id" />
<div><label class="block text-sm font-medium">Day number (1-16)</label><input type="number" name="day_number" value="{{ old('day_number',$growthLog->day_number) }}" required min="1" max="16" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="day_number" />
<div><label class="block text-sm font-medium">Number of trays</label><input type="number" name="growth_value" value="{{ old('growth_value',$growthLog->growth_value) }}" required step="0.01" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="growth_value" />
<div><label class="block text-sm font-medium">Log date</label><input type="date" name="log_date" value="{{ old('log_date', substr((string)$growthLog->log_date,0,10)) }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="log_date" />
<div class="flex gap-3"><button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Update</button><a href="{{ route('admin.growth-logs.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
