@extends('layouts.admin')
@section('title', 'Supplier')
@section('heading', 'Supplier Details')
@section('actions')
    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection
@section('content')
<x-admin.party-details :record="$supplier" />
@endsection
