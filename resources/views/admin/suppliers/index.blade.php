@extends('layouts.admin')
@section('title', 'Suppliers')
@section('heading', 'Suppliers')
@section('actions')
    <a href="{{ route('admin.suppliers.create') }}" class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm font-medium text-white">New Supplier</a>
@endsection
@section('content')
    <livewire:admin.suppliers-table />
@endsection
