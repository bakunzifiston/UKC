<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\AccessCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_open_access_and_system_pages(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))->assertOk()
            ->assertSee('Roles');

        $this->actingAs($admin)->get(route('admin.roles.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.permissions.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.system.index'))->assertOk()->assertSee('System configuration');
        $this->actingAs($admin)->get(route('admin.sites.index'))->assertOk();
    }

    public function test_user_without_permission_is_forbidden(): void
    {
        $user = User::factory()->create();
        $user->roles()->detach();

        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_role_permissions_grant_module_access(): void
    {
        $user = User::factory()->create();
        $user->roles()->detach();

        $role = Role::query()->create([
            'name' => 'Site clerk',
            'slug' => 'site-clerk',
            'is_system' => false,
        ]);
        $role->permissions()->sync(
            Permission::query()->whereIn('slug', ['sites.view', 'dashboard.view'])->pluck('id')
        );
        $user->roles()->attach($role);

        $this->actingAs($user->fresh())->get(route('admin.sites.index'))->assertOk();
        $this->actingAs($user->fresh())->get(route('admin.sales.index'))->assertForbidden();
    }

    public function test_deactivated_user_cannot_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => 'password',
            'is_active' => false,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_super_admin_cannot_delete_self_or_the_last_active_super_admin(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('user');

        $this->assertModelExists($admin);
    }

    public function test_password_can_be_reset_and_role_permissions_are_visible(): void
    {
        $admin = User::factory()->create();
        $roleId = Role::query()->where('slug', AccessCatalog::ADMINISTRATOR)->value('id');

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Clerk',
            'email' => 'clerk@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'roles' => [$roleId],
        ])->assertRedirect();

        $clerk = User::query()->where('email', 'clerk@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.users.password', $clerk), [
                'password' => 'new-secret',
                'password_confirmation' => 'new-secret',
            ])
            ->assertRedirect(route('admin.users.show', $clerk));

        $this->actingAs($admin)
            ->get(route('admin.users.show', $clerk))
            ->assertOk()
            ->assertSee('View sites')
            ->assertSee('Administrator');

        $this->post(route('admin.logout'));

        $this->post(route('admin.login.store'), [
            'email' => 'clerk@example.com',
            'password' => 'new-secret',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($clerk);
    }

    public function test_system_role_and_permission_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        $role = Role::query()->where('slug', AccessCatalog::SUPER_ADMIN)->firstOrFail();
        $permission = Permission::query()->where('slug', 'users.view')->firstOrFail();

        $this->actingAs($admin)->delete(route('admin.roles.destroy', $role))->assertSessionHasErrors('role');
        $this->actingAs($admin)->delete(route('admin.permissions.destroy', $permission))->assertSessionHasErrors('permission');
        $this->assertModelExists($role);
        $this->assertModelExists($permission);
    }

    public function test_custom_role_can_be_created_with_permissions(): void
    {
        $admin = User::factory()->create();
        $permissionId = Permission::query()->where('slug', 'clients.view')->value('id');

        $this->actingAs($admin)->post(route('admin.roles.store'), [
            'name' => 'Client reader',
            'description' => 'Reads clients',
            'permissions' => [$permissionId],
        ])->assertRedirect(route('admin.roles.index'));

        $role = Role::query()->where('slug', 'client-reader')->firstOrFail();
        $this->assertTrue($role->permissions()->where('slug', 'clients.view')->exists());
    }
}
