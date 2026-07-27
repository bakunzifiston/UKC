<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_site(): void
    {
        $admin = User::factory()->create();

        $payload = [
            'site_name' => 'Kigali Site',
            'province' => 'Kigali',
            'district' => 'Gasabo',
            'sector' => 'Remera',
            'village' => 'Rukiri',
            'googlemap' => 'https://maps.example.com',
            'manager_name' => 'Jane',
            'contact-details' => '0780000000',
        ];

        $this->actingAs($admin)
            ->post(route('admin.sites.store'), $payload)
            ->assertRedirect(route('admin.sites.index'));

        $this->assertDatabaseHas('sites', ['site_name' => 'Kigali Site']);
    }
}
