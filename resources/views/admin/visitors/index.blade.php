@extends('layouts.admin')
@section('title','Visitors')
@section('heading','Visitors')
@section('actions')
<a href="{{ route('admin.visitors.create') }}" class="rounded-xl bg-brand shadow-sm shadow-brand/15 hover:bg-brand-light px-4 py-2 text-sm font-medium text-white">New Visitor</a>
@endsection
@section('content')<livewire:admin.visitors-table />@endsection
