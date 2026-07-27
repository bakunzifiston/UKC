@extends('layouts.admin')

@section('title', 'User')
@section('heading', 'User Details')

@section('actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection

@section('content')
<div class="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-sm">
    <div><span class="font-medium text-gray-500">Name:</span> {{ $user->name }}</div>
    <div><span class="font-medium text-gray-500">Email:</span> {{ $user->email }}</div>
    <div><span class="font-medium text-gray-500">Verified:</span> {{ $user->email_verified_at?->format('Y-m-d H:i') ?? '—' }}</div>
    <div><span class="font-medium text-gray-500">Created:</span> {{ $user->created_at }}</div>
</div>
@endsection
