@extends('layouts.admin')
@section('title','Staff Members')
@section('heading','Staff Members')
@section('actions')
<a href="{{ route('admin.staff-members.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white">New Staff Member</a>
@endsection
@section('content')<livewire:admin.staff-members-table />@endsection
