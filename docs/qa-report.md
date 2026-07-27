# QA Report — Filament → Blade/Livewire Admin

**Date:** 2026-07-27  
**Environment:** Local MySQL `ukcrw_ukc` (non-production copy) + PHPUnit SQLite `:memory:`  
**Scope:** CRUD, validation, relationships, auth, actions, KPIs/filters/charts after Filament removal and UI rebuild  
**Baseline:** `docs/filament-inventory.md` + `docs/migration/*.md`

## Summary matrix

| Module | CRUD | Validation | Relationships | Auth | Actions | KPIs / Filters / Charts |
|--------|------|------------|---------------|------|---------|-------------------------|
| Users | ✅ | ✅ | ✅ (n/a) | ✅ | ✅ | ✅ |
| Sites | ✅ | ✅ | ✅ (DB cascade) | ✅ | ✅ | ✅ |
| Suppliers | ✅ | ✅ | ✅ (DB cascade) | ✅ | ✅ | ✅ |
| Products | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| ProductSuppliers | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Hydroponics | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| GrowthLogs | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Clients | ✅ | ✅ | ✅ (DB cascade → sales) | ✅ | ✅ | ⚠️ |
| Visitors | ✅ | ✅ | ✅ (n/a) | ✅ | ✅ | ✅ |
| StaffMembers | ✅ | ✅ | ✅ (n/a) | ✅ | ✅ | ✅ |
| Sales | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ (trend fixed) |
| Dashboard | ✅ | n/a | n/a | ✅ | n/a | ⚠️ |

**Legend:** ✅ pass · ⚠️ pass with noted caveats · ❌ fail

**Automated suite:** `php artisan test` — **33 passed** (174 assertions), including new `AdminCrudMatrixTest`.  
**Pint:** clean after one import-order fix in the new test file.

---

## Cross-cutting results

### Auth
- Guests hitting `/admin/*` redirect to `/admin/login` (302).
- Any authenticated user can access all modules — matches Filament inventory (**no Policies / Spatie roles / `canAccessPanel`**).
- No role matrix to re-test beyond authenticated vs guest.

### Actions / bulk actions
- Filament inventory: only View / Edit / Delete / Create — **no custom bulk or row actions**.
- Livewire tables expose Delete with `wire:confirm`; controller destroy routes still work.
- No Filament actions were lost.

### Inventory intentional deviations (not regressions)
From `docs/filament-inventory.md`:
1. User email uniqueness (create + update ignore-self)
2. Password optional on user update
3. Integer quantity fields
4. `sale_date` required
5. `day_number` 1–16
6. `custom_role` dropped
7. Broken Filament chart widgets omitted (dashboard rebuilt separately)
8. Site slug `sites`

### Live MySQL KPI spot-checks (all-time, unfiltered)

| Module | KPI | KPI value | DB query | Match |
|--------|-----|-----------|----------|-------|
| Sales | Sales | 30 | `COUNT(sales)` | ✅ |
| Sales | Revenue | 3,404,110 | `SUM(total_price)` | ✅ |
| Sales | Sales (2025 filter) | 20 | date-filtered count | ✅ |
| Products | Products | 4 | `COUNT(products)` | ✅ |
| Sites | Sites | 3 | `COUNT(sites)` | ✅ |
| Visitors | Visitors | 3 | `COUNT(visitors)` | ✅ |
| Clients | Clients | 509 | `COUNT(clients)` | ✅ |
| Staff | Staff | 9 | `COUNT(staff_members)` | ✅ |
| Hydroponics | Categories | 4 | `COUNT(hydroponics)` | ✅ |
| Users | Users | 3 | `COUNT(users)` | ✅ |
| Suppliers / ProductSuppliers / GrowthLogs | totals | 0 | empty tables | ✅ |

Dashboard empty-state: custom range `2099-01-01`–`2099-12-31` → `hasSalesChartData` / `hasSiteChartData` false (empty message, not a blank broken chart).

### HTTP smoke (authenticated)
All module index / create / edit / show routes returned **200** (where records exist). Empty modules (Suppliers, ProductSuppliers, GrowthLogs) still load index + create.

---

## Detailed bug list

### Fixed in this pass

#### BUG-01 — Sales revenue trend misleading on “All time”
- **Module / page:** Sales index KPI card “Revenue”
- **Severity:** Major
- **Steps:** Open `/admin/sales` with default period “All time”
- **Expected:** No prior-period comparison (or neutral message)
- **Actual:** Trend showed **“New vs prior period”** because previous bounds are null and previous revenue was treated as `0`
- **Fix:** `WithDateRange::formatTrend(..., $comparable)` + `SalesTable` only compares when prior bounds exist; otherwise `trend = null`
- **Files:** `app/Livewire/Admin/Concerns/WithDateRange.php`, `app/Livewire/Admin/SalesTable.php`

#### BUG-02 — Date preset change did not reset pagination
- **Module / page:** All Livewire index tables using `WithDateRange`
- **Severity:** Major
- **Steps:** Go to page 2 of a list → change Period preset / dates so fewer rows remain
- **Expected:** Return to page 1
- **Actual:** Could land on an empty page (only Sales reset page on From/To `updating*`, not on preset)
- **Fix:** `updatedPreset` / `updatedDateFrom` / `updatedDateTo` call `resetPage()` when available
- **Files:** `app/Livewire/Admin/Concerns/WithDateRange.php`

#### BUG-03 — Validation errors only in page banner (no field-level messages)
- **Module / page:** All create/edit forms
- **Severity:** Major (Filament showed field errors; inventory QA criteria require field adjacency)
- **Steps:** Submit a create form with empty required fields
- **Expected:** Errors next to the relevant fields
- **Actual:** Only the layout banner listed errors
- **Fix:** Added `<x-admin.field-error>` on all admin create/edit forms
- **Files:** `resources/views/components/admin/field-error.blade.php`, all `resources/views/admin/**/{create,edit}.blade.php`

### Open / not fixed (deliberate)

#### OPEN-01 — Dashboard Sites / Products / Clients / low-stock ignore date filter
- **Module / page:** Dashboard
- **Severity:** Minor / product decision
- **Expected (strict reading of UI):** Period filter applies to everything
- **Actual:** Period applies to sales / visitors / growth logs / charts; inventory-style counts are global snapshots
- **Why not fixed:** Inventory entities are point-in-time; filtering “products created in range” is a product choice, not a documented Filament behavior. Labels already mix scopes (e.g. Clients + “visitors in range”). **Needs product decision** before changing.

#### OPEN-02 — Search does not affect KPI cards
- **Module / page:** All module indexes
- **Severity:** Minor
- **Expected (strict):** KPIs match visible filtered table including search
- **Actual:** KPIs use dimension + date filters only; search only narrows the table
- **Why not fixed:** Common dashboard pattern; changing it would alter KPI semantics. Documented for awareness.

#### OPEN-03 — Clients “With sales” / Suppliers “Linked to products” ignore date on related rows
- **Severity:** Minor
- **Actual:** Counts distinct related records for clients/suppliers in the filtered set, but related sales/supplies are not date-scoped
- **Why not fixed:** Ambiguous metric definition; not a Filament regression (KPIs are new).

#### OPEN-04 — Sales do not decrement product stock
- **Severity:** Info (confirmed expected)
- **Actual:** Creating a sale leaves `products.quantity` unchanged
- **Why not fixed:** Filament inventory: **no lifecycle hooks**; no stock mutation existed. Verified by test.

#### OPEN-05 — Site / Product / Client / Supplier delete cascades via DB
- **Severity:** Info
- **Actual:** Hard delete; MySQL `cascadeOnDelete` removes children (e.g. Site → Hydroponics → Products → Sales/GrowthLogs)
- **Why not fixed:** Matches schema / Filament hard-delete behavior. **No soft deletes.** Operators should treat Site delete as destructive.

#### OPEN-06 — Visitor / StaffMember GET show returns 405 (not 404)
- **Severity:** Minor
- **Actual:** Show routes intentionally excluded; GET `/admin/visitors/{id}` → 405 Method Not Allowed
- **Why not fixed:** Correct for Laravel resource `except(['show'])`; not user-facing (no View links). Optional polish: custom 404 route.

#### OPEN-07 — Staff `role` not constrained by `Rule::in` server-side
- **Severity:** Minor
- **Actual:** UI select lists known roles; FormRequest only `required|string|max:255` (matches inventory “in options or free”)
- **Why not fixed:** Inventory allowed free text; not a migration regression.

#### OPEN-08 — Visitor `address` max 20 / Staff `site_name` max 20
- **Severity:** Info
- **Actual:** Short max lengths enforced as in Filament
- **Why not fixed:** Preserved intentionally; may feel tight for real addresses — product decision to widen later.

#### OPEN-09 — Compact create forms place some `<x-admin.field-error>` outside the field `<div>`
- **Severity:** Minor (cosmetic)
- **Actual:** Error still renders next to the control; markup nesting is imperfect on a few compacted Blade files
- **Why not fixed:** Functional; tidy markup can be a follow-up.

---

## Bugs fixed (file list)

| Bug | Files changed |
|-----|----------------|
| BUG-01 Sales all-time trend | `app/Livewire/Admin/Concerns/WithDateRange.php`, `app/Livewire/Admin/SalesTable.php` |
| BUG-02 Date filter pagination | `app/Livewire/Admin/Concerns/WithDateRange.php` |
| BUG-03 Field-level validation UI | `resources/views/components/admin/field-error.blade.php`, 22 create/edit views under `resources/views/admin/` |
| Coverage | `tests/Feature/Admin/AdminCrudMatrixTest.php` |

---

## Module notes (order tested)

1. **Users** — CRUD, unique email, update ignore-self, optional password, delete ✅  
2. **Sites** — all Filament fields incl. `contact-details`, update preserves fields, cascade delete verified in tests ✅  
3. **Suppliers** — CRUD/validation ✅ (live DB empty but forms/indexes load)  
4. **Products** — FK selects, integer qty validation, KPIs ✅  
5. **ProductSuppliers** — pivot fields only (qty + date; no price/lead-time in schema/inventory) ✅  
6. **Hydroponics** — route binding `$hydroponic` works; show/edit ✅  
7. **GrowthLogs** — day 1–16 enforced; empty live table OK ✅  
8. **Clients** — CRUD ✅; KPI caveat OPEN-03 ⚠️  
9. **Visitors** — no show (intentional); email/address validation ✅  
10. **StaffMembers** — no show; role select + site_name max 20 ✅  
11. **Sales** — required `sale_date`, no stock side-effects, KPI/filter/trend ✅ after fixes  

---

## What was not available / constraints

- **Roles:** none beyond `auth` middleware (per inventory).
- **Bulk actions:** none existed under Filament.
- **Relation managers:** none.
- **Browser console JS:** not instrumented in a real browser session this pass; Chart.js empty states verified via Livewire flags + blade conditionals. Recommend a quick manual DevTools pass on Dashboard + Sales.
- **Mobile viewport:** layout uses responsive grids / mobile nav chips; not pixel-verified in a device lab this pass — flag for visual spot-check on Sites, Sales, Products.
- **Destructive live MySQL CRUD:** mutation matrix run via PHPUnit (SQLite) + authenticated GET smoke on MySQL; KPI Livewire checks against MySQL. No permanent QA rows left in `ukcrw_ukc`.

---

## Recommended follow-ups (for you)

1. Decide whether Dashboard inventory KPIs should be period-scoped or labeled “All-time inventory”.
2. Decide whether search should shrink KPIs.
3. Optional: confirm Site delete UX warning copy (cascade is silent beyond `wire:confirm`).
4. Manual mobile + browser-console pass on 3 modules.
