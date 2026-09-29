@extends('layouts.admin')
@section('title','Edit Client')
@section('heading','Edit Client')
@section('content')
<form method="POST" action="{{ route('admin.clients.update', $client) }}" class="max-w-3xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    <x-admin.party-fields :record="$client" />
    <div class="flex gap-3">
        <button class="rounded-xl bg-brand px-4 py-2 text-sm text-white shadow-sm shadow-brand/15 hover:bg-brand-light">Update</button>
        <a href="{{ route('admin.clients.index') }}" class="rounded-md border px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection
