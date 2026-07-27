<?php

namespace Tests\Feature\Admin;

use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GrowthLogAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_growth_log(): void
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
        $this->actingAs($admin)->post(route('admin.growth-logs.store'), [
            'site_id' => $site->id,
            'hydroponics_id' => $cat->id,
            'product_id' => $product->id,
            'day_number' => 3,
            'growth_value' => 12.5,
            'log_date' => '2025-02-01',
        ])->assertRedirect(route('admin.growth-logs.index'));
        $this->assertDatabaseHas('growth_logs', ['day_number' => 3]);
    }
}
