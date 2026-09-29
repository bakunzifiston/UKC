@extends('layouts.admin')
@section('title', 'Edit Role')
@section('heading', 'Edit Role')
@section('content')
<form method="POST" action="{{ route('admin.roles.update', $role) }}" class="max-w-4xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            <x-admin.field-error name="name" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $role->slug) }}" @disabled($role->is_system) class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @if ($role->is_system)
                <input type="hidden" name="slug" value="{{ $role->slug }}">
            @endif
            <x-admin.field-error name="slug" />
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="2" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ old('description', $role->description) }}</textarea>
        <x-admin.field-error name="description" />
    </div>
    <div>
        <h3 class="text-sm font-semibold text-gray-900">Permissions</h3>
        <div class="mt-3">
            <x-admin.permission-checkboxes :groups="$permissionGroups" :selected="old('permissions', $role->permissions->pluck('id')->all())" :locked="$role->isSuperAdmin()" />
        </div>
    </div>
    <div class="flex gap-3">
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Update</button>
        <a href="{{ route('admin.roles.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
