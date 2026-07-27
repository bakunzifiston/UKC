@extends('layouts.admin')
@section('title','Create Staff Member')
@section('heading','Create Staff Member')
@section('content')
<form method="POST" action="{{ route('admin.staff-members.store') }}" class="max-w-xl space-y-4 rounded-lg border bg-white p-6 shadow-sm">@csrf
<div><label class="block text-sm font-medium">Name</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="name" />
<div><label class="block text-sm font-medium">Phone</label><input type="tel" name="phone_number" value="{{ old('phone_number') }}" maxlength="20" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="phone_number" />
<div><label class="block text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="email" />
<div><label class="block text-sm font-medium">Role</label>
<select name="role" required class="mt-1 w-full rounded-md border px-3 py-2 text-sm">
<option value="">Select role</option>
@foreach($roles as $role)
<option value="{{ $role }}" @selected(old('role')===$role)>{{ $role }}</option>
@endforeach
</select></div>
<x-admin.field-error name="role" />
<div><label class="block text-sm font-medium">Site name</label><input name="site_name" value="{{ old('site_name') }}" required maxlength="20" class="mt-1 w-full rounded-md border px-3 py-2 text-sm"></div>
<x-admin.field-error name="site_name" />
<div class="flex gap-3"><button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Save</button><a href="{{ route('admin.staff-members.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a></div>
</form>@endsection
