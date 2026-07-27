<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'UKC') }}</title>
    <link rel="icon" href="{{ asset('images/ukc-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#F3F5F8] font-sans text-slate-900 antialiased">
@php
    $navIcon = fn (string $d) => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0">'.$d.'</svg>';
    $icons = [
        'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />',
        'staff' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.897 1.125 1.125 0 0 0-.36-2.11 6.75 6.75 0 0 0-2.761-.75 6.75 6.75 0 0 0-2.761.75 1.125 1.125 0 0 0-.36 2.11ZM9 19.128a9.38 9.38 0 0 1-2.625.372 9.337 9.337 0 0 1-4.121-.897 1.125 1.125 0 0 1 .36-2.11 6.75 6.75 0 0 1 2.761-.75 6.75 6.75 0 0 1 2.761.75 1.125 1.125 0 0 1 .36 2.11ZM12 12.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />',
        'sites' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />',
        'suppliers' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.236v5.236a2.25 2.25 0 0 1-2.25 2.25h-4.5A2.25 2.25 0 0 1 5.25 12.75v-5.25" />',
        'visitors' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        'categories' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6c0-4.125-3-7.5-6-9.75-3 2.25-6 5.625-6 9.75a6 6 0 0 0 6 6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75V9" />',
        'products' => '<path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />',
        'supply' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />',
        'clients' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />',
        'sales' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />',
        'growth' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
    ];
    $navLink = function (string $routePattern, string $route, string $label, string $iconKey) use ($icons, $navIcon) {
        $active = request()->routeIs($routePattern);
        $classes = $active
            ? 'bg-brand-light text-white shadow-sm'
            : 'text-white/70 hover:bg-white/10 hover:text-white';

        return '<a href="'.e(route($route)).'" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition '.$classes.'">'.$navIcon($icons[$iconKey]).'<span>'.e($label).'</span></a>';
    };
@endphp

<div class="flex min-h-screen gap-0 p-3 lg:gap-3 lg:p-4">
    <aside class="hidden w-[15.5rem] shrink-0 flex-col rounded-[1.75rem] bg-brand text-white shadow-xl shadow-brand/20 lg:flex">
        <div class="px-5 pb-2 pt-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center rounded-2xl bg-white px-3 py-3">
                <img src="{{ asset('images/ukc-logo.png') }}" alt="{{ config('app.name', 'UKC') }}" class="h-9 w-auto">
            </a>
        </div>

        <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
            <div class="space-y-1">
                {!! $navLink('admin.dashboard', 'admin.dashboard', 'Dashboard', 'dashboard') !!}
            </div>
            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/40">Operations</p>
                <div class="space-y-1">
                    {!! $navLink('admin.staff-members.*', 'admin.staff-members.index', 'Staff', 'staff') !!}
                    {!! $navLink('admin.sites.*', 'admin.sites.index', 'Sites', 'sites') !!}
                    {!! $navLink('admin.suppliers.*', 'admin.suppliers.index', 'Suppliers', 'suppliers') !!}
                    {!! $navLink('admin.visitors.*', 'admin.visitors.index', 'Visitors', 'visitors') !!}
                </div>
            </div>
            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/40">Products &amp; Sales</p>
                <div class="space-y-1">
                    {!! $navLink('admin.hydroponics.*', 'admin.hydroponics.index', 'Categories', 'categories') !!}
                    {!! $navLink('admin.products.*', 'admin.products.index', 'Products', 'products') !!}
                    {!! $navLink('admin.product-suppliers.*', 'admin.product-suppliers.index', 'Supplies', 'supply') !!}
                    {!! $navLink('admin.clients.*', 'admin.clients.index', 'Clients', 'clients') !!}
                    {!! $navLink('admin.sales.*', 'admin.sales.index', 'Sales', 'sales') !!}
                    {!! $navLink('admin.growth-logs.*', 'admin.growth-logs.index', 'Growth Logs', 'growth') !!}
                    {!! $navLink('admin.users.*', 'admin.users.index', 'Users', 'users') !!}
                </div>
            </div>
        </nav>

        <div class="border-t border-white/10 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-white/50 hover:text-white">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-[1.5rem] bg-white px-4 py-3 shadow-[0_8px_30px_rgb(15,23,42,0.04)] sm:px-6">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-base font-semibold text-slate-900">Hello, {{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-400">@yield('heading', 'Admin')</p>
                </div>
            </div>

            <div class="order-3 flex w-full items-center gap-2 sm:order-none sm:w-auto sm:flex-1 sm:justify-center">
                <div class="relative w-full max-w-md">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </span>
                    <input type="search" placeholder="Search…" class="ui-input w-full pl-9" disabled title="Use module search filters">
                </div>
            </div>

            <div class="flex items-center gap-2">
                @hasSection('actions')
                    <div class="flex items-center gap-2">@yield('actions')</div>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="hidden h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-brand-soft hover:text-brand sm:inline-flex" title="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </a>
            </div>
        </header>

        {{-- Mobile nav --}}
        <div class="mb-4 flex gap-2 overflow-x-auto pb-1 lg:hidden">
            <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap rounded-full bg-brand px-3 py-1.5 text-xs font-medium text-white">Dashboard</a>
            <a href="{{ route('admin.sales.index') }}" class="whitespace-nowrap rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm">Sales</a>
            <a href="{{ route('admin.products.index') }}" class="whitespace-nowrap rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm">Products</a>
            <a href="{{ route('admin.sites.index') }}" class="whitespace-nowrap rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm">Sites</a>
            <a href="{{ route('admin.clients.index') }}" class="whitespace-nowrap rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm">Clients</a>
        </div>

        <main class="flex-1 px-1 pb-4 sm:px-0">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-brand/15 bg-brand-soft px-4 py-3 text-sm text-brand">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
