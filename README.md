# Radharani Jewellery ERP

Internal stock-management ERP + read-only ecommerce catalog for a jewellery shop. See `docs/DEVELOPER_GUIDE.md` for system design, schema, and reasoning before making structural changes; `docs/SCHEMA_REFERENCE.md` for exact columns; `docs/REQUIREMENTS.md` for the confirmed client requirements this build implements; `docs/DESIGN_SYSTEM.md` for the UI tokens/components; `CLAUDE.md` for the non-negotiable rules and known traps (read this one first).

## Stack

Laravel 13 (PHP 8.4) · Blade + Livewire · Tailwind CSS · MySQL 8+ · Database-driven queue · Local disk (WebP) storage · Hostinger shared hosting (SSH + cron)

## First-Time Setup (local)

```bash
git clone https://github.com/pilgrimsage/radharani-erp.git
cd radharani-erp

composer install
npm install

cp .env.example .env
php artisan key:generate
```

**Edit `.env`:**
```
DB_CONNECTION=mysql
DB_DATABASE=radharani_erp
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
SERVICES_RATE_API_URL=
```

**Four setup steps no file automates** (see `CLAUDE.md`'s "Environment setup traps" for the why on each):
1. Spatie's middleware aliases in `bootstrap/app.php` (already applied — reapply if it goes missing).
2. The `customer` guard/provider/passwords block in `config/auth.php` — see `config/auth-additions.md`.
3. `composer require laravel/breeze` alone does nothing — also run `php artisan breeze:install blade`.
4. Reapply `app/Http/Requests/Auth/LoginRequest.php`'s `is_active` check + phone-or-email login logic after any Breeze reinstall.

**Migrate, link storage, and seed:**
```bash
php artisan migrate
php artisan storage:link
php artisan db:seed
```
`db:seed` creates the owner login (`echocrew@owner.com` / `password`) plus, in local/testing environments, a full set of realistic demo data across every module (`DemoDataSeeder`) — boxes/packets/items, movements, purchases, sales, customers, exchange/refinery batches, orders, notifications, and a handful of staff logins with different roles (all `password`). Don't run it against a database with real records.

**Build assets and run:**
```bash
npm run build   # or `npm run dev` while actively changing Blade/CSS/JS
php artisan serve
```

Visit `/` for the public landing page, `/login` for staff, `/portal/login` for customers, `/wireframes` for the 13 original static reference screens.

## Wireframes → Live Module Mapping

The 13 original client-approved wireframes in `resources/views/wireframes/` map to these live modules (see `CLAUDE.md` rule 6):

| Wireframe | Live module |
|---|---|
| `main.blade.php` (Dashboard) | Owner Dashboard (`/reports/dashboard`) |
| `inventory.blade.php` | Stock → Inventory (`/stock/items`) |
| `item-detail.blade.php` | Stock → Item Detail (`/stock/items/{item}`) |
| `box-packet.blade.php` | Stock → Box/Packet Management |
| `scan-stock.blade.php` | Stock → Assign to Packet (scan mode) |
| `move-stock.blade.php` | Movements → Vault ↔ Counter |
| `karigar-dispatch.blade.php` | Movements → Karigar Dispatch |
| `karigar-return.blade.php` | Movements → Karigar Return |
| `external-movement.blade.php` | Movements → Photo/Custom Purpose (photo upload) |
| `history.blade.php` | Stock → Item/Box/Packet Detail (history tab) |
| `logbook.blade.php` | Reports → Daily Logbook |
| `location-report.blade.php` | Reports → Location Report |

Modules that emerged **after** the original 13 wireframes were approved (Exchange, Refinery, Orders, Notifications, Loyalty, Installments, Accounting, Sales, Purchases, Admin) have no matching wireframe and instead follow `docs/DESIGN_SYSTEM.md` directly.

## Folder Structure

```
app/
  Models/
    Stock/         Item, Packet, Box, QrCode
    Movement/      Movement, RateLog, KarigarRawBatch
    Customer/      Customer, LoyaltyTransaction, InstallmentScheme, InstallmentPayment, CustomerMaterialJob
    Sales/         Sale
    Purchase/      Purchase, PurchaseItem, Vendor
    Exchange/      ExchangeTransaction, RefineryBatch
    Orders/        Order
    Notification/  PendingNotification
    Accounting/    Account, Transaction
    Pricing/       DiscountRule, GstRate
    User.php, Employee.php

  Services/
    PricingService.php           -- live price calculation, never stored
    PhotoCompressionService.php  -- WebP compression on every upload
    RateFetchService.php         -- daily rate API pull + manual fallback

  Livewire/
    Stock/, Movement/, Exchange/, Orders/, Pricing/, Sales/, Purchase/,
    Accounting/, Admin/, Loyalty/, Installments/, Notifications/,
    Portal/, Storefront/, Reports/, Layout/  -- every module listed in CLAUDE.md's Build status table

  Console/Commands/
    CleanupOldPhotos.php   -- daily, deletes movement photos >90 days
    FetchDailyRate.php     -- daily, pulls gold/silver rate
    CheckDiskUsage.php     -- daily, alerts if disk >80%

resources/views/
  components/layouts/app.blade.php     -- staff app shell: dark sidebar, topbar, search
  components/layouts/guest.blade.php   -- customer portal + plain auth pages
  components/ui/                       -- shared design-system components (icon, card, badge, button, table...)
  livewire/                            -- one folder per module, matching app/Livewire/
  wireframes/                          -- the 13 original client-approved screens, static, view-only

routes/
  web.php + one file per module (stock.php, movements.php, exchange.php, orders.php, sales.php,
  purchases.php, accounting.php, admin.php, loyalty.php, installments.php, notifications.php,
  pricing.php, reports.php, storefront.php, portal.php, wireframes.php)
  console.php  -- scheduler definitions (queue:work, cleanup, rate fetch, disk check)

database/
  migrations/   -- 36 migrations, chronological; see docs/SCHEMA_REFERENCE.md for the current shape
  seeders/      -- RolePermissionSeeder, DemoDataSeeder
```

## Git Workflow

```
main        -- production, protected, deploy-only
staging     -- pre-release testing (mirrors hosting env)
feature/*   -- one branch per module/feature, short-lived
```

- No direct pushes to `main`.
- Branch → PR → review → merge to `staging` → test on staging subdomain → merge to `main`.
- Tag every production release (`v1.0`, `v1.1`, ...) — makes rollback trivial on shared hosting where there's no easy infrastructure-level rollback.

**Commit messages:** reference the module (`stock:`, `movements:`, `sales:`) so history stays scannable as the app grows.

## Deployment (Hostinger — SSH + cron only, no CI server)

```bash
#!/bin/bash
# deploy.sh — run via SSH on the server
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

- `.env` is **not** in git — maintained manually per environment.
- Never run `migrate:fresh` in production — only `migrate`.
- Never run `db:seed --class=DemoDataSeeder` in production — demo data only, gated to `local`/`testing` environments in `DatabaseSeeder`.
- `config:cache` / `route:cache` matter more here — shared hosting has weaker CPU, caching saves real latency.

**Server cron — one line only, everything else is handled by Laravel's scheduler:**
```
* * * * * php /home/USERNAME/domains/YOURDOMAIN/public_html/artisan schedule:run >> /dev/null 2>&1
```

**Optional:** a GitHub Actions workflow that SSHs in and runs `deploy.sh` on push to `main` removes the manual SSH step — free tier covers this.

## Staging

Use a subdomain (`staging.yourdomain.com`) on the same hosting, separate database. Test every migration and integration (rate API, WhatsApp) here before touching production — there's no fast rollback on shared hosting, so staging is the safety net.

## Current Module Status

See `CLAUDE.md`'s Build status table — it's kept as the single source of truth for what's live vs. still open, so it doesn't drift out of sync with this file.
