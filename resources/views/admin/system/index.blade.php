@extends('layouts.admin')
@section('title', 'System')
@section('heading', 'System configuration')
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 text-sm shadow-sm">
    <p class="text-gray-500">Super Admin can open this page along with users, roles, permissions, and every application module. Secrets and environment files stay out of the admin interface.</p>
    @foreach ($settings as $label => $value)
        <div><span class="font-medium text-gray-500">{{ $label }}:</span> {{ $value }}</div>
    @endforeach
</div>
@endsection
