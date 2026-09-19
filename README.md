# Radharani Jewellery ERP

Internal stock-management ERP + read-only ecommerce catalog for a jewellery shop. See `DEVELOPER_GUIDE.md` for system design, schema, and reasoning before making structural changes.

## Stack

Laravel 11 · Blade + Livewire · MySQL 8+ · Database-driven queue · Local disk (WebP) storage · Hostinger shared hosting (SSH + cron)

## First-Time Setup (local)

```bash
git clone https://github.com/pilgrimsage/radharani-erp.git
cd radharani-erp

composer install
composer require livewire/livewire spatie/laravel-permission spatie/laravel-activitylog intervention/image

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

**Publish package migrations:**
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
```

**Wire routes** — confirm these two lines exist at the end of `routes/web.php`:
```php
require __DIR__.'/stock.php';
require __DIR__.'/wireframes.php';
```

**Migrate and run:**
```bash
php artisan storage:link
php artisan migrate
php artisan serve
```

Visit `/wireframes` for the static approved screens, `/stock/items` for the live Stock module.

## Folder Structure

```
app/
  Models/
    Stock/       Item, Packet, Box
    Movement/    Movement, RateLog
    Sales/       Sale
    Purchase/    Purchase, Vendor
    Accounting/  Account, Transaction
    Customer/    Customer, LoyaltyTransaction, InstallmentScheme, InstallmentPayment
    User.php, Employee.php

  Services/
    PricingService.php           -- live price calculation, never stored
    PhotoCompressionService.php  -- WebP compression on every upload
    RateFetchService.php         -- daily rate API pull + manual fallback

  Livewire/
    Stock/       BoxManager, PacketManager, ItemManager  -- live, built

  Console/Commands/
    CleanupOldPhotos.php   -- daily, deletes movement photos >90 days
    FetchDailyRate.php     -- daily, pulls gold/silver rate
    CheckDiskUsage.php     -- daily, alerts if disk >80%

resources/views/
  components/layouts/app.blade.php   -- shared sidebar layout, matches approved wireframe style
  livewire/stock/                    -- live Stock module views
  wireframes/                        -- all 13 client-approved screens, static, view-only

routes/
  web.php         -- requires stock.php and wireframes.php
  stock.php       -- live Stock module routes
  wireframes.php  -- static wireframe routes
  console.php     -- scheduler definitions (queue:work, cleanup, rate fetch, disk check)
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
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

- `.env` is **not** in git — maintained manually per environment.
- Never run `migrate:fresh` in production — only `migrate`.
- `config:cache` / `route:cache` matter more here — shared hosting has weaker CPU, caching saves real latency.

**Server cron — one line only, everything else is handled by Laravel's scheduler:**
```
* * * * * php /home/USERNAME/domains/YOURDOMAIN/public_html/artisan schedule:run >> /dev/null 2>&1
```

**Optional:** a GitHub Actions workflow that SSHs in and runs `deploy.sh` on push to `main` removes the manual SSH step — free tier covers this.

## Staging

Use a subdomain (`staging.yourdomain.com`) on the same hosting, separate database. Test every migration and integration (rate API, WhatsApp) here before touching production — there's no fast rollback on shared hosting, so staging is the safety net.

## Current Module Status

| Module | Status |
|---|---|
| Stock (Box/Packet/Item) | ✅ Live, Livewire |
| Wireframes (all 13 screens) | ✅ Static reference only |
| Movements | ⬜ Not built |
| Sales / Billing | ⬜ Not built |
| Purchases / Vendors | ⬜ Not built |
| Accounting Ledger | ⬜ Not built |
| Customer / Loyalty / Installments | ⬜ Not built |
| Dashboard / History / Logbook / Reports | ⬜ Not built |

Build order and reasoning for each: see `DEVELOPER_GUIDE.md`, Section 4.
