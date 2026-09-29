@extends('layouts.admin')

@section('title', 'User')
@section('heading', 'User Details')

@section('actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection

@section('content')
<div class="grid max-w-5xl gap-6 lg:grid-cols-2">
    <div class="space-y-3 rounded-lg border border-gray-200 bg-white p-6 text-sm shadow-sm">
        <h3 class="border-b border-gray-200 pb-2 font-semibold text-gray-900">Account</h3>
        <div><span class="font-medium text-gray-500">Name:</span> {{ $user->name }}</div>
        <div><span class="font-medium text-gray-500">Email:</span> {{ $user->email }}</div>
        <div><span class="font-medium text-gray-500">Status:</span> {{ $user->is_active ? 'Active' : 'Inactive' }}</div>
        <div><span class="font-medium text-gray-500">Verified:</span> {{ $user->email_verified_at?->format('Y-m-d H:i') ?? '—' }}</div>
        <div><span class="font-medium text-gray-500">Roles:</span> {{ $user->roles->pluck('name')->join(', ') ?: '—' }}</div>
        @if ($user->isSuperAdmin())
            <p class="rounded-md bg-amber-50 px-3 py-2 text-amber-800">Super Admin has unrestricted access to users, roles, permissions, system configuration, and every module.</p>
        @endif
        <div class="flex flex-wrap gap-2 pt-2">
            @can('users.update')
                <form method="POST" action="{{ route('admin.users.active', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button>
                </form>
            @endcan
            @can('users.delete')
                @unless(auth()->user()->is($user))
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-md border border-red-200 px-3 py-1.5 text-sm text-red-600">Delete</button>
                    </form>
                @endunless
            @endcan
        </div>
    </div>

    @can('users.update')
        <form method="POST" action="{{ route('admin.users.password', $user) }}" class="space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Reset password</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700">New password</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                <x-admin.field-error name="password" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm password</label>
                <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Update password</button>
        </form>
    @endcan
</div>

<div class="mt-6 max-w-5xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Permissions from roles</h3>
    @forelse ($permissions as $module => $items)
        <div>
            <h4 class="text-sm font-medium text-gray-800">{{ $module }}</h4>
            <ul class="mt-1 grid gap-1 text-sm text-gray-600 sm:grid-cols-2">
                @foreach ($items as $permission)
                    <li>{{ $permission->name }}</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-sm text-gray-500">This user has no permissions.</p>
    @endforelse
</div>
@endsection
