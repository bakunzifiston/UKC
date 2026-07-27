<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ClientsTable;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\GrowthLogsTable;
use App\Livewire\Admin\HydroponicsTable;
use App\Livewire\Admin\ProductsTable;
use App\Livewire\Admin\ProductSuppliersTable;
use App\Livewire\Admin\SalesTable;
use App\Livewire\Admin\SitesTable;
use App\Livewire\Admin\StaffMembersTable;
use App\Livewire\Admin\SuppliersTable;
use App\Livewire\Admin\UsersTable;
use App\Livewire\Admin\VisitorsTable;
use App\Models\Client;
use App\Models\GrowthLog;
use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Sale;
use App\Models\Site;
use App\Models\StaffMember;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCrudMatrixTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Site $site;

    private Hydroponics $category;

    private Product $product;

    private Client $client;

    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->site = Site::create([
            'site_name' => 'Matrix Site',
            'province' => 'Kigali',
            'district' => 'Gasabo',
            'sector' => 'Remera',
            'village' => 'Rukiri',
            'googlemap' => 'map',
            'manager_name' => 'Manager',
            'contact-details' => '0780000000',
        ]);
        $this->category = Hydroponics::create([
            'site_id' => $this->site->id,
            'hydroponics_name' => 'Leafy',
            'description' => 'desc',
        ]);
        $this->product = Product::create([
            'site_id' => $this->site->id,
            'hydroponics_id' => $this->category->id,
            'product_name' => 'Lettuce',
            'product_type' => 'Veg',
            'quantity' => 20,
            'unit_price' => 1000,
        ]);
        $this->client = Client::create([
            'client_name' => 'Buyer Co',
            'contact_info' => '078111',
            'address' => 'Kigali',
        ]);
        $this->supplier = Supplier::create([
            'supplier_name' => 'Agri Supply',
            'contact_info' => '078222',
            'address' => 'Kigali',
        ]);
    }

    public function test_guests_redirected_from_all_module_indexes(): void
    {
        $routes = [
            'admin.dashboard',
            'admin.users.index',
            'admin.sites.index',
            'admin.suppliers.index',
            'admin.products.index',
            'admin.product-suppliers.index',
            'admin.hydroponics.index',
            'admin.growth-logs.index',
            'admin.clients.index',
            'admin.visitors.index',
            'admin.staff-members.index',
            'admin.sales.index',
        ];

        foreach ($routes as $route) {
            $this->get(route($route))->assertRedirect(route('admin.login'));
        }
    }

    public function test_authenticated_indexes_and_forms_load(): void
    {
        $this->actingAs($this->admin);

        $gets = [
            route('admin.dashboard'),
            route('admin.users.index'),
            route('admin.users.create'),
            route('admin.sites.index'),
            route('admin.sites.create'),
            route('admin.suppliers.index'),
            route('admin.suppliers.create'),
            route('admin.products.index'),
            route('admin.products.create'),
            route('admin.product-suppliers.index'),
            route('admin.product-suppliers.create'),
            route('admin.hydroponics.index'),
            route('admin.hydroponics.create'),
            route('admin.growth-logs.index'),
            route('admin.growth-logs.create'),
            route('admin.clients.index'),
            route('admin.clients.create'),
            route('admin.visitors.index'),
            route('admin.visitors.create'),
            route('admin.staff-members.index'),
            route('admin.staff-members.create'),
            route('admin.sales.index'),
            route('admin.sales.create'),
            route('admin.sites.show', $this->site),
            route('admin.sites.edit', $this->site),
            route('admin.products.show', $this->product),
            route('admin.products.edit', $this->product),
            route('admin.hydroponics.show', $this->category),
            route('admin.hydroponics.edit', $this->category),
            route('admin.clients.show', $this->client),
            route('admin.clients.edit', $this->client),
            route('admin.suppliers.show', $this->supplier),
            route('admin.suppliers.edit', $this->supplier),
        ];

        foreach ($gets as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_users_validation_and_unique_ignore_on_update(): void
    {
        $existing = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => '',
                'email' => 'bad',
                'password' => '',
            ])
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->actingAs($this->admin)
            ->put(route('admin.users.update', $existing), [
                'name' => 'Same Email User',
                'email' => 'taken@example.com',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertSame('Same Email User', $existing->fresh()->name);
    }

    public function test_users_delete(): void
    {
        $target = User::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $target))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_sites_update_does_not_wipe_fields(): void
    {
        $this->actingAs($this->admin)
            ->put(route('admin.sites.update', $this->site), [
                'site_name' => 'Matrix Site',
                'province' => 'Kigali',
                'district' => 'Gasabo',
                'sector' => 'Remera',
                'village' => 'Rukiri',
                'googlemap' => 'map',
                'manager_name' => 'New Manager',
                'contact-details' => '0780000000',
            ])
            ->assertRedirect(route('admin.sites.index'));

        $fresh = $this->site->fresh();
        $this->assertSame('New Manager', $fresh->manager_name);
        $this->assertSame('Kigali', $fresh->province);
        $this->assertSame('0780000000', $fresh->{'contact-details'});
    }

    public function test_products_reject_non_integer_quantity(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'site_id' => $this->site->id,
                'hydroponics_id' => $this->category->id,
                'product_name' => 'Bad',
                'product_type' => 'X',
                'quantity' => 'abc',
                'unit_price' => 10,
            ])
            ->assertSessionHasErrors('quantity');
    }

    public function test_product_supplier_create_update_delete(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.product-suppliers.store'), [
                'product_id' => $this->product->id,
                'supplier_id' => $this->supplier->id,
                'supplied_quantity' => 8,
                'supplied_date' => '2025-06-01',
            ])
            ->assertRedirect(route('admin.product-suppliers.index'));

        $row = ProductSupplier::first();
        $this->assertNotNull($row);

        $this->actingAs($this->admin)
            ->put(route('admin.product-suppliers.update', $row), [
                'product_id' => $this->product->id,
                'supplier_id' => $this->supplier->id,
                'supplied_quantity' => 12,
                'supplied_date' => '2025-06-02',
            ])
            ->assertRedirect(route('admin.product-suppliers.index'));

        $this->assertSame(12, (int) $row->fresh()->supplied_quantity);

        $this->actingAs($this->admin)
            ->delete(route('admin.product-suppliers.destroy', $row))
            ->assertRedirect(route('admin.product-suppliers.index'));

        $this->assertDatabaseMissing('product_suppliers', ['id' => $row->id]);
    }

    public function test_growth_log_day_number_bounds_and_create(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.growth-logs.store'), [
                'site_id' => $this->site->id,
                'hydroponics_id' => $this->category->id,
                'product_id' => $this->product->id,
                'day_number' => 17,
                'growth_value' => 2,
                'log_date' => '2025-06-01',
            ])
            ->assertSessionHasErrors('day_number');

        $this->actingAs($this->admin)
            ->post(route('admin.growth-logs.store'), [
                'site_id' => $this->site->id,
                'hydroponics_id' => $this->category->id,
                'product_id' => $this->product->id,
                'day_number' => 4,
                'growth_value' => 3.5,
                'log_date' => '2025-06-01',
            ])
            ->assertRedirect(route('admin.growth-logs.index'));

        $this->assertDatabaseHas('growth_logs', [
            'product_id' => $this->product->id,
            'day_number' => 4,
        ]);
    }

    public function test_visitors_address_max_and_email_validation(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.visitors.store'), [
                'name' => 'V',
                'email' => 'not-email',
                'phone' => null,
                'address' => str_repeat('a', 21),
                'visit_purpose' => 'Tour',
            ])
            ->assertSessionHasErrors(['email', 'address']);

        $this->actingAs($this->admin)
            ->post(route('admin.visitors.store'), [
                'name' => 'Visitor',
                'email' => 'v@example.com',
                'phone' => '078',
                'address' => 'Kigali',
                'visit_purpose' => 'Tour',
            ])
            ->assertRedirect(route('admin.visitors.index'));
    }

    public function test_staff_site_name_max_and_create(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.staff-members.store'), [
                'name' => 'Staff',
                'role' => 'Site Manager',
                'site_name' => str_repeat('x', 21),
            ])
            ->assertSessionHasErrors('site_name');

        $this->actingAs($this->admin)
            ->post(route('admin.staff-members.store'), [
                'name' => 'Staff',
                'phone_number' => '078',
                'email' => 's@example.com',
                'role' => 'Site Manager',
                'site_name' => 'Site A',
            ])
            ->assertRedirect(route('admin.staff-members.index'));

        $this->assertDatabaseHas('staff_members', ['name' => 'Staff', 'site_name' => 'Site A']);
    }

    public function test_sales_require_sale_date_and_do_not_change_stock(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.sales.store'), [
                'product_id' => $this->product->id,
                'client_id' => $this->client->id,
                'quantity_sold' => 2,
                'total_price' => 2000,
            ])
            ->assertSessionHasErrors('sale_date');

        $this->actingAs($this->admin)
            ->post(route('admin.sales.store'), [
                'product_id' => $this->product->id,
                'client_id' => $this->client->id,
                'quantity_sold' => 2,
                'total_price' => 2000,
                'sale_date' => '2025-06-03',
            ])
            ->assertRedirect(route('admin.sales.index'));

        $this->assertSame(20, (int) $this->product->fresh()->quantity);
        $this->assertDatabaseHas('sales', ['quantity_sold' => 2, 'total_price' => 2000]);
    }

    public function test_sales_kpi_matches_filtered_query_and_all_time_trend_is_null(): void
    {
        Sale::create([
            'product_id' => $this->product->id,
            'client_id' => $this->client->id,
            'quantity_sold' => 1,
            'total_price' => 500,
            'sale_date' => '2025-02-01',
        ]);
        Sale::create([
            'product_id' => $this->product->id,
            'client_id' => $this->client->id,
            'quantity_sold' => 1,
            'total_price' => 700,
            'sale_date' => '2024-02-01',
        ]);

        Livewire::actingAs($this->admin)
            ->test(SalesTable::class)
            ->assertSet('preset', 'all')
            ->assertViewHas('kpis', function (array $kpis) {
                $sales = collect($kpis)->firstWhere('label', 'Sales');
                $revenue = collect($kpis)->firstWhere('label', 'Revenue');

                return (int) str_replace(',', '', $sales['value']) === 2
                    && $revenue['trend'] === null;
            })
            ->set('preset', 'custom')
            ->set('dateFrom', '2025-01-01')
            ->set('dateTo', '2025-12-31')
            ->assertViewHas('kpis', function (array $kpis) {
                $sales = collect($kpis)->firstWhere('label', 'Sales');

                return (int) str_replace(',', '', $sales['value']) === 1;
            });
    }

    public function test_module_livewire_tables_render(): void
    {
        $this->actingAs($this->admin);

        foreach ([
            UsersTable::class,
            SitesTable::class,
            SuppliersTable::class,
            ProductsTable::class,
            ProductSuppliersTable::class,
            HydroponicsTable::class,
            GrowthLogsTable::class,
            ClientsTable::class,
            VisitorsTable::class,
            StaffMembersTable::class,
            SalesTable::class,
            Dashboard::class,
        ] as $component) {
            Livewire::test($component)->assertSuccessful();
        }
    }

    public function test_deleting_site_cascades_children(): void
    {
        GrowthLog::create([
            'site_id' => $this->site->id,
            'hydroponics_id' => $this->category->id,
            'product_id' => $this->product->id,
            'day_number' => 1,
            'growth_value' => 1,
            'log_date' => '2025-01-01',
        ]);
        Sale::create([
            'product_id' => $this->product->id,
            'client_id' => $this->client->id,
            'quantity_sold' => 1,
            'total_price' => 100,
            'sale_date' => '2025-01-02',
        ]);

        $siteId = $this->site->id;
        $productId = $this->product->id;
        $categoryId = $this->category->id;

        $this->actingAs($this->admin)
            ->delete(route('admin.sites.destroy', $this->site))
            ->assertRedirect(route('admin.sites.index'));

        $this->assertDatabaseMissing('sites', ['id' => $siteId]);
        $this->assertDatabaseMissing('hydroponics', ['id' => $categoryId]);
        $this->assertDatabaseMissing('products', ['id' => $productId]);
        $this->assertDatabaseMissing('sales', ['product_id' => $productId]);
        $this->assertDatabaseMissing('growth_logs', ['site_id' => $siteId]);
    }

    public function test_visitors_and_staff_have_no_show_route(): void
    {
        $visitor = Visitor::create([
            'name' => 'V',
            'email' => null,
            'phone' => null,
            'address' => 'Kigali',
            'visit_purpose' => 'Tour',
        ]);
        $staff = StaffMember::create([
            'name' => 'S',
            'phone_number' => null,
            'email' => null,
            'role' => 'Site Manager',
            'site_name' => 'Site',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin/visitors/'.$visitor->id)
            ->assertStatus(405);

        $this->actingAs($this->admin)
            ->get('/admin/staff-members/'.$staff->id)
            ->assertStatus(405);
    }
}
