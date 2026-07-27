<div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" wire:loading.class="opacity-60">
        @foreach ($kpis as $kpi)
            <x-kpi-card
                :label="$kpi['label']"
                :value="$kpi['value']"
                :description="$kpi['description']"
                :trend="$kpi['trend'] ?? null"
                :trend-positive="$kpi['positive'] ?? true"
                :icon="$kpi['icon'] ?? 'chart'"
                :tone="$kpi['tone'] ?? 'emerald'"
            />
        @endforeach
    </div>

    <div class="mb-4">
        <x-admin.filter-bar title="Sales filters">
            <x-admin.date-range-filters />
            <x-admin.filter-field label="Client" wire-model="clientId" type="select">
                <option value="">All clients</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                @endforeach
            </x-admin.filter-field>
            <x-admin.filter-field label="Site" wire-model="siteId" type="select">
                <option value="">All sites</option>
                @foreach ($sites as $site)
                    <option value="{{ $site->id }}">{{ $site->site_name }}</option>
                @endforeach
            </x-admin.filter-field>
        </x-admin.filter-bar>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search sales..."
               class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-3 top-3 z-10 rounded-md bg-white/90 px-3 py-1 text-xs font-medium text-slate-500 shadow">Updating…</div>
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Client</th>
                <th class="px-4 py-3">Qty (kg)</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('sale_date')">Date</button></th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($sales as $sale)
                <tr>
                    <td class="px-4 py-3">{{ $sale->product?->product_name }}</td>
                    <td class="px-4 py-3">{{ $sale->client?->client_name }}</td>
                    <td class="px-4 py-3">{{ $sale->quantity_sold }} kg</td>
                    <td class="px-4 py-3">{{ number_format((float) $sale->total_price, 2) }} RWF</td>
                    <td class="px-4 py-3">{{ $sale->sale_date }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.sales.show', $sale) }}" class="text-brand hover:underline">View</a>
                        <a href="{{ route('admin.sales.edit', $sale) }}" class="text-brand hover:underline">Edit</a>
                        <button type="button" wire:click="delete({{ $sale->id }})" wire:confirm="Delete?" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No sales for this filter.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $sales->links() }}</div>
</div>
