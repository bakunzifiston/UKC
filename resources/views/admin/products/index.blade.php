@extends('layouts.admin')
@section('title','Products')
@section('heading','Products')
@section('actions')
<a href="{{ route('admin.products.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">New Product</a>
@endsection
@section('content')<livewire:admin.products-table />@endsection
