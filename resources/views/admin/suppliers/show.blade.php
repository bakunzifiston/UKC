@extends('layouts.admin')
@section('title', 'Supplier')
@section('heading', 'Supplier Details')
@section('actions')
    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 text-sm shadow-sm">
    <div><span class="font-medium text-gray-500">Name:</span> {{ $supplier->supplier_name }}</div>
    <div><span class="font-medium text-gray-500">Contact:</span> {{ $supplier->contact_info }}</div>
    <div><span class="font-medium text-gray-500">Address:</span> {{ $supplier->address ?: '—' }}</div>
</div>
@endsection
