@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Users')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-light">New User</a>
@endsection

@section('content')
    <livewire:admin.users-table />
@endsection
