<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserPasswordRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Support\AccessGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $roleIds = array_map('intval', $request->validated('roles'));
        AccessGuard::assertCanAssign($request->user(), $roleIds);

        $data = collect($request->validated())->except(['roles'])->all();
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);
        $user->roles()->sync($roleIds);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function show(User $user): View
    {
        $user->load('roles.permissions');

        return view('admin.users.show', [
            'user' => $user,
            'permissions' => $user->effectivePermissions()->groupBy('module'),
        ]);
    }

    public function edit(User $user): View
    {
        AccessGuard::assertCanManage(request()->user(), $user);

        $user->load('roles');

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        AccessGuard::assertCanManage($request->user(), $user);

        $data = collect($request->validated())->except(['roles'])->all();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $roleIds = $request->exists('roles')
            ? array_map('intval', $request->validated('roles'))
            : null;

        if ($roleIds !== null) {
            AccessGuard::assertCanAssign($request->user(), $roleIds);
        }

        $willBeActive = array_key_exists('is_active', $data) ? (bool) $data['is_active'] : $user->is_active;
        AccessGuard::assertSuperAdminRemains($user, $roleIds, $willBeActive);

        $user->update($data);

        if ($roleIds !== null) {
            $user->roles()->sync($roleIds);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function updatePassword(UpdateUserPasswordRequest $request, User $user): RedirectResponse
    {
        AccessGuard::assertCanManage($request->user(), $user);

        $user->update(['password' => $request->validated('password')]);

        return redirect()->route('admin.users.show', $user)->with('success', 'Password updated.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        AccessGuard::assertCanManage($request->user(), $user);

        $willBeActive = ! $user->is_active;

        try {
            AccessGuard::assertSuperAdminRemains($user, null, $willBeActive);
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        $user->update(['is_active' => $willBeActive]);

        return back()->with('success', $willBeActive ? 'User activated.' : 'User deactivated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $denial = AccessGuard::deleteDenial($request->user(), $user);

        if ($denial !== null) {
            return back()->withErrors(['user' => $denial]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
