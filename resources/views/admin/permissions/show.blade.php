@extends('layouts.admin')
@section('title', $permission->name)
@section('heading', 'Permission Details')
@section('actions')
    <a href="{{ route('admin.permissions.edit', $permission) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection
@section('content')
<div class="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 text-sm shadow-sm">
    <div><span class="font-medium text-gray-500">Name:</span> {{ $permission->name }}</div>
    <div><span class="font-medium text-gray-500">Slug:</span> {{ $permission->slug }}</div>
    <div><span class="font-medium text-gray-500">Module:</span> {{ $permission->module }}</div>
    <div><span class="font-medium text-gray-500">Description:</span> {{ $permission->description ?: '—' }}</div>
    <div><span class="font-medium text-gray-500">Type:</span> {{ $permission->is_system ? 'System' : 'Custom' }}</div>
    <div>
        <span class="font-medium text-gray-500">Roles:</span>
        {{ $permission->roles->pluck('name')->join(', ') ?: '—' }}
    </div>
</div>
@endsection
