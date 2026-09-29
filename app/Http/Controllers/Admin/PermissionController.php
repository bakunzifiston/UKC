<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.permissions.index');
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        Permission::create([
            ...$request->validated(),
            'is_system' => false,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created.');
    }

    public function show(Permission $permission): View
    {
        $permission->load('roles');

        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if ($permission->is_system) {
            return back()->withErrors(['permission' => 'System permissions cannot be deleted.']);
        }

        if ($permission->roles()->exists()) {
            return back()->withErrors(['permission' => 'Remove this permission from its roles before deleting it.']);
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted.');
    }
}
