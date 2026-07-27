@extends('layouts.admin')
@section('title','Create Visitor')
@section('heading','Create Visitor')
@section('content')
<form method="POST" action="{{ route('admin.visitors.store') }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf
<div><label class="block text-sm font-medium">Name</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="name" />
<div><label class="block text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="email" />
<div><label class="block text-sm font-medium">Phone</label><input type="tel" name="phone" value="{{ old('phone') }}" maxlength="20" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="phone" />
<div><label class="block text-sm font-medium">Address</label><input name="address" value="{{ old('address') }}" required maxlength="20" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="address" />
<div><label class="block text-sm font-medium">Visit purpose</label><textarea name="visit_purpose" rows="3" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">{{ old('visit_purpose') }}</textarea></div>
<x-admin.field-error name="visit_purpose" />
<div class="flex gap-3"><button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Save</button><a href="{{ route('admin.visitors.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
