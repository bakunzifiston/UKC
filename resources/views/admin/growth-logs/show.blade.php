@extends('layouts.admin')
@section('title','Growth Log')
@section('heading','Growth Log Details')
@section('actions')<a href="{{ route('admin.growth-logs.edit',$growthLog) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light text-white">Edit</a>@endsection
@section('content')
<div class="max-w-xl space-y-3 rounded-lg border bg-white p-6 text-sm">
<div><span class="font-medium text-gray-500">Site:</span> {{ $growthLog->site?->site_name }}</div>
<div><span class="font-medium text-gray-500">Category:</span> {{ $growthLog->hydroponics?->hydroponics_name }}</div>
<div><span class="font-medium text-gray-500">Product:</span> {{ $growthLog->product?->product_name }}</div>
<div><span class="font-medium text-gray-500">Day:</span> {{ $growthLog->day_number }}</div>
<div><span class="font-medium text-gray-500">Trays:</span> {{ $growthLog->growth_value }}</div>
<div><span class="font-medium text-gray-500">Date:</span> {{ $growthLog->log_date }}</div>
</div>@endsection
