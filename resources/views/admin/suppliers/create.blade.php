@extends('layouts.admin')
@section('title', 'Create Supplier')
@section('heading', 'Create Supplier')
@section('content')
<form method="POST" action="{{ route('admin.suppliers.store') }}" class="max-w-3xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    <x-admin.party-fields />
    <div class="flex gap-3">
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/15 hover:bg-brand-light">Save</button>
        <a href="{{ route('admin.suppliers.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
