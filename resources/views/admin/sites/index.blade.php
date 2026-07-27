@extends('layouts.admin')

@section('title', 'Sites')
@section('heading', 'Sites')

@section('actions')
    <a href="{{ route('admin.sites.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-light">New Site</a>
@endsection

@section('content')
    <livewire:admin.sites-table />
@endsection