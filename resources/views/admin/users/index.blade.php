@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Users')

@section('actions')
    @can('users.create')
        <a href="{{ route('admin.users.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-light">New User</a>
    @endcan
@endsection

@section('content')
    <livewire:admin.users-table />
@endsection
