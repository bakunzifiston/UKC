<div>
    @error('delete')
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $message }}</div>
    @enderror

    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($kpis as $kpi)
            <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :trend="$kpi['trend'] ?? null" :trend-positive="$kpi['positive'] ?? true" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
        @endforeach
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search roles..."
               class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('name')">Name</button></th>
                <th class="px-4 py-3">Users</th>
                <th class="px-4 py-3">Permissions</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($roles as $role)
                <tr>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-800">{{ $role->name }}</div>
                        <div class="text-xs text-slate-400">{{ $role->slug }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $role->users_count }}</td>
                    <td class="px-4 py-3">{{ $role->isSuperAdmin() ? 'All' : $role->permissions_count }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.roles.show', $role) }}" class="text-brand hover:underline">View</a>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-brand hover:underline">Edit</a>
                        @can('roles.delete')
                            <button type="button" wire:click="delete({{ $role->id }})" wire:confirm="Delete this role?" class="text-red-600 hover:underline">Delete</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-10 text-center text-slate-400">No roles found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $roles->links() }}</div>
</div>
