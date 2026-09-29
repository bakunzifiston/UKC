<?php

namespace Tests\Feature\Admin;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_rwandan_supplier(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.suppliers.store'), $this->supplierPayload())
            ->assertRedirect(route('admin.suppliers.index'));

        $this->assertDatabaseHas('suppliers', [
            'supplier_name' => 'Eric Habimana',
            'first_name' => 'Eric',
            'second_name' => 'Habimana',
            'country' => 'Rwanda',
            'province' => 'Southern',
            'district' => 'Huye',
            'sector' => 'Tumba',
            'cell' => 'Cyarwa',
            'village' => 'Agateme',
            'gender' => 'Male',
            'telephone' => '0781234567',
            'contact_info' => '0781234567',
            'email' => null,
            'age' => null,
        ]);
    }

    public function test_international_supplier_keeps_country_only(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.suppliers.store'), $this->supplierPayload([
                'country' => 'Belgium',
                'province' => 'Brussels',
                'cell' => 'Centre',
            ]))
            ->assertRedirect(route('admin.suppliers.index'));

        $supplier = Supplier::query()->first();
        $this->assertSame('Belgium', $supplier->country);
        $this->assertNull($supplier->province);
        $this->assertNull($supplier->cell);
        $this->assertSame('Eric Habimana', $supplier->supplier_name);
    }

    public function test_supplier_form_requires_gender_and_names(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.suppliers.create'))
            ->post(route('admin.suppliers.store'), $this->supplierPayload([
                'first_name' => '',
                'second_name' => '',
                'gender' => '',
            ]))
            ->assertRedirect(route('admin.suppliers.create'))
            ->assertSessionHasErrors(['first_name', 'second_name', 'gender']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function supplierPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Eric',
            'second_name' => 'Habimana',
            'country' => 'Rwanda',
            'province' => 'Southern',
            'district' => 'Huye',
            'sector' => 'Tumba',
            'cell' => 'Cyarwa',
            'village' => 'Agateme',
            'age' => null,
            'gender' => 'Male',
            'email' => null,
            'telephone' => '0781234567',
        ], $overrides);
    }
}
