<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_rwandan_client(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.clients.store'), $this->clientPayload())
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', [
            'client_name' => 'Aline Uwase',
            'first_name' => 'Aline',
            'second_name' => 'Uwase',
            'country' => 'Rwanda',
            'province' => 'Kigali',
            'district' => 'Gasabo',
            'sector' => 'Remera',
            'cell' => 'Rukiri',
            'village' => 'Amahoro',
            'age' => 29,
            'gender' => 'Female',
            'email' => 'aline@example.com',
            'telephone' => '0788001122',
            'contact_info' => '0788001122',
        ]);
    }

    public function test_international_client_keeps_country_only(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.clients.store'), $this->clientPayload([
                'first_name' => 'John',
                'second_name' => 'Kamau',
                'country' => 'Kenya',
                'province' => 'Nairobi',
                'district' => 'Westlands',
                'sector' => 'Parklands',
                'cell' => 'Highridge',
                'village' => 'Spring',
                'email' => null,
                'age' => null,
            ]))
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', [
            'client_name' => 'John Kamau',
            'country' => 'Kenya',
            'province' => null,
            'district' => null,
            'sector' => null,
            'cell' => null,
            'village' => null,
            'email' => null,
            'age' => null,
        ]);
    }

    public function test_rwandan_client_requires_local_location_and_telephone(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.clients.create'))
            ->post(route('admin.clients.store'), $this->clientPayload([
                'province' => '',
                'district' => '',
                'sector' => '',
                'cell' => '',
                'village' => '',
                'telephone' => '',
                'email' => 'not-an-email',
            ]))
            ->assertRedirect(route('admin.clients.create'))
            ->assertSessionHasErrors(['province', 'district', 'sector', 'cell', 'village', 'telephone', 'email']);
    }

    public function test_can_switch_client_to_international_and_clear_local_location(): void
    {
        $admin = User::factory()->create();
        $client = Client::create($this->clientPayload());

        $this->actingAs($admin)
            ->put(route('admin.clients.update', $client), $this->clientPayload([
                'country' => 'Uganda',
                'province' => 'Central',
            ]))
            ->assertRedirect(route('admin.clients.index'));

        $client->refresh();
        $this->assertSame('Uganda', $client->country);
        $this->assertNull($client->province);
        $this->assertNull($client->cell);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function clientPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Aline',
            'second_name' => 'Uwase',
            'country' => 'Rwanda',
            'province' => 'Kigali',
            'district' => 'Gasabo',
            'sector' => 'Remera',
            'cell' => 'Rukiri',
            'village' => 'Amahoro',
            'age' => 29,
            'gender' => 'Female',
            'email' => 'aline@example.com',
            'telephone' => '0788001122',
        ], $overrides);
    }
}
