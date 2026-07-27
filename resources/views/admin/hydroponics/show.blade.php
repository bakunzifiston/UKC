@extends('layouts.admin')
@section('title','Category')
@section('heading','Category Details')
@section('actions')<a href="{{ route('admin.hydroponics.edit',$hydroponics) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Edit</a>@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border bg-white p-6 text-sm">
<div><span class="font-medium text-gray-500">Site:</span> {{ $hydroponics->site?->site_name }}</div>
<div><span class="font-medium text-gray-500">Category:</span> {{ $hydroponics->hydroponics_name }}</div>
<div><span class="font-medium text-gray-500">Description:</span> {{ $hydroponics->description ?: '—' }}</div>
</div>@endsection
