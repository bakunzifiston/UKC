@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('heading', 'Edit Supplier')
@section('content')
<form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf @method('PUT')
    <div><label class="block text-sm font-medium text-gray-700">Supplier name</label><input type="text" name="supplier_name" value="{{ old('supplier_name', $supplier->supplier_name) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"></div>
    <x-admin.field-error name="supplier_name" />
    <div><label class="block text-sm font-medium text-gray-700">Contact info</label><input type="text" name="contact_info" value="{{ old('contact_info', $supplier->contact_info) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"></div>
    <x-admin.field-error name="contact_info" />
    <div><label class="block text-sm font-medium text-gray-700">Address</label><textarea name="address" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ old('address', $supplier->address) }}</textarea></div>
    <x-admin.field-error name="address" />
    <div class="flex gap-3"><button class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm font-medium text-white">Update</button><a href="{{ route('admin.suppliers.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a></div>
</form>
@endsection
