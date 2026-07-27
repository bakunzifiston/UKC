@extends('layouts.admin')
@section('title','Product Suppliers')
@section('heading','Product Suppliers')
@section('actions')
<a href="{{ route('admin.product-suppliers.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">New Record</a>
@endsection
@section('content')<livewire:admin.product-suppliers-table />@endsection
