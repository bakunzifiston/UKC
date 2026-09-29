<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Support\AccessCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index');
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except('permissions')->all();
        $data['is_system'] = false;

        $role = Role::create($data);
        $role->permissions()->sync($request->validated('permissions'));

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function show(Role $role): View
    {
        $role->load(['permissions', 'users']);

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $data = collect($request->validated())->except('permissions')->all();

        if ($role->is_system) {
            unset($data['slug']);
        }

        $role->update($data);

        if (! $role->isSuperAdmin()) {
            $role->permissions()->sync($request->validated('permissions'));
        } else {
            $role->permissions()->sync(
                Permission::query()->where('is_system', true)->pluck('id')
            );
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system || $role->slug === AccessCatalog::SUPER_ADMIN) {
            return back()->withErrors(['role' => 'This role is required by the system and cannot be deleted.']);
        }

        if ($role->users()->exists()) {
            return back()->withErrors(['role' => 'Reassign users before deleting this role.']);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }

    /**
     * @return \Illuminate\Support\Collection<string, \Illuminate\Support\Collection<int, Permission>>
     */
    private function permissionGroups()
    {
        return Permission::query()->orderBy('module')->orderBy('name')->get()->groupBy('module');
    }
}
