@extends('layouts.admin')
@section('title','Product Categories')
@section('heading','Product Categories')
@section('actions')
<a href="{{ route('admin.hydroponics.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">New Category</a>
@endsection
@section('content')<livewire:admin.hydroponics-table />@endsection
