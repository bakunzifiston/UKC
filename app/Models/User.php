<?php

namespace App\Models;

use App\Support\AccessCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    private ?bool $superAdminCache = null;

    /** @var list<string>|null */
    private ?array $permissionCache = null;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->superAdminCache ??= $this->roles()
            ->where('roles.slug', AccessCatalog::SUPER_ADMIN)
            ->exists();
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($slug, $this->permissionSlugs(), true);
    }

    /**
     * Permissions granted by roles. Super Admin receives every permission.
     *
     * @return Collection<int, Permission>
     */
    public function effectivePermissions(): Collection
    {
        if ($this->isSuperAdmin()) {
            return Permission::query()->orderBy('module')->orderBy('name')->get();
        }

        return Permission::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('roles.id', $this->roles()->select('roles.id')))
            ->orderBy('module')
            ->orderBy('name')
            ->get();
    }

    /**
     * @return list<string>
     */
    private function permissionSlugs(): array
    {
        if ($this->permissionCache !== null) {
            return $this->permissionCache;
        }

        $this->loadMissing('roles.permissions');

        return $this->permissionCache = $this->roles
            ->flatMap(fn (Role $role) => $role->permissions->pluck('slug'))
            ->unique()
            ->values()
            ->all();
    }
}
