# Sites migration log

## Ported from
`SiteResource`

## Validation
All fields required|string|max:255: site_name, province, district, sector, village, googlemap, manager_name, contact-details.

## Intentional changes
- Route slug normalized to `sites` (Filament used `UKC Sites`).