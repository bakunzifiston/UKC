# Filament removal summary

## Removed
- Package: `filament/filament` (and transitive Filament plugins)
- Directory: `app/Filament/` (all Resources, Pages, Widgets)
- Provider: `app/Providers/Filament/AdminPanelProvider.php`
- Registration in `bootstrap/providers.php`
- Composer script `filament:upgrade`

## Added
- Direct dependency: `livewire/livewire` ^3.5
- Plain Laravel admin at `/admin` (auth login, dashboard, 11 resource CRUDs)
- Docs: `docs/filament-inventory.md`, `docs/migration/*.md`

## Verification
- `php artisan test` — 18 passed
- `vendor/bin/pint --dirty` — clean
- No Filament panel in `php artisan about`
