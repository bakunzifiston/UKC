<?php

namespace Tests\Feature\Admin;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HydroponicsAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_hydroponics(): void
    {
        $admin = User::factory()->create();
        $site = Site::create([
            'site_name' => 'S1', 'province' => 'P', 'district' => 'D', 'sector' => 'Sec',
            'village' => 'V', 'googlemap' => 'g', 'manager_name' => 'M', 'contact-details' => 'c',
        ]);
        $this->actingAs($admin)->post(route('admin.hydroponics.store'), [
            'site_id' => $site->id,
            'hydroponics_name' => 'Lettuce',
            'description' => 'Leafy',
        ])->assertRedirect(route('admin.hydroponics.index'));
        $this->assertDatabaseHas('hydroponics', ['hydroponics_name' => 'Lettuce']);
    }
}
