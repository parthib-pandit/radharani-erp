# Radharani Jewellery ERP — Developer Guide

System design, schema, and reasoning for every structural decision. Read this before touching migrations or models.

---

## 1. What This System Is

An internal stock-audit ERP for a jewellery shop, with a read-only ecommerce catalog attached. **Not primarily an ecommerce app** — the core problem being solved is tamper-evident tracking of physical stock as it moves between vault, counter, karigars, hallmarking centers, and customers.

Everything in the schema serves one of these goals:
1. Know where every item physically is, right now, and prove it later.
2. Reprice the entire catalog instantly when gold/silver rates change.
3. Never let a staff member quietly edit history.
4. Run on shared hosting with SSH + cron only — no VPS, no Redis, no S3.

---

## 2. Stack & Why

| Choice | Why |
|---|---|
| Laravel 11 | Long support window, everything else here builds on it |
| Blade + Livewire (not Vue/React SPA) | No separate API layer, no frontend build pipeline to break on shared hosting, matches small-team size |
| MySQL 8+ | JSON columns for flexible fields, what Hostinger provides |
| Database queue driver | No Redis available — `jobs` table needs zero extra infrastructure |
| Local disk + WebP | No S3 on this hosting plan — WebP compression is what keeps 20GB from filling up |
| `spatie/laravel-permission` | Real roles/permissions instead of a flat `role` enum — don't hand-roll this |
| `spatie/laravel-activitylog` | Full audit trail on every write — proven package, not custom code |

**If hosting ever upgrades:** storage swaps from `local` to `s3` in `filesystems.php`, queue swaps from `database` to `redis` in `.env`. No schema or business-logic change either way — this is intentional.

---

## 3. Schema — Full Reference

The complete, current schema (all 23 migrations, every column, every relationship) lives in **`SCHEMA_REFERENCE.md`**, including two ER diagrams — read that alongside this section. What follows here is the *reasoning* behind the non-obvious decisions; the reference doc is the source of truth for exact columns.

### Stock Hierarchy — `boxes`, `packets`, `items`

Three-level containment: Box → Packet → Item. All nullable at the parent level, because an item can exist before it's assigned a packet (matches the "fill up later" workflow).

**Why `pair_group_id` on items, not a separate pairs table:** earrings/bangles are two physical pieces with potentially different weights, but sold and displayed as one product. One row per physical piece, linked by a shared group ID, keeps weight-based pricing accurate per piece while still letting the UI show them as a pair.

**Why `huid_code` and `internal_code` are separate columns:** an item has exactly one active ID type — government HUID (mandatory over 2g) or an auto-generated 7-character fallback — never both. Reporting needs to know which kind of ID it's looking at.

### Movements — `movements`

**The core table.** One polymorphic table (`trackable_type` + `trackable_id`) instead of seven separate tables for vault, karigar, hallmarking, photography, custom-purpose, order, and melt movements — because every one of those workflows is structurally identical: something leaves, gets photographed, comes back, gets photographed, expires after 90 days.

**Why this matters practically:**
- "Where is item X right now" is one query (`ORDER BY created_at DESC LIMIT 1`), not seven joins
- One cleanup job handles all photo expiry, not seven
- A new movement type is a new enum value, not a new table

**Why rows are never updated or deleted:** every dispatch and return is its own row. A correction is a new row (`movement_type = 'correction'`, `reverses_movement_id` pointing at the mistake, `approved_by` set to the owner's user ID) — the original is never touched. This is the actual mechanism behind "no tampering," not just a label on the table.

### Pricing — `rate_logs`

Price is **never stored on an item.** It's calculated live in `PricingService` from the latest `rate_logs` row at read time:

```
price = (weight × rate) + making_charge + huid_charge
```

This is why a single rate update reprices the entire catalog instantly — there's nothing to update, because nothing was stored. `source` (`manual`/`api`) tracks whether the rate came from a live market API or a manual override; manual always wins if entered, since the shop must never depend on a third-party API for something this operationally critical.

### Sales — `sales`, `sale_items`

**Why `sale_items.price_at_sale` freezes the price:** live pricing is correct *before* a sale, but once sold, the price must never move again even if the gold rate changes the next day. This is the one deliberate exception to "never store a price."

GST fields (`cgst`, `sgst`, `igst`, `invoice_number`) are split, not a flat total, because GST law requires the split shown on the invoice and requires sequential, gap-free invoice numbering per financial year.

### Purchases — `vendors`, `purchases`, `purchase_items`

Separate from `movements` on purpose. **Movements answer "where is it physically"; purchases answer "what do we owe for it."** A karigar delivering finished goods is both a `karigar_in` movement and a `purchase` — conflating the two would make vendor payables untrackable independent of stock location.

### Accounting Ledger — `accounts`, `transactions`

**Not a Tally replacement.** A thin double-entry-style ledger that every `sale` and `purchase` writes to automatically (debit/credit rows), so a clean Tally-compatible export is always possible without manual reconciliation. Trial balance, P&L, balance sheet — deliberately out of scope, Tally already does that well.

### Users, Roles, Employees — `users`, `roles`, `permissions`, `employees`

**Why `users` and `employees` are separate tables:** not every employee needs system login (a helper or cleaner, for instance), and HR data (salary, designation) shouldn't live next to authentication credentials. `users.employee_id` links the two only where a login is actually needed.

**Why roles/permissions instead of a flat enum:** a flat `role` column can't express "this accountant can approve sales but not edit rates." Spatie's package gives configurable per-role permissions without a schema change every time a new role is needed.

### Customer features — `customers`, `loyalty_transactions`, `installment_schemes`, `installment_payments`, `loyalty_settings`

Loyalty is a ledger (`loyalty_transactions`), not a running counter, for the same audit reason as movements — any dispute is answerable from history, not just a trusted total. Installments split scheme (the plan) from payments (each actual payment) so partial/late payments are traceable and a 12th-month bonus can be triggered from real payment history.

**Why `loyalty_settings` is a table, not config values in code:** points-per-rupee and the referral bonus are business decisions the owner needs to tune without a developer redeploying anything. It's a deliberately single-row table (`LoyaltySetting::current()` is the only access pattern) — one place to change the rate, immediately live everywhere `LoyaltyService` is called.

**Referral bonus timing:** paid to the referrer only on the referred customer's *first confirmed sale*, not at signup — otherwise the bonus is gameable by creating empty accounts. The owner-facing `ReferralOverview` screen shows exactly which referrals are still "pending first purchase" vs. "bonus earned," so this isn't a black box.

**Customer portal auth is fully separate** from staff auth — see Section 3 of `SCHEMA_REFERENCE.md`. A customer logs in with phone+password on the `customer` guard and can never reach staff-only routes; the two tables (`users` vs `customers`) are never merged.

### Activity Log — via `spatie/laravel-activitylog`

Every write to `movements`, `sales`, `purchases` is attributed to an authenticated `user_id` and logged with IP and timestamp. This — combined with never allowing hard edits — is what actually delivers "no tampering," not the movements table alone.

---

## 4. The Scaffold — What's Actually Built vs. What's Next

**Built:**
- All 23 migrations (schema in `SCHEMA_REFERENCE.md`, fully in place)
- Models, organized by domain folder (`app/Models/Stock/`, `Movement/`, `Sales/`, `Purchase/`, `Accounting/`, `Customer/`, `Pricing/`)
- `PricingService` (with discount rules applied), `PhotoCompressionService`, `RateFetchService`, `LoyaltyService`
- 3 scheduled console commands (photo cleanup, rate fetch, disk usage alert) — all wired through `routes/console.php` so a single cron entry drives everything
- **Stock module**, fully live: `BoxManager`, `PacketManager`, `ItemManager`
- **Admin module**, fully live: `EmployeeManager`, `UserManager`, `RoleManager`, `LoyaltySettingsManager`, `ReferralOverview`
- **Customer portal**, fully live: phone+password login, dashboard with Purchases/Loyalty/Installments tabs, referral list
- **Wireframes**, static: all 13 client-approved screens, reference-only, no logic

**Not built yet — build in this order, each depends on the last:**
1. **Movements module** — live version of KarigarDispatch, KarigarReturn, ExternalMovement, MoveStock, ScanStock. Depends on Stock (done).
2. **Sales module** — live billing screen, GST invoice generation. This is also where `LoyaltyService::awardForSale()` gets wired in and where the manual `sales.discount` stacks on top of automatic `discount_rules`. Depends on Movements (for item status).
3. **Purchases + Vendor module**
4. **Accounting ledger wiring** — auto-write `transactions` rows from Sales/Purchases
5. **Dashboard, History, Logbook, Location Report, Audit Log viewer** — mostly read-only aggregation views, build last since they depend on everything else having real data

**Why this order:** every later module reads from Items and Movements. Building Sales before Movements works exist would mean faking "current item status," which then needs rework once Movements is real.

---

## 5. Non-Negotiable Rules When Extending This

- **Never `UPDATE` or `DELETE` a `movements`, `sales`, or `purchases` row.** Corrections are new rows referencing the original.
- **Never store a calculated price** except in `sale_items.price_at_sale`, which is a deliberate snapshot.
- **Every write needs a `user_id`.** No anonymous or shared-login actions anywhere.
- **Photos go through `PhotoCompressionService`.** Never save an uploaded file directly — WebP + resize is what keeps disk usage bounded.
- **New async work goes through the queue (`database` driver), not the request cycle.** Anything slow (compression, WhatsApp, exports) blocks a staff member on a phone otherwise.
