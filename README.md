# Radharani Jewellery ERP — Scaffold

These files can't be composer-installed in this sandbox (no packagist access here),
so set up the base Laravel project on your own machine, then drop these files in.

## Setup (run on your local machine, not this sandbox)

```bash
composer create-project laravel/laravel radharani-erp
cd radharani-erp

composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
composer require intervention/image
composer require laravel/breeze --dev
php artisan breeze:install blade
```

Then copy this scaffold's folders into the new project, overwriting where prompted:
- `database/migrations/*` → merge with existing (keep Laravel's default `2014_10_12_*` files removed — this scaffold replaces the default users table)
- `app/Models/*` → copy in full
- `app/Services/*` → copy in full
- `app/Console/Commands/*` → copy in full
- `routes/console.php` → replace

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan storage:link
php artisan migrate
```

## .env additions needed

```
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
SERVICES_RATE_API_URL=https://your-rate-provider/endpoint
```

## Server cron (Hostinger panel — one line only)

```
* * * * * php /home/USERNAME/domains/YOURDOMAIN/public_html/artisan schedule:run >> /dev/null 2>&1
```

## What's included in this scaffold

- 21 migrations covering stock hierarchy, movements, sales, purchases, accounting ledger, customers, loyalty, installments, QR
- Models with relationships wired (Item ↔ Packet ↔ Box, Movement polymorphic, Sale ↔ Items pivot with frozen price)
- `PricingService` — live price calculation, never stored
- `PhotoCompressionService` — WebP compression on upload
- `RateFetchService` — daily rate API pull with manual-entry fallback
- 3 scheduled commands: photo cleanup, rate fetch, disk usage alert

## Stock module (built, styled)

- `app/Livewire/Stock/BoxManager.php`, `PacketManager.php`, `ItemManager.php`
- Views: `resources/views/livewire/stock/*.blade.php` — restyled to match the client wireframes (Newsreader/Public Sans fonts, tan accent `#A9772F`, cream background `#FAF8F4`)
- Shared layout: `resources/views/components/layouts/app.blade.php` — sidebar nav, used via `<x-layouts.app>`
- Routes: `routes/stock.php` — add `require __DIR__.'/stock.php';` to the end of `routes/web.php`
- No delete on Box/Packet — deletion would break movement history. Deactivate later if ever needed, don't remove.
- Item auto-generates a 7-char `internal_code` when no HUID is given; pair items (earrings/bangles) share `pair_group_id`.

Visit `/stock/boxes`, `/stock/packets`, `/stock/items` once routes are wired and you're logged in.

## Static wireframes (view-only, converted from client HTMLs)

- `resources/views/wireframes/*.blade.php` — all 13 original wireframe screens, converted to Blade views as-is
- No functionality added — pure static HTML/CSS/JS exactly as designed, just served through Laravel routes
- Internal links between screens (e.g. `index.html` → `Main.html`) rewritten to use `route()` so navigation works inside the app
- Routes: `routes/wireframes.php` — add `require __DIR__.'/wireframes.php';` to the end of `routes/web.php`

Visit `/wireframes` to browse all screens exactly as shown to the client.

**Next step when ready:** wire each wireframe's markup/CSS into the matching real Livewire component (Stock module already does this for the styling — Inventory/ItemDetail/Movements screens still need their static HTML turned into live views backed by real data).

## Roles / Users / Employees module (built)

- `app/Livewire/Admin/EmployeeManager.php`, `UserManager.php`, `RoleManager.php`
- Views: `resources/views/livewire/admin/*.blade.php`
- Seeder: `database/seeders/RolePermissionSeeder.php` — run with `php artisan db:seed --class=RolePermissionSeeder`
- Default roles seeded: `owner`, `manager`, `accountant`, `counter_staff`, `karigar_handler` — each with a scoped permission set
- Routes: `routes/admin.php` — add `require __DIR__.'/admin.php';` to `routes/web.php`, gated per-route by `permission:` middleware
- Sidebar now shows Admin links only to users with the matching permission (`@can('employee.manage')` etc.)
- **Never delete** a User or Employee — deactivate only (`is_active` / `status` flags). Every movement/sale references `user_id`; deleting would break attribution.

**Still needed before this is production-ready:**
- Login must check `is_active` and reject disabled users — add this check to your auth guard/login listener (not yet wired, since Breeze/Fortify specifics depend on which you install)
- First owner account: create manually via `php artisan tinker` after migrating + seeding, then assign the `owner` role

```php
// tinker, one-time setup
$u = App\Models\User::create(['name' => 'Owner', 'email' => 'owner@shop.com', 'password' => bcrypt('changeme'), 'is_active' => true]);
$u->assignRole('owner');
```

## Login is_active enforcement (built)

- `app/Http/Requests/Auth/LoginRequest.php` overrides Breeze's default — copy this over your existing one at the same path
- Blocks login for any user with `is_active = false`, with a clear message, instead of just hiding them from the UI

## Discount rules (built)

- Migration: `2024_01_01_000095_create_discount_rules_table.php`
- Model: `app/Models/Pricing/DiscountRule.php` — precedence: item → packet → box → category → weight tier, first match wins
- `PricingService` now applies the best matching rule automatically; the accountant's manual discount at checkout (`sales.discount`) is separate and stacks on top at Sales-module build time
- No admin UI yet for creating rules — add a `DiscountRuleManager` Livewire component when the Sales module is built (same pattern as Stock/Admin managers)

## Loyalty & Referral (built)

- `app/Services/LoyaltyService.php` — awards points per sale (rate is a single constant, easy to tune), pays referral bonus to the referrer on the referred customer's **first confirmed sale** (not at signup, so it can't be gamed)
- Call `LoyaltyService::awardForSale($sale)` once a sale is confirmed — wire this in when the Sales module is built
- `loyalty_transactions` is the source of truth; `customers.loyalty_points` is just a cached total for fast display

## Customer Portal (built)

Customers log in with **phone + password**, on a completely separate `customer` auth guard — never mixed with staff `users`/`web` guard.

- Migration: `2024_01_01_000096_add_auth_fields_to_customers_table.php` — adds `password`/`remember_token` to `customers`
- Model: `Customer` now implements `Authenticatable`
- **Config step required:** merge `config/auth-additions.md` into your real `config/auth.php` — adds the `customer` guard + `customers` provider
- `app/Livewire/Portal/CustomerLogin.php`, `CustomerDashboard.php` — dashboard has 3 tabs: Past Purchases, Loyalty & Referral (shows their referral code), Installments (scheme + payment history)
- Routes: `routes/portal.php` — add `require __DIR__.'/portal.php';` to `routes/web.php`
- Visit `/portal/login`

**How a customer gets portal access:** staff sets a password for them manually (no self-signup flow built yet — add a "Set Portal Password" action to a future Customer management screen if self-signup isn't wanted).

## Loyalty settings + referral overview (built)

- `app/Livewire/Admin/LoyaltySettingsManager.php` — owner-editable points-per-rupee, referral bonus, redemption minimum, point value in ₹. Backed by single-row `loyalty_settings` table, not hardcoded constants.
- `app/Livewire/Admin/ReferralOverview.php` — read-only report: who referred whom, whether each referral's bonus has actually been earned yet
- `LoyaltyService` now reads rates from `LoyaltySetting::current()` instead of class constants
- Customer portal's Loyalty tab now also lists people *they've* referred, with bonus-earned status
- Routes added to `routes/admin.php`, sidebar links gated by `loyalty.manage` permission (added to seeder)

## Not included yet (next steps)

**Rule for every module below: convert the matching static wireframe from `resources/views/wireframes/` — don't design new markup from scratch.** The wireframes are the client-approved layout; live modules should reuse their HTML/CSS structure and wire real Livewire data into it, the same way the Stock module's views were restyled from the wireframe look rather than invented fresh. Delete the static wireframe view once its live replacement covers the same screen, so there's never two competing versions of one screen.

| Module to build | Wireframe(s) to convert |
|---|---|
| Movements | `karigar-dispatch.blade.php`, `karigar-return.blade.php`, `external-movement.blade.php`, `move-stock.blade.php`, `scan-stock.blade.php`, `box-packet.blade.php` |
| Sales module | *(no wireframe provided yet — ask the client for a billing screen mockup before building, or extend `item-detail.blade.php`'s layout if that's the intended base)* |
| Main dashboard (+ price history chart) | `main.blade.php` |
| Item Detail (live) | `item-detail.blade.php` |
| Inventory (live) — already superseded by `ItemManager`, but check it visually matches | `inventory.blade.php` |
| History | `history.blade.php` |
| Logbook | `logbook.blade.php` |
| Location Report | `location-report.blade.php` |
| Audit log viewer | *(no wireframe — new screen, style to match the rest via `components/layouts/app.blade.php`)* |

- `DiscountRuleManager` admin screen (schema + logic done, no UI yet — no wireframe either, style via the shared layout)
- Customer self-signup for the portal, if wanted (currently staff-assigned password only)
- GST invoice PDF template
- Tally export service
