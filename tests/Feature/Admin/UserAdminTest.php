<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use App\Support\AccessCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_users_index(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_create_user(): void
    {
        $admin = User::factory()->create();
        $roleId = Role::query()->where('slug', AccessCatalog::ADMINISTRATOR)->value('id');

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'is_active' => '1',
                'roles' => [$roleId],
            ])
            ->assertRedirect(route('admin.users.index'));

        $created = User::query()->where('email', 'new@example.com')->first();
        $this->assertNotNull($created);
        $this->assertTrue($created->roles()->where('slug', AccessCatalog::ADMINISTRATOR)->exists());
        $this->assertFalse($created->isSuperAdmin());
    }

    public function test_user_email_must_be_unique(): void
    {
        $admin = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Dup',
                'email' => 'taken@example.com',
                'password' => 'secret123',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_password_optional_on_update(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(['email' => 'keep@example.com']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'Updated Name',
                'email' => 'keep@example.com',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertSame('Updated Name', $user->fresh()->name);
    }
}
