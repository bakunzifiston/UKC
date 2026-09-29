<div>
    @error('delete')
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $message }}</div>
    @enderror
    @error('active')
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $message }}</div>
    @enderror

    <div class="mb-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" wire:loading.class="opacity-60">
        @foreach ($kpis as $kpi)
            <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :trend="$kpi['trend'] ?? null" :trend-positive="$kpi['positive'] ?? true" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
        @endforeach
    </div>

    <div class="mb-4">
        <x-admin.filter-bar title="User filters">
            <x-admin.date-range-filters />
            <x-admin.filter-field label="Role" wire-model="role" type="select">
                <option value="">All roles</option>
                @foreach ($roleOptions as $option)
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                @endforeach
            </x-admin.filter-field>
            <x-admin.filter-field label="Status" wire-model="status" type="select">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </x-admin.filter-field>
            <x-admin.filter-field label="Verified" wire-model="verifiedFilter" type="select">
                <option value="">All</option>
                <option value="yes">Verified</option>
                <option value="no">Unverified</option>
            </x-admin.filter-field>
        </x-admin.filter-bar>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search users..."
               class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-3 top-3 z-10 rounded-md bg-white/90 px-3 py-1 text-xs text-slate-500 shadow">Updating…</div>
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('name')" class="hover:text-slate-900">Name</button></th>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('email')" class="hover:text-slate-900">Email</button></th>
                <th class="px-4 py-3">Roles</th>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('is_active')" class="hover:text-slate-900">Status</button></th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($users as $user)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-brand hover:underline">View</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-brand hover:underline">Edit</a>
                        @can('users.update')
                            <button type="button" wire:click="toggleActive({{ $user->id }})" class="text-slate-600 hover:underline">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button>
                        @endcan
                        @can('users.delete')
                            <button type="button" wire:click="delete({{ $user->id }})" wire:confirm="Delete this user?" class="text-red-600 hover:underline">Delete</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">No users for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
