@props([
    'label',
    'wireModel',
    'type' => 'text',
])

<label class="block text-xs font-medium text-slate-500">
    {{ $label }}
    @if ($type === 'select')
        <select wire:model.live="{{ $wireModel }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
            {{ $slot }}
        </select>
    @else
        <input
            type="{{ $type }}"
            wire:model.live.debounce.300ms="{{ $wireModel }}"
            {{ $attributes->merge(['class' => 'mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand']) }}
        >
    @endif
</label>
