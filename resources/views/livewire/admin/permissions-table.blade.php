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
        <x-admin.filter-bar title="Permission filters">
            <x-admin.filter-field label="Module" wire-model="module" type="select">
                <option value="">All modules</option>
                @foreach ($moduleOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </x-admin.filter-field>
        </x-admin.filter-bar>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search permissions..."
               class="w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('name')">Name</button></th>
                <th class="px-4 py-3"><button type="button" wire:click="sortBy('module')">Module</button></th>
                <th class="px-4 py-3">Roles</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($permissions as $permission)
                <tr>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-800">{{ $permission->name }}</div>
                        <div class="text-xs text-slate-400">{{ $permission->slug }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $permission->module }}</td>
                    <td class="px-4 py-3">{{ $permission->roles_count }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('admin.permissions.show', $permission) }}" class="text-brand hover:underline">View</a>
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-brand hover:underline">Edit</a>
                        @can('permissions.delete')
                            <button type="button" wire:click="delete({{ $permission->id }})" wire:confirm="Delete this permission?" class="text-red-600 hover:underline">Delete</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-10 text-center text-slate-400">No permissions for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $permissions->links() }}</div>
</div>
