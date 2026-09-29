@extends('layouts.admin')

@section('title', 'Edit User')
@section('heading', 'Edit User')

@section('content')
@php $selectedRoles = collect(old('roles', $user->roles->pluck('id')->all())); @endphp
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-3xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    <div class="grid gap-4 sm:grid-cols-2">
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
            <label class="block text-sm font-medium text-gray-700">New password <span class="font-normal text-gray-400">(leave blank to keep)</span></label>
            <input type="password" name="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            <x-admin.field-error name="password" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm new password</label>
            <input type="password" name="password_confirmation" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email verified at</label>
            <input type="datetime-local" name="email_verified_at" value="{{ old('email_verified_at', optional($user->email_verified_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            <x-admin.field-error name="email_verified_at" />
        </div>
        <div class="flex items-end">
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-brand focus:ring-brand" @checked(old('is_active', $user->is_active ? '1' : '0') == '1')>
                Active
            </label>
        </div>
    </div>

    <fieldset>
        <legend class="text-sm font-semibold text-gray-900">Roles</legend>
        <div class="mt-3 grid gap-2 sm:grid-cols-2">
            @foreach ($roles as $role)
                @continue($role->isSuperAdmin() && ! auth()->user()->isSuperAdmin())
                <label class="flex items-start gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mt-0.5 rounded border-gray-300 text-brand focus:ring-brand" @checked($selectedRoles->contains($role->id))>
                    <span>
                        <span class="font-medium text-gray-800">{{ $role->name }}</span>
                        @if ($role->description)
                            <span class="block text-xs text-gray-400">{{ $role->description }}</span>
                        @endif
                    </span>
                </label>
            @endforeach
        </div>
        <x-admin.field-error name="roles" />
    </fieldset>

    <div class="flex gap-3">
        <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Update</button>
        <a href="{{ route('admin.users.show', $user) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
