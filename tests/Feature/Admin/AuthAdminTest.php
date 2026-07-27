<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads(): void
    {
        $this->get(route('admin.login'))->assertOk();
    }

    public function test_user_can_login_and_see_dashboard(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
    }
}
