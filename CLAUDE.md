# Radharani Jewellery ERP

Internal stock-audit ERP + read-only ecommerce catalog for Radharani Jewellery Works. The core problem is tamper-evident tracking of physical stock (vault, counter, karigars, hallmarking, customers) — this is not primarily an online store.

Full reasoning behind every schema decision: @docs/DEVELOPER_GUIDE.md
Exact columns/relationships for all tables + ER diagrams: @docs/SCHEMA_REFERENCE.md

## Non-negotiable rules

1. Never `UPDATE` or `DELETE` a `movements`, `sales`, or `purchases` row — corrections are new rows referencing the original, approved by an owner.
2. Never store a calculated price, except `sale_items.price_at_sale` (a deliberate snapshot at time of sale).
3. Every write needs a real `user_id` — no anonymous or shared-login actions.
4. Photos go through `PhotoCompressionService` — never save an upload directly.
5. New slow/async work goes through the queue (`database` driver) — never the request cycle.
6. New modules convert their matching wireframe in `resources/views/wireframes/` — see the mapping table in `README.md`. Don't invent new markup.
7. Staff (`users`) and customers (`customers`) are two entirely separate auth systems (different guards) — never merge them.

## Stack

- Laravel 13 (PHP 8.4), Blade + Livewire — no separate frontend framework, no API layer
- MySQL 8+, database-driven queue (no Redis), local disk storage with WebP-compressed photos (no S3)
- Hosting: Hostinger shared plan — SSH + cron + phpMyAdmin only, no VPS, 20GB storage
- Auth: Laravel Breeze (blade stack); `spatie/laravel-permission`, `spatie/laravel-activitylog`, `intervention/image`
- Designed to swap infra later via config only (`local`→`s3`, `database`→`redis` queue) — never a rebuild

## Build status

| Module | Status |
|---|---|
| Stock (Box/Packet/Item) | ✅ Live, Livewire, styled |
| Wireframes (all 13) | ✅ Static reference views, routed at `/wireframes` |
| Admin (Employees/Users/Roles/Customers) | ✅ Live |
| Loyalty settings + Referral overview | ✅ Live |
| Customer portal (login, purchases, loyalty, installments) | ✅ Live — own minimal layout, not Breeze's staff one |
| Discount rules (schema + pricing logic) | ✅ Built, no admin UI yet |
| Login `is_active` enforcement | ✅ Built (Breeze `LoginRequest` override — reapply after every `breeze:install`) |
| Public landing page (guest `/`) | ✅ Live — staff/portal login split |
| Queue infrastructure (`jobs` table) | ✅ Fixed — was missing, breaks `queue:work` without it |
| **Movements** (Karigar dispatch/return, external, scan/move) | ⬜ Not built — next in queue |
| Sales / Billing / GST invoice | ⬜ Not built |
| Purchases / Vendors UI | ⬜ Not built |
| Accounting ledger wiring | ⬜ Not built |
| Dashboard, History, Logbook, Location Report | ⬜ Not built (static wireframes only) |
| Audit log viewer | ⬜ Not built |
| Tally export | ⬜ Not built |

Build in this order — each depends on the last (see `docs/DEVELOPER_GUIDE.md` Section 4 for why): **Movements → Sales → Purchases/Vendors → Accounting ledger wiring → Dashboard/History/Logbook/Location Report/Audit log**.

## Environment setup traps (easy to reintroduce on a fresh clone)

1. `bootstrap/app.php` needs Spatie's middleware aliases manually added (Laravel 11+ removed `Kernel.php`) — already applied there; reapply if it goes missing.
2. `config/auth.php` needs the `customer` guard/provider/passwords block — see `config/auth-additions.md`.
3. `composer require laravel/breeze` alone does nothing — must also run `php artisan breeze:install blade`.
4. Portal Livewire components must use `->layout('components.layouts.guest')`, never Breeze's default — that default assumes a staff login and crashes on any guest/customer page.
5. The `jobs`/`job_batches`/`failed_jobs` tables aren't part of any business migration — easy to forget, breaks the queue silently until first dispatch.
