<?php

namespace App\Support;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AccessGuard
{
    public static function assertCanManage(User $actor, User $target): void
    {
        if ($target->isSuperAdmin() && ! $actor->isSuperAdmin()) {
            abort(403);
        }
    }

    /**
     * @param  list<int>  $roleIds
     */
    public static function assertCanAssign(User $actor, array $roleIds): void
    {
        $superAdminId = self::superAdminRoleId();

        if ($superAdminId !== null && in_array($superAdminId, $roleIds, true) && ! $actor->isSuperAdmin()) {
            abort(403);
        }
    }

    /**
     * @param  list<int>|null  $roleIds
     */
    public static function assertSuperAdminRemains(User $target, ?array $roleIds, bool $willBeActive): void
    {
        if (! $target->isSuperAdmin()) {
            return;
        }

        $superAdminId = self::superAdminRoleId();
        $keepsRole = $roleIds === null || ($superAdminId !== null && in_array($superAdminId, $roleIds, true));

        if ($keepsRole && $willBeActive) {
            return;
        }

        $anotherActiveSuperAdmin = User::query()
            ->where('is_active', true)
            ->whereKeyNot($target->id)
            ->whereHas('roles', fn ($query) => $query->where('slug', AccessCatalog::SUPER_ADMIN))
            ->exists();

        if (! $anotherActiveSuperAdmin) {
            throw ValidationException::withMessages([
                'roles' => 'At least one active Super Admin must remain.',
            ]);
        }
    }

    public static function deleteDenial(User $actor, User $target): ?string
    {
        if ($actor->is($target)) {
            return 'You cannot delete your own account.';
        }

        if ($target->isSuperAdmin() && ! $actor->isSuperAdmin()) {
            return 'Only a Super Admin can delete a Super Admin.';
        }

        if ($target->isSuperAdmin() && ! self::anotherActiveSuperAdminExists($target)) {
            return 'At least one active Super Admin must remain.';
        }

        return null;
    }

    public static function anotherActiveSuperAdminExists(User $except): bool
    {
        return User::query()
            ->where('is_active', true)
            ->whereKeyNot($except->id)
            ->whereHas('roles', fn ($query) => $query->where('slug', AccessCatalog::SUPER_ADMIN))
            ->exists();
    }

    public static function superAdminRoleId(): ?int
    {
        $id = Role::query()->where('slug', AccessCatalog::SUPER_ADMIN)->value('id');

        return $id === null ? null : (int) $id;
    }
}
