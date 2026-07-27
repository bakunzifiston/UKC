<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_visitor(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->post(route('admin.visitors.store'), [
            'name' => 'Guest',
            'email' => 'g@example.com',
            'phone' => '078',
            'address' => 'Kigali',
            'visit_purpose' => 'Tour',
        ])->assertRedirect(route('admin.visitors.index'));
        $this->assertDatabaseHas('visitors', ['name' => 'Guest']);
    }
}
