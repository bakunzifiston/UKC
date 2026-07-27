@extends('layouts.admin')

@section('title', 'Create User')
@section('heading', 'Create User')

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}" class="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="name" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="email" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email verified at</label>
        <input type="datetime-local" name="email_verified_at" value="{{ old('email_verified_at') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="email_verified_at" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="password" />
    </div>
    <div class="flex gap-3">
        <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Save</button>
        <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
