@extends('layouts.admin')
@section('title','Edit Category')
@section('heading','Edit Category')
@section('content')
<form method="POST" action="{{ route('admin.hydroponics.update',$hydroponics) }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf @method('PUT')
<div><label class="block text-sm font-medium">Site</label>
<select name="site_id" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
@foreach($sites as $site)
<option value="{{ $site->id }}" @selected(old('site_id',$hydroponics->site_id)==$site->id)>{{ $site->site_name }}</option>
@endforeach
</select></div>
<x-admin.field-error name="site_id" />
<div><label class="block text-sm font-medium">Category name</label><input name="hydroponics_name" value="{{ old('hydroponics_name',$hydroponics->hydroponics_name) }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="hydroponics_name" />
<div><label class="block text-sm font-medium">Description</label><textarea name="description" rows="3" class="mt-1 w-full rounded-md border px-3 py-2 text-sm">{{ old('description',$hydroponics->description) }}</textarea></div>
<x-admin.field-error name="description" />
<div class="flex gap-3"><button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Update</button><a href="{{ route('admin.hydroponics.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
