<?php

namespace Tests\Feature\Admin;

use App\Models\Hydroponics;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        $admin = User::factory()->create();
        $site = Site::create([
            'site_name' => 'S1', 'province' => 'P', 'district' => 'D', 'sector' => 'Sec',
            'village' => 'V', 'googlemap' => 'g', 'manager_name' => 'M', 'contact-details' => 'c',
        ]);
        $cat = Hydroponics::create(['site_id' => $site->id, 'hydroponics_name' => 'Cat', 'description' => null]);
        $this->actingAs($admin)->post(route('admin.products.store'), [
            'site_id' => $site->id,
            'hydroponics_id' => $cat->id,
            'product_name' => 'Tomato',
            'product_type' => 'Veg',
            'quantity' => 10,
            'unit_price' => 1500.50,
        ])->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['product_name' => 'Tomato']);
    }
}
