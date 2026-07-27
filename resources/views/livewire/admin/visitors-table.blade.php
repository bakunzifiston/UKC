<div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" wire:loading.class="opacity-60">
        @foreach ($kpis as $kpi)
            <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :trend="$kpi['trend'] ?? null" :trend-positive="$kpi['positive'] ?? true" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
        @endforeach
    </div>

    <div class="mb-4">
        <x-admin.filter-bar title="Visitor filters">
            <x-admin.date-range-filters />
            <x-admin.filter-field label="Email" wire-model="emailFilter" type="select">
                <option value="">All</option>
                <option value="with">Has email</option>
                <option value="without">No email</option>
            </x-admin.filter-field>
        </x-admin.filter-bar>
        <p class="mt-2 text-xs text-slate-400">Visitors have no site foreign key in the schema, so site filtering is not available.</p>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search visitors..." class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-3 top-3 z-10 rounded-md bg-white/90 px-3 py-1 text-xs text-slate-500 shadow">Updating…</div>
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('name')">Name</button></th>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('email')">Email</button></th>
                <th class="px-4 py-3">Phone</th>
                <th class="px-4 py-3">Address</th>
                <th class="px-4 py-3">Purpose</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($visitors as $visitor)
                <tr>
                    <td class="px-4 py-3">{{ $visitor->name }}</td>
                    <td class="px-4 py-3">{{ $visitor->email }}</td>
                    <td class="px-4 py-3">{{ $visitor->phone }}</td>
                    <td class="px-4 py-3">{{ $visitor->address }}</td>
                    <td class="px-4 py-3">{{ Str::limit($visitor->visit_purpose, 30) }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.visitors.edit', $visitor) }}" class="text-brand hover:underline">Edit</a>
                        <button type="button" wire:click="delete({{ $visitor->id }})" wire:confirm="Delete?" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">No visitors for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $visitors->links() }}</div>
</div>
