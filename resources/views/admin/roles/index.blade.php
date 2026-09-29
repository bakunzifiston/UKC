@extends('layouts.admin')
@section('title', 'Roles')
@section('heading', 'Roles')
@section('actions')
    @can('roles.create')
        <a href="{{ route('admin.roles.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-light">New Role</a>
    @endcan
@endsection
@section('content')
    <p class="mb-4 max-w-3xl text-sm text-slate-500">Roles control what a user can do in the admin. They are separate from the job title stored on a staff record.</p>
    <livewire:admin.roles-table />
@endsection
