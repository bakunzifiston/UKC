<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffMemberAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_staff_member(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->post(route('admin.staff-members.store'), [
            'name' => 'Alice',
            'phone_number' => '078',
            'email' => 'a@example.com',
            'role' => 'Agronomist',
            'site_name' => 'Kigali',
        ])->assertRedirect(route('admin.staff-members.index'));
        $this->assertDatabaseHas('staff_members', ['name' => 'Alice']);
    }
}
