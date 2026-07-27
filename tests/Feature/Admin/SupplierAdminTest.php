<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_supplier(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.suppliers.store'), [
                'supplier_name' => 'Acme',
                'contact_info' => '078',
                'address' => 'Kigali',
            ])
            ->assertRedirect(route('admin.suppliers.index'));

        $this->assertDatabaseHas('suppliers', ['supplier_name' => 'Acme']);
    }
}
