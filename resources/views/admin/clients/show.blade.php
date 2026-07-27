@extends('layouts.admin')
@section('title','Client')
@section('heading','Client Details')
@section('actions')<a href="{{ route('admin.clients.edit',$client) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Edit</a>@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border bg-white p-6 text-sm">
<div><span class="font-medium text-gray-500">Name:</span> {{ $client->client_name }}</div>
<div><span class="font-medium text-gray-500">Contact:</span> {{ $client->contact_info }}</div>
<div><span class="font-medium text-gray-500">Address:</span> {{ $client->address ?: '—' }}</div>
</div>@endsection
