@extends('layouts.admin')
@section('title', $role->name)
@section('heading', 'Role Details')
@section('actions')
    <a href="{{ route('admin.roles.edit', $role) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection
@section('content')
<div class="max-w-4xl space-y-6">
    <div class="space-y-2 rounded-lg border border-gray-200 bg-white p-6 text-sm shadow-sm">
        <div><span class="font-medium text-gray-500">Name:</span> {{ $role->name }}</div>
        <div><span class="font-medium text-gray-500">Slug:</span> {{ $role->slug }}</div>
        <div><span class="font-medium text-gray-500">Description:</span> {{ $role->description ?: '—' }}</div>
        @if ($role->isSuperAdmin())
            <p class="rounded-md bg-amber-50 px-3 py-2 text-amber-800">This role bypasses permission checks and can open every module.</p>
        @endif
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-900">Users</h3>
        <ul class="mt-2 space-y-1 text-sm text-gray-700">
            @forelse ($role->users as $user)
                <li><a href="{{ route('admin.users.show', $user) }}" class="text-brand hover:underline">{{ $user->name }}</a></li>
            @empty
                <li class="text-gray-400">No users assigned.</li>
            @endforelse
        </ul>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-900">Permissions</h3>
        <ul class="mt-2 grid gap-1 text-sm text-gray-700 sm:grid-cols-2">
            @forelse ($role->permissions as $permission)
                <li>{{ $permission->name }} <span class="text-xs text-gray-400">{{ $permission->slug }}</span></li>
            @empty
                <li class="text-gray-400">No permissions assigned.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
