<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_sale(): void
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
        $client = Client::create(['client_name' => 'Buyer', 'contact_info' => '079', 'address' => null]);
        $this->actingAs($admin)->post(route('admin.sales.store'), [
            'product_id' => $product->id,
            'client_id' => $client->id,
            'quantity_sold' => 2,
            'total_price' => 200,
            'sale_date' => '2025-03-01',
        ])->assertRedirect(route('admin.sales.index'));
        $this->assertDatabaseHas('sales', ['quantity_sold' => 2]);
    }
}
