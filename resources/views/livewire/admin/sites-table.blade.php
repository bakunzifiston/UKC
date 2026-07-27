<div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" wire:loading.class="opacity-60">
        @foreach ($kpis as $kpi)
            <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :trend="$kpi['trend'] ?? null" :trend-positive="$kpi['positive'] ?? true" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
        @endforeach
    </div>

    <div class="mb-4">
        <x-admin.filter-bar title="Site filters">
            <x-admin.date-range-filters />
            <x-admin.filter-field label="Province" wire-model="province" type="select">
                <option value="">All provinces</option>
                @foreach ($provinceOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </x-admin.filter-field>
        </x-admin.filter-bar>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search sites..." class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-3 top-3 z-10 rounded-md bg-white/90 px-3 py-1 text-xs text-slate-500 shadow">Updating…</div>
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('site_name')">Site</button></th>
                <th class="px-4 py-3">District</th>
                <th class="px-4 py-3">Sector</th>
                <th class="px-4 py-3">Manager</th>
                <th class="px-4 py-3">Contact</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse ($sites as $site)
                <tr>
                    <td class="px-4 py-3">{{ $site->site_name }}</td>
                    <td class="px-4 py-3">{{ $site->district }}</td>
                    <td class="px-4 py-3">{{ $site->sector }}</td>
                    <td class="px-4 py-3">{{ $site->manager_name }}</td>
                    <td class="px-4 py-3">{{ $site->{'contact-details'} }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.sites.show', $site) }}" class="text-brand hover:underline">View</a>
                        <a href="{{ route('admin.sites.edit', $site) }}" class="text-brand hover:underline">Edit</a>
                        <button type="button" wire:click="delete({{ $site->id }})" wire:confirm="Delete this site?" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">No sites for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $sites->links() }}</div>
</div>
