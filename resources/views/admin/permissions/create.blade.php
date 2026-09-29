@extends('layouts.admin')
@section('title', 'Create Permission')
@section('heading', 'Create Permission')
@section('content')
<form method="POST" action="{{ route('admin.permissions.store') }}" class="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="name" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="reports.export" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="slug" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Module</label>
        <input type="text" name="module" value="{{ old('module') }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="module" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="2" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ old('description') }}</textarea>
        <x-admin.field-error name="description" />
    </div>
    <div class="flex gap-3">
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Save</button>
        <a href="{{ route('admin.permissions.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
