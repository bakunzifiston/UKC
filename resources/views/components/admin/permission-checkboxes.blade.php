@props(['groups', 'selected' => [], 'locked' => false])

<div class="space-y-4">
    @if ($locked)
        <p class="rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-800">Super Admin always has every permission. These assignments stay in place so the role still lists them.</p>
    @endif
    @foreach ($groups as $module => $permissions)
        <fieldset class="rounded-md border border-gray-200 p-4">
            <legend class="px-1 text-sm font-semibold text-gray-900">{{ $module }}</legend>
            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                @foreach ($permissions as $permission)
                    <label class="flex items-start gap-2 text-sm text-gray-700">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            class="mt-0.5 rounded border-gray-300 text-brand focus:ring-brand"
                            @checked($locked || collect($selected)->contains($permission->id))
                            @disabled($locked)
                        >
                        <span>
                            <span class="font-medium">{{ $permission->name }}</span>
                            <span class="block text-xs text-gray-400">{{ $permission->slug }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </fieldset>
    @endforeach
    <x-admin.field-error name="permissions" />
</div>
