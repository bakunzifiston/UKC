@extends('layouts.admin')
@section('title','Sales')
@section('heading','Sales')
@section('actions')
<a href="{{ route('admin.sales.create') }}" class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm font-medium text-white">New Sale</a>
@endsection
@section('content')<livewire:admin.sales-table />@endsection
