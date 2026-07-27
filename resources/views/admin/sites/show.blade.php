@extends('layouts.admin')

@section('title', 'Site')
@section('heading', 'Site Details')

@section('actions')
    <a href="{{ route('admin.sites.edit', $site) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Edit</a>
@endsection

@section('content')
<div class="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-sm">
    <div><span class="font-medium text-gray-500">Site name:</span> {{ $site->{'site_name'} }}</div>
    <div><span class="font-medium text-gray-500">Province:</span> {{ $site->{'province'} }}</div>
    <div><span class="font-medium text-gray-500">District:</span> {{ $site->{'district'} }}</div>
    <div><span class="font-medium text-gray-500">Sector:</span> {{ $site->{'sector'} }}</div>
    <div><span class="font-medium text-gray-500">Village:</span> {{ $site->{'village'} }}</div>
    <div><span class="font-medium text-gray-500">Google map:</span> {{ $site->{'googlemap'} }}</div>
    <div><span class="font-medium text-gray-500">Manager name:</span> {{ $site->{'manager_name'} }}</div>
    <div><span class="font-medium text-gray-500">Contact details:</span> {{ $site->{'contact-details'} }}</div>
</div>
@endsection