<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in — {{ config('app.name', 'UKC') }}</title>
    <link rel="icon" href="{{ asset('images/ukc-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: Figtree, ui-sans-serif, system-ui, sans-serif; }
        .login-shell {
            background: linear-gradient(145deg, #032804 0%, #053a06 45%, #0a5c0c 100%);
        }
        .welcome-panel {
            background:
                radial-gradient(circle at 18% 22%, rgba(10, 92, 12, 0.95) 0%, rgba(10, 92, 12, 0) 42%),
                radial-gradient(circle at 78% 18%, rgba(5, 58, 6, 0.9) 0%, rgba(5, 58, 6, 0) 38%),
                radial-gradient(circle at 62% 72%, rgba(3, 40, 4, 0.95) 0%, rgba(3, 40, 4, 0) 45%),
                radial-gradient(circle at 28% 78%, rgba(5, 58, 6, 0.85) 0%, rgba(5, 58, 6, 0) 40%),
                linear-gradient(160deg, #032804 0%, #053a06 40%, #0a5c0c 100%);
        }
    </style>
</head>
<body class="login-shell flex min-h-screen items-center justify-center p-4 antialiased sm:p-6">
    <div class="flex w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-brand-dark/40">
        {{-- Left welcome panel --}}
        <aside class="welcome-panel relative hidden w-[42%] flex-col justify-center px-10 py-14 text-white lg:flex xl:px-14">
            <div class="relative z-10 max-w-sm">
                <div class="mb-8 inline-flex rounded-2xl bg-white px-4 py-3 shadow-sm">
                    <img src="{{ asset('images/ukc-logo.png') }}" alt="{{ config('app.name', 'UKC') }}" class="h-12 w-auto">
                </div>
                <h1 class="text-4xl font-bold tracking-[0.08em] xl:text-5xl">WELCOME</h1>
                <p class="mt-3 text-sm font-semibold tracking-[0.18em] text-white/70">
                    {{ strtoupper(config('app.name', 'UKC')) }} ADMIN
                </p>
                <p class="mt-6 text-sm leading-relaxed text-white/75">
                    Sign in to manage sites, hydroponics, inventory, sales, and staff for the UKC information system.
                </p>
            </div>
        </aside>

        {{-- Right form panel --}}
        <section class="flex w-full flex-col justify-center bg-white px-8 py-10 sm:px-12 lg:w-[58%] lg:px-16 lg:py-14">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-6 lg:hidden">
                    <img src="{{ asset('images/ukc-logo.png') }}" alt="{{ config('app.name', 'UKC') }}" class="h-10 w-auto">
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Sign in</h2>
                <p class="mt-2 text-sm text-slate-400">
                    Enter your credentials to access the admin panel.
                </p>

                @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-4">
                    @csrf

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Email"
                            class="w-full rounded-xl border-0 bg-slate-100 py-3.5 pl-12 pr-4 text-sm text-slate-800 placeholder:text-slate-400 focus:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40"
                        >
                    </div>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <label for="password" class="sr-only">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                            class="w-full rounded-xl border-0 bg-slate-100 py-3.5 pl-12 pr-16 text-sm text-slate-800 placeholder:text-slate-400 focus:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40"
                        >
                        <button
                            type="button"
                            id="toggle-password"
                            class="absolute inset-y-0 right-0 px-4 text-xs font-semibold tracking-wide text-brand hover:text-brand-light"
                        >
                            SHOW
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-sm">
                        <label class="flex cursor-pointer items-center gap-2 text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand"
                            >
                            Remember me
                        </label>
                        <span class="text-slate-400">Forgot Password?</span>
                    </div>

                    <button
                        type="submit"
                        class="mt-2 w-full rounded-xl bg-brand px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-brand/30"
                    >
                        Sign in
                    </button>
                </form>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            this.textContent = showing ? 'SHOW' : 'HIDE';
        });
    </script>
</body>
</html>
