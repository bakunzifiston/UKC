<?php

namespace Tests\Feature\Admin;

use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\Site;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSupplierAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_supplier(): void
    {
        $admin = User::factory()->create();
        $site = Site::create([
            'site_name' => 'S1', 'province' => 'P', 'district' => 'D', 'sector' => 'Sec',
            'village' => 'V', 'googlemap' => 'g', 'manager_name' => 'M', 'contact-details' => 'c',
        ]);
        $cat = Hydroponics::create(['site_id' => $site->id, 'hydroponics_name' => 'Cat', 'description' => null]);
        $product = Product::create([
            'site_id' => $site->id, 'hydroponics_id' => $cat->id,
            'product_name' => 'Tomato', 'product_type' => 'Veg', 'quantity' => 5, 'unit_price' => 100,
        ]);
        $supplier = Supplier::create(['supplier_name' => 'Acme', 'contact_info' => '078', 'address' => null]);
        $this->actingAs($admin)->post(route('admin.product-suppliers.store'), [
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'supplied_quantity' => 3,
            'supplied_date' => '2025-01-15',
        ])->assertRedirect(route('admin.product-suppliers.index'));
        $this->assertDatabaseHas('product_suppliers', ['supplied_quantity' => 3]);
    }
}
