# Filament Inventory (Phase 0)

Safety net for Filament → plain Laravel admin migration. No schema changes.

## Panel / Auth

| Item | Value |
|------|--------|
| Provider | `app/Providers/Filament/AdminPanelProvider.php` |
| Path | `/admin` |
| Login | Filament `->login()` |
| Auth | Any authenticated user (no Policies, no Spatie roles, no `canAccessPanel`) |
| Colors | primary `#059669`, sidebar `#047857`, success `#10B981`, danger `#DC2626`, warning `#F59E0B`, info `#3B82F6`, gray `#6B7280` |
| Nav groups | Operations and Staff; Product and Sales Management |

## Widgets

| Widget | File | Behavior |
|--------|------|----------|
| StatsAdminOverview | `StatsAdimnOverview.php` (typo filename) | Counts: Sites, Staff, Visitors, Suppliers, Clients, Sales. Revenue sum computed but not shown. |
| ClientAdminChart | `ClientAdminChart.php` | Buggy month/client labeling |
| SupplierAdminChart | `SupplierAdminChart.php` | Buggy product→supplier chart |

**Migration decision:** Port stats overview; omit broken charts (rebuild later if needed).

## Cross-cutting (all resources)

- **RelationManagers:** none
- **Lifecycle hooks:** none (`mutateFormData*`, `afterCreate`, etc.)
- **Policies / canX:** none
- **defaultSort / filters:** none
- **Custom bulk/row actions:** only View, Edit, Delete, Create

## Resources

### User (`UserResource`) — sort 11, group Product and Sales

| Field | Filament rules | FormRequest target |
|-------|----------------|--------------------|
| name | required, maxLength(255) | `required\|string\|max:255` |
| email | email, required, maxLength(255) (no unique in form) | `required\|email\|max:255\|unique:users,email` (**fix:** add unique; DB has unique) |
| email_verified_at | optional DateTimePicker | `nullable\|date` |
| password | password, required, maxLength(255) always | create: `required\|string\|max:255`; update: `nullable\|string\|max:255` (**fix:** optional on edit) |

Table: name, email (searchable); email_verified_at sortable. View page: yes.

### Site (`SiteResource`) — sort 2, Operations

All TextInput required maxLength(255): `site_name`, `province`, `district`, `sector`, `village`, `googlemap`, `manager_name`, `contact-details` (hyphenated).

View page: yes. Slug was `UKC Sites` (unusual) → use `sites`.

### Supplier (`SupplierResource`) — sort 3, Operations

| Field | Rules |
|-------|-------|
| supplier_name | required, max:255 |
| contact_info | required, max:255 |
| address | optional textarea |

View: yes.

### Product (`ProductResource`) — sort 6, Product and Sales

| Field | Filament | FormRequest |
|-------|----------|-------------|
| site_id | BelongsTo required | `required\|exists:sites,id` |
| hydroponics_id | BelongsTo required | `required\|exists:hydroponics,id` |
| product_name | required max:255 | same |
| product_type | required max:255 | same |
| quantity | numeric min 0 step 0.01 | `required\|integer\|min:0` (**fix:** int matches DB) |
| unit_price | numeric min 0 step 0.01 | `required\|numeric\|min:0` |

View: yes. Dead fillable `hydroponics_name` ignored.

### ProductSupplier (`ProductSupplierResource`) — sort 7

| Field | FormRequest |
|-------|-------------|
| product_id | required exists:products,id |
| supplier_id | required exists:suppliers,id |
| supplied_quantity | required integer min:0 (**fix:** int) |
| supplied_date | required date |

View: yes.

### Hydroponics (`HydroponicsResource`) — sort 5, nav label "Product Category"

| Field | FormRequest |
|-------|-------------|
| site_id | required exists:sites,id |
| hydroponics_name | required max:255 |
| description | nullable |

View: yes. Dead fillable `site_name` ignored.

### GrowthLog (`GrowthLogResource`) — sort 10

| Field | FormRequest |
|-------|-------------|
| site_id | required exists:sites,id |
| hydroponics_id | required exists:hydroponics,id |
| product_id | required exists:products,id |
| day_number | required integer; default cycle 1–16 | `required\|integer\|min:1\|max:16` (**fix:** bound to cycle) |
| growth_value | required numeric (label: Number of Trays) | `required\|numeric` |
| log_date | required date | same |

View: yes. No cascading selects.

### Client (`ClientResource`) — sort 8

| Field | FormRequest |
|-------|-------------|
| client_name | required max:255 |
| contact_info | required max:255 |
| address | nullable |

View: yes.

### Visitor (`VisitorResource`) — sort 4, Operations

| Field | FormRequest |
|-------|-------------|
| name | required max:255 |
| email | nullable email max:255 |
| phone | nullable max:20 |
| address | required max:20 |
| visit_purpose | required |

View page: **no** (table had ViewAction without page) → no show route.

### StaffMember (`StaffMemberResource`) — sort 1, Operations

| Field | FormRequest |
|-------|-------------|
| name | required max:255 |
| phone_number | nullable max:20 |
| email | nullable email max:255 |
| role | required; Select options (see below) | `required\|string\|max:255` in options or free |
| custom_role | visible if Other; **not persisted** | **DROPPED** (not in DB) |
| site_name | required max:20 (free text, not FK) | same |

Role options: Site Manager, Production Technicians, Quality Control Officer, Agronomist, Maintenance Technician, Sales and Marketing Officer, Delivery/Logistics Personnel, Administrative and Compliance Officer, Security Personnel, Other.

View page: **no**.

### Sale (`SaleResource`) — sort 9

| Field | FormRequest |
|-------|-------------|
| product_id | required exists:products,id |
| client_id | required exists:clients,id |
| quantity_sold | required integer min:0 (**fix:** int) |
| total_price | required numeric min:0 (manual, no auto-calc) |
| sale_date | Filament optional; DB NOT NULL | `required\|date` (**fix**) |

View: yes.

## Intentional deviations summary

See also per-resource logs in `docs/migration/`.

1. Email uniqueness enforced on users
2. Password optional on user update
3. Integer qty fields match DB
4. sale_date required
5. day_number min/max 1–16
6. custom_role UI dropped
7. Broken chart widgets omitted; stats kept
8. Site slug normalized to `sites`
