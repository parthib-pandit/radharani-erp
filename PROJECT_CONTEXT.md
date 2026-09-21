# Radharani Jewellery ERP — Project Context Pack

**Purpose of this file:** paste this whole file (or attach it) as the first message in a new Claude conversation to resume this project with full context, without needing this chat's history. Pair it with the other files listed in "What to bring" below.

---

## What this project is

Internal stock-audit ERP + read-only ecommerce catalog for **Radharani Jewellery Works**, a jewellery shop. The core problem is tamper-evident tracking of physical stock (vault, counter, karigars, hallmarking, customers) — not primarily an online store.

## Stack (final, confirmed)

- Laravel 13, PHP 8.4
- Blade + Livewire (no separate frontend framework)
- MySQL 8+
- Database-driven queue (no Redis available)
- Local disk storage, WebP-compressed photos (no S3 available)
- Hosting: Hostinger shared plan — SSH + cron + phpMyAdmin, no VPS, 20GB storage
- Auth: Laravel Breeze (blade stack)
- `spatie/laravel-permission`, `spatie/laravel-activitylog`, `intervention/image`
- Git repo: `https://github.com/pilgrimsage/radharani-erp.git`

## What's been decided and why (read `DEVELOPER_GUIDE.md` for full reasoning)

- Price is **never stored** on an item — always computed live from `rate_logs` + `discount_rules`, so a rate change reprices everything instantly.
- `movements`, `sales`, `purchases` rows are **never updated or deleted** — corrections are new rows referencing the original, approved by an owner. This plus full activity logging is what "no staff tampering" actually means.
- Staff (`users`) and customers (`customers`) are **two entirely separate auth systems** (different guards) — never merge them.
- `loyalty_settings` is a single-row, owner-editable config table — points-per-rupee and referral bonus are tunable without a deploy.
- Everything is designed to swap infrastructure later with a config change, not a rebuild (`local`→`s3`, `database`→`redis` queue driver).

## Client-approved wireframes — the design source of truth

13 static HTML screens were approved by the client and converted to Blade views in `resources/views/wireframes/`. **Every future module must convert its matching wireframe, not invent new markup.** Mapping table is in `README.md`. Two screens have no wireframe yet (Sales/billing, Audit log) — flag this to the client before designing those.

## Build status (as of pausing here)

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

## Environment setup traps hit so far (all fixed, but easy to reintroduce on a fresh clone)

1. `bootstrap/app.php` needs Spatie's middleware aliases manually added (Laravel 11+ removed `Kernel.php`) — see `BOOTSTRAP_APP_ADDITIONS.md`
2. `config/auth.php` needs the `customer` guard/provider/passwords block — see `config/auth-additions.md`
3. `composer require laravel/breeze` alone does nothing — must also run `php artisan breeze:install blade`
4. Portal Livewire components must use `->layout('components.layouts.guest')`, never Breeze's default — that default assumes a staff login and crashes on any guest/customer page
5. The `jobs`/`job_batches`/`failed_jobs` tables aren't part of any business migration — easy to forget, breaks the queue silently until first dispatch

## Non-negotiable rules for anyone (human or Claude) extending this

1. Never `UPDATE`/`DELETE` a `movements`, `sales`, or `purchases` row — corrections are new, approved rows.
2. Never store a calculated price except `sale_items.price_at_sale` (a deliberate snapshot).
3. Every write needs a real `user_id` — no anonymous/shared-login actions.
4. Photos go through `PhotoCompressionService` — never save an upload directly.
5. New slow work goes through the queue, never the request cycle.
6. New modules convert the matching wireframe — see mapping table in `README.md`.
7. Staff and customer auth are never merged.

## What to bring into a new conversation

Attach/paste, in this order:
1. This file (`PROJECT_CONTEXT.md`)
2. `DEVELOPER_GUIDE.md` — full reasoning per schema decision
3. `SCHEMA_REFERENCE.md` — exact columns/relationships, all 23 tables + 2 ER diagram images
4. `README.md` — setup, git workflow, deploy steps, wireframe mapping table
5. The scaffold zip (or just point Claude at the actual repo if it has repo access) — actual code for everything marked ✅ above
6. The `radharani-erp-skill` folder (see below) — optional, but keeps a fresh Claude session automatically following the non-negotiable rules without you having to repeat them

## Recommended next step when resuming

Say: *"Continue building the Movements module — convert `karigar-dispatch.blade.php`, `karigar-return.blade.php`, `external-movement.blade.php`, `move-stock.blade.php`, `scan-stock.blade.php` into live Livewire components, following the conventions in DEVELOPER_GUIDE.md."*
