@extends('layouts.admin')

@section('title', 'Edit User')
@section('heading', 'Edit User')

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="name" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="email" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email verified at</label>
        <input type="datetime-local" name="email_verified_at" value="{{ old('email_verified_at', optional($user->email_verified_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="email_verified_at" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Password <span class="font-normal text-gray-400">(leave blank to keep)</span></label>
        <input type="password" name="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="password" />
    </div>
    <div class="flex gap-3">
        <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Update</button>
        <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
