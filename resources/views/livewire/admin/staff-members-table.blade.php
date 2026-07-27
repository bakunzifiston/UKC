<div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" wire:loading.class="opacity-60">
        @foreach ($kpis as $kpi)
            <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :trend="$kpi['trend'] ?? null" :trend-positive="$kpi['positive'] ?? true" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
        @endforeach
    </div>

    <div class="mb-4">
        <x-admin.filter-bar title="Staff filters">
            <x-admin.date-range-filters />
            <x-admin.filter-field label="Role" wire-model="role" type="select">
                <option value="">All roles</option>
                @foreach ($roleOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </x-admin.filter-field>
        </x-admin.filter-bar>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search staff..." class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-3 top-3 z-10 rounded-md bg-white/90 px-3 py-1 text-xs text-slate-500 shadow">Updating…</div>
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('name')">Name</button></th>
                <th class="px-4 py-3">Phone</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('role')">Role</button></th>
                <th class="px-4 py-3">Site</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($staffMembers as $member)
                <tr>
                    <td class="px-4 py-3">{{ $member->name }}</td>
                    <td class="px-4 py-3">{{ $member->phone_number }}</td>
                    <td class="px-4 py-3">{{ $member->email }}</td>
                    <td class="px-4 py-3">{{ $member->role }}</td>
                    <td class="px-4 py-3">{{ $member->site_name }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.staff-members.edit', $member) }}" class="text-brand hover:underline">Edit</a>
                        <button type="button" wire:click="delete({{ $member->id }})" wire:confirm="Delete?" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">No staff for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $staffMembers->links() }}</div>
</div>
