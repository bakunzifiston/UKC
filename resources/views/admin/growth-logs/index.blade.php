@extends('layouts.admin')
@section('title','Growth Logs')
@section('heading','Growth Logs')
@section('actions')
<a href="{{ route('admin.growth-logs.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">New Growth Log</a>
@endsection
@section('content')<livewire:admin.growth-logs-table />@endsection
