@extends('layouts.admin')
@section('title', 'Clients')
@section('heading', 'Clients')
@section('actions')
<a href="{{ route('admin.clients.create') }}" class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm font-medium text-white">New Client</a>
@endsection
@section('content')
<livewire:admin.clients-table />
@endsection
