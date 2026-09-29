@extends('layouts.admin')
@section('title','Client')
@section('heading','Client Details')
@section('actions')<a href="{{ route('admin.clients.edit',$client) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white shadow-sm shadow-brand/20 hover:bg-brand-light">Edit</a>@endsection
@section('content')
<x-admin.party-details :record="$client" />
@endsection
