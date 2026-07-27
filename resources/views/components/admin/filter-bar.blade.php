@props([
    'title' => 'Filters',
])

<div {{ $attributes->merge(['class' => 'ui-card p-4']) }}>
    <div class="mb-3 flex items-center justify-between gap-2">
        <h3 class="text-sm font-semibold text-slate-700">{{ $title }}</h3>
        @isset($actions)
            <div>{{ $actions }}</div>
        @endisset
    </div>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        {{ $slot }}
    </div>
</div>
