@extends('layouts.admin')

@section('title', 'Edit Site')
@section('heading', 'Edit Site')

@section('content')
<form method="POST" action="{{ route('admin.sites.update', $site) }}" class="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-sm font-medium text-gray-700">Site name</label>
        <input type="text" name="site_name" value="{{ old('site_name', $site->{'site_name'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="site_name" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Province</label>
        <input type="text" name="province" value="{{ old('province', $site->{'province'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="province" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">District</label>
        <input type="text" name="district" value="{{ old('district', $site->{'district'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="district" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Sector</label>
        <input type="text" name="sector" value="{{ old('sector', $site->{'sector'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="sector" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Village</label>
        <input type="text" name="village" value="{{ old('village', $site->{'village'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="village" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Google map</label>
        <input type="text" name="googlemap" value="{{ old('googlemap', $site->{'googlemap'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="googlemap" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Manager name</label>
        <input type="text" name="manager_name" value="{{ old('manager_name', $site->{'manager_name'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="manager_name" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Contact details</label>
        <input type="text" name="contact-details" value="{{ old('contact-details', $site->{'contact-details'}) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        <x-admin.field-error name="contact-details" />
    </div>
    <div class="flex gap-3">
        <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">Update</button>
        <a href="{{ route('admin.sites.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Cancel</a>
    </div>
</form>
@endsection