<div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" wire:loading.class="opacity-60">
        @foreach ($kpis as $kpi)
            <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :trend="$kpi['trend'] ?? null" :trend-positive="$kpi['positive'] ?? true" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
        @endforeach
    </div>

    <div class="mb-4">
        <x-admin.filter-bar title="Supplier filters">
            <x-admin.date-range-filters />
        </x-admin.filter-bar>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search suppliers..." class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-3 top-3 z-10 rounded-md bg-white/90 px-3 py-1 text-xs text-slate-500 shadow">Updating…</div>
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('supplier_name')">Name</button></th>
                <th class="px-4 py-3">Contact</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse ($suppliers as $supplier)
                <tr>
                    <td class="px-4 py-3">{{ $supplier->supplier_name }}</td>
                    <td class="px-4 py-3">{{ $supplier->contact_info }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.suppliers.show', $supplier) }}" class="text-brand hover:underline">View</a>
                        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="text-brand hover:underline">Edit</a>
                        <button type="button" wire:click="delete({{ $supplier->id }})" wire:confirm="Delete?" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-10 text-center text-slate-400">No suppliers for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $suppliers->links() }}</div>
</div>
