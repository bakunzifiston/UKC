@extends('layouts.admin')
@section('title', 'Permissions')
@section('heading', 'Permissions')
@section('actions')
    @can('permissions.create')
        <a href="{{ route('admin.permissions.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-light">New Permission</a>
    @endcan
@endsection
@section('content')
    <livewire:admin.permissions-table />
@endsection
