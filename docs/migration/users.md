# Users migration log

## Ported from
`UserResource` + Create/Edit/View/List pages.

## Validation
| Field | Filament | New |
|-------|----------|-----|
| name | required max 255 | same |
| email | required email max 255 | + `unique:users,email` (ignore on update) |
| email_verified_at | optional | nullable date |
| password | always required | required on create; **nullable on update** |

## Intentional changes
- Enforce email uniqueness (DB already unique; Filament omitted it).
- Password optional on edit (leave blank to keep).

## Not ported
- N/A (no relation managers / custom actions / hooks).
