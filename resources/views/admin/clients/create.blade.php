@extends('layouts.admin')
@section('title','Create Client')
@section('heading','Create Client')
@section('content')
<form method="POST" action="{{ route('admin.clients.store') }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf
<div><label class="block text-sm font-medium">Client name</label><input name="client_name" value="{{ old('client_name') }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="client_name" />
<div><label class="block text-sm font-medium">Contact info</label><input name="contact_info" value="{{ old('contact_info') }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="contact_info" />
<div><label class="block text-sm font-medium">Address</label><textarea name="address" rows="3" class="mt-1 w-full rounded-md border px-3 py-2 text-sm">{{ old('address') }}</textarea></div>
<x-admin.field-error name="address" />
<div class="flex gap-3"><button class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm text-white">Save</button><a href="{{ route('admin.clients.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
