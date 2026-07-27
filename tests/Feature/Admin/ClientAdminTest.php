<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_client(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->post(route('admin.clients.store'), [
            'client_name' => 'Buyer',
            'contact_info' => '079',
            'address' => null,
        ])->assertRedirect(route('admin.clients.index'));
        $this->assertDatabaseHas('clients', ['client_name' => 'Buyer']);
    }
}
