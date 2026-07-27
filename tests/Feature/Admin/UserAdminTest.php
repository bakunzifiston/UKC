<?php

namespace Tests\Feature\Admin;

use App\Models\User;
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

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => 'secret123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'name' => 'New User',
        ]);
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
